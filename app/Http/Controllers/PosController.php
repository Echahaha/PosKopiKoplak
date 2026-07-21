<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\StockLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\StockStatusService;

class PosController extends Controller
{
    private const ADDON_PRICES = [
        'Cup Hot' => 0,
        'Cup Ice' => 0,
        'Gula Pasir' => 0,
        'Gula Aren Bubuk' => 0,
        'Oat Milk' => 10000,
    ];

    public function index()
    {
        // Menampilkan semua produk yang ditandai sebagai menu jual
        // Filter stok dihilangkan karena stok menu bergantung pada bahan baku (resep)
        $products = Product::where('is_menu', true)->get();
        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.id' => ['required', 'integer', 'exists:products,id'],
            'cart.*.qty' => ['required', 'integer', 'min:1'],
            'cart.*.addons' => ['nullable', 'array'],
            'cart.*.addons.*' => ['string'],
            'pay_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:Cash,Debit,QRIS'],
        ]);

        try {
            $transactionId = DB::transaction(function () use ($validated) {
                $cart = $validated['cart'];
                $paymentMethod = $validated['payment_method'];
                $totalAmount = 0;

                foreach ($cart as $item) {
                    $product = Product::with('recipes')->findOrFail($item['id']);
                    $qty = (int) $item['qty'];
                    $addons = $this->normalizeAddons($item['addons'] ?? []);
                    $addonTotal = $this->hitungTotalAddon($addons);

                    $totalAmount += ((int) $product->price + $addonTotal) * $qty;

                    foreach ($product->recipes as $recipe) {
                        $ingredient = Product::lockForUpdate()->find($recipe->ingredient_id);
                        if (!$ingredient) {
                            throw new \Exception("Bahan resep untuk {$product->name} tidak ditemukan.");
                        }

                        $totalUsage = $qty * $recipe->usage_amount;
                        if ($ingredient->stock < $totalUsage) {
                            throw new \Exception("Stok {$ingredient->name} tidak cukup untuk {$product->name}.");
                        }
                    }

                    foreach ($addons as $addon) {
                        $addonProduct = Product::where('name', $addon)->lockForUpdate()->first();
                        if ($addonProduct && $addonProduct->stock < $qty) {
                            throw new \Exception("Stok add-on {$addonProduct->name} tidak cukup.");
                        }
                    }
                }

                $payAmount = $paymentMethod === 'Cash'
                    ? (int) ($validated['pay_amount'] ?? 0)
                    : $totalAmount;

                if ($payAmount < $totalAmount) {
                    throw new \Exception('Nominal bayar kurang dari total transaksi.');
                }

                $transaction = Transaction::create([
                    'invoice_number' => 'INV-' . strtoupper(uniqid()),
                    'total_amount' => $totalAmount,
                    'pay_amount' => $payAmount,
                    'change_amount' => $payAmount - $totalAmount,
                    'payment_method' => $paymentMethod,
                    'user_id' => Auth::id(),
                ]);

                foreach ($cart as $item) {
                    $product = Product::with('recipes')->findOrFail($item['id']);
                    $qty = (int) $item['qty'];
                    $addons = $this->normalizeAddons($item['addons'] ?? []);
                    $addonTotal = $this->hitungTotalAddon($addons);

                    // 1. Simpan detail transaksi seperti biasa
                    $transaction->details()->create([
                        'product_id' => $item['id'],
                        'quantity' => $qty,
                        'price_at_time' => $product->price,
                        'addons' => $addons,
                        'addon_total' => $addonTotal,
                    ]);

                    // 2. Jalankan pengurangan stok resep (Kopi, Susu, dll)
                    if ($product && $product->recipes->count() > 0) {
                        foreach ($product->recipes as $recipe) {
                            $ingredient = Product::lockForUpdate()->find($recipe->ingredient_id);
                            if ($ingredient) {
                                $totalUsage = $qty * $recipe->usage_amount;
                                $ingredient->decrement('stock', $totalUsage);

                                // Log stok bahan baku standar
                                StockLog::create([
                                    'product_id' => $ingredient->id,
                                    'type' => 'out',
                                    'amount' => $totalUsage,
                                    'reason' => 'Bahan untuk ' . $product->name,
                                ]);
                            }
                        }
                    }

                    // 3. LOGIKA TAMBAHAN: Cek jika Take Away
                    foreach ($addons as $addon) {
                        $addonProduct = Product::where('name', $addon)->lockForUpdate()->first();
                        if ($addonProduct) {
                            $addonProduct->decrement('stock', $qty);
                            StockLog::create([
                                'product_id' => $addonProduct->id,
                                'type' => 'out',
                                'amount' => $qty,
                                'reason' => 'Add-on ' . $addon . ' untuk ' . $product->name . ' (#' . $transaction->invoice_number . ')',
                            ]);
                        }
                    }
                }
                return $transaction->id;
            });

            app(StockStatusService::class)->clearCache();
            return response()->json(['message' => 'Sukses!', 'transaction_id' => $transactionId]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function normalizeAddons(array $addons): array
    {
        return array_values(array_unique(array_filter(
            $addons,
            fn ($addon) => is_string($addon) && array_key_exists($addon, self::ADDON_PRICES)
        )));
    }

    private function hitungTotalAddon(array $addons): int
    {
        return array_sum(array_map(fn ($addon) => self::ADDON_PRICES[$addon], $addons));
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        $request->validate([
            'payment_method' => ['required', 'in:Cash,Debit,QRIS'],
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'payment_method' => $request->payment_method
        ]);

        return back()->with('success', 'Metode pembayaran berhasil dikoreksi!');
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with('details.product')->findOrFail($id);
        return view('pos.receipt', compact('transaction'));
    }

    public function void($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $transaction = Transaction::with('details.product.recipes')->findOrFail($id);

                // Kembalikan stok berdasarkan apakah produk memiliki resep atau tidak
                foreach ($transaction->details as $detail) {
                    $product = $detail->product;

                    if ($product && $product->recipes->count() > 0) {
                        // Jika menu racikan, kembalikan stok ke tiap bahan bakunya
                        foreach ($product->recipes as $recipe) {
                            $ingredient = Product::find($recipe->ingredient_id);
                            if ($ingredient) {
                                $returnAmount = $detail->quantity * $recipe->usage_amount;
                                $ingredient->increment('stock', $returnAmount);

                                // Catat log pengembalian stok bahan
                                StockLog::create([
                                    'product_id' => $ingredient->id,
                                    'type' => 'in',
                                    'amount' => $returnAmount,
                                    'reason' => 'Void Transaksi #' . $transaction->invoice_number,
                                ]);
                            }
                        }
                    } elseif ($product) {
                        // Jika bukan racikan, kembalikan ke stok produk itu sendiri
                        $product->increment('stock', $detail->quantity);

                        StockLog::create([
                            'product_id' => $product->id,
                            'type' => 'in',
                            'amount' => $detail->quantity,
                            'reason' => 'Void Transaksi #' . $transaction->invoice_number,
                        ]);
                    }

                    foreach (($detail->addons ?? []) as $addon) {
                        $addonProduct = Product::where('name', $addon)->first();
                        if ($addonProduct) {
                            $addonProduct->increment('stock', $detail->quantity);

                            StockLog::create([
                                'product_id' => $addonProduct->id,
                                'type' => 'in',
                                'amount' => $detail->quantity,
                                'reason' => 'Void add-on ' . $addon . ' #' . $transaction->invoice_number,
                            ]);
                        }
                    }
                }

                $transaction->details()->delete();
                $transaction->delete();
            });

            app(StockStatusService::class)->clearCache();
            return back()->with('success', 'Transaksi berhasil di-void dan stok bahan baku dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
