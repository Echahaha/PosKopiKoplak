<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\StockStatusService;

class ProductController extends Controller
{
    public function index()
    {
        // 1. Ambil semua produk untuk ditampilkan di tabel
        $products = Product::with('category')->orderBy('name', 'asc')->get();

        // 2. Ambil Kategori untuk dropdown modal
        $categories = \App\Models\Category::all();

        // 3. AMBIL DATA BAHAN BAKU (is_menu = false) 
        // Ini yang tadi ketinggalan, supaya pilihan resep muncul
        $ingredients = Product::where('is_menu', false)->orderBy('name', 'asc')->get();

        // 4. Kirim semua variabel ke view
        return view('products.index', compact('products', 'categories', 'ingredients'));
    }
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Cek apakah produk ini pernah digunakan di transaksi
            $usedInTransactions = \App\Models\TransactionDetail::where('product_id', $product->id)->exists();
            if ($usedInTransactions) {
                return redirect()->route('products.index')->with('error', 'Produk "' . $product->name . '" tidak bisa dihapus karena sudah pernah digunakan dalam transaksi.');
            }

            // Cek apakah bahan baku ini dipakai di resep menu lain
            $usedAsIngredient = \App\Models\Recipe::where('ingredient_id', $product->id)->exists();
            if ($usedAsIngredient) {
                return redirect()->route('products.index')->with('error', 'Bahan "' . $product->name . '" tidak bisa dihapus karena masih digunakan di resep menu.');
            }

            // Hapus resep milik produk ini (jika menu) lalu hapus produk
            $product->recipes()->delete();
            $product->delete();

            return redirect()->route('products.index')->with('success', 'Produk dan data resep terkait berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // 1. Validasi agar tidak ada data penting yang kosong
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_menu' => 'required|boolean', // Memastikan tipe (Menu/Bahan) terpilih
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 2. Upload gambar setelah validasi berhasil
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        try {
            DB::transaction(function () use ($request, $imagePath) {
                // 3. Simpan data Produk atau Bahan Baku
                $product = Product::create([
                    'name'           => $request->name,
                    'category_id'    => $request->category_id,
                    'is_menu'        => $request->is_menu,
                    'price'          => $request->price ?? 0,
                    'purchase_price' => $request->purchase_price ?? 0,
                    'stock'          => $request->stock ?? 0,
                    'unit'           => $request->unit ?? ($request->is_menu == 1 ? 'cup' : 'gram'), // Pengaman unit
                    'min_stock'      => $request->min_stock ?? 0,
                    'image'          => $imagePath,
                ]);

                // 4. Jika yang diinput adalah MENU JUAL, simpan data resepnya
                if ($request->is_menu == 1 && $request->has('ingredients')) {
                    foreach ($request->ingredients as $key => $ingredient_id) {
                        // Hanya simpan jika bahan baku dipilih dan takarannya diisi
                        if (!empty($ingredient_id) && !empty($request->amounts[$key])) {
                            \App\Models\Recipe::create([
                                'product_id'    => $product->id,
                                'ingredient_id' => $ingredient_id,
                                'usage_amount'  => $request->amounts[$key]
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('products.index')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            // Jika ada error, balikkan ke form dengan pesan error
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $product = Product::findOrFail($id);

                // Simpan stok lama sebelum diupdate
                $oldStock = $product->stock;
                $newStock = $request->stock !== null ? (int)$request->stock : $product->stock;

                $updateData = [
                    'name'           => $request->name,
                    'category_id'    => $request->category_id,
                    'price'          => $request->price ?? $product->price,
                    'purchase_price' => $request->purchase_price ?? $product->purchase_price,
                    'stock'          => $newStock,
                    'unit'           => $request->unit ?? $product->unit,
                    'min_stock'      => $request->min_stock ?? $product->min_stock,
                ];

                if ($request->hasFile('image')) {
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $updateData['image'] = $request->file('image')->store('products', 'public');
                }

                $product->update($updateData);

                // Catat StockLog jika stok bahan baku berubah
                if (!$product->is_menu && $newStock != $oldStock) {
                    $selisih = $newStock - $oldStock;

                    StockLog::create([
                        'product_id' => $product->id,
                        'type'       => $selisih > 0 ? 'in' : 'out',
                        'amount'     => abs($selisih),
                        'reason'     => 'Penyesuaian stok manual (' . ($selisih > 0 ? '+' : '') . $selisih . ' ' . $product->unit . ')',
                    ]);
                }

                // Update resep jika Menu Jual
                if ($product->is_menu == 1) {
                    $product->recipes()->delete();

                    if ($request->has('ingredients')) {
                        foreach ($request->ingredients as $key => $ing_id) {
                            if (!empty($ing_id) && !empty($request->amounts[$key])) {
                                \App\Models\Recipe::create([
                                    'product_id'    => $product->id,
                                    'ingredient_id' => $ing_id,
                                    'usage_amount'  => $request->amounts[$key],
                                ]);
                            }
                        }
                    }
                }
            });

            app(StockStatusService::class)->clearCache();
            return back()->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function restock(Request $request)
    {
        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'restock_amount' => 'required|integer|min:1',
            'total_price'    => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);

                $oldStock = $product->stock;
                $oldPrice = $product->purchase_price;
                $addAmount = (int) $request->restock_amount;

                // Hitung harga modal per satuan dari total harga beli
                $newPrice = $oldPrice;
                if (!empty($request->total_price) && $request->total_price > 0) {
                    $newPrice = round($request->total_price / $addAmount);
                }

                $product->update([
                    'stock'          => $oldStock + $addAmount,
                    'purchase_price' => $newPrice,
                ]);

                $reasonText = 'Restock barang masuk (+' . $addAmount . ' ' . $product->unit . ')';
                if ($newPrice != $oldPrice) {
                    $reasonText .= ', harga modal/satuan diperbarui dari Rp ' . number_format($oldPrice, 0, ',', '.')
                        . ' menjadi Rp ' . number_format($newPrice, 0, ',', '.')
                        . ' (Total beli Rp ' . number_format($request->total_price, 0, ',', '.') . ')';
                }

                StockLog::create([
                    'product_id' => $product->id,
                    'type'       => 'in',
                    'amount'     => $addAmount,
                    'reason'     => $reasonText,
                ]);
            });

            app(StockStatusService::class)->clearCache();
            return back()->with('success', 'Stok berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal restock: ' . $e->getMessage());
        }
    }
    public function stockLogs()
    {
        // Mengambil log stok terbaru, diurutkan dari yang paling baru
        $logs = \App\Models\StockLog::with('product')->latest()->paginate(10);

        return view('products.logs', compact('logs'));
    }

    public function deleteImage($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
                $product->update(['image' => null]);
            }

            return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus foto: ' . $e->getMessage()], 500);
        }
    }
}
