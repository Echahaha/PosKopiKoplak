<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Hitung HPP (Harga Pokok Penjualan) per sajian
     */
    private function calculateHPP($product)
    {
        $hpp = 0;

        foreach ($product->recipes as $recipe) {
            $ingredient = $recipe->ingredient;
            
            // purchase_price sudah merupakan harga per satuan (unit)
            if ($ingredient && $ingredient->purchase_price > 0) {
                $hpp += $recipe->usage_amount * $ingredient->purchase_price;
            }
        }

        return $hpp;
    }

    /**
     * Tampilkan halaman resep untuk menu tertentu
     */
    public function index($productId)
    {
        $product = Product::with('recipes.ingredient')->findOrFail($productId);
        
        // Pastikan product adalah menu
        if (!$product->is_menu) {
            abort(403, 'Hanya menu yang bisa memiliki resep');
        }

        // Hitung HPP
        $hpp = $this->calculateHPP($product);

        // Ambil daftar bahan baku yang tersedia (bukan menu)
        $ingredients = Product::where('is_menu', false)->get();

        return view('recipes.index', compact('product', 'ingredients', 'hpp'));
    }

    /**
     * Simpan resep (tambah bahan ke menu)
     */
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // Validasi input
        $validated = $request->validate([
            'ingredient_id' => [
                'required',
                'exists:products,id',
                'different:' . $productId,
            ],
            'usage_amount' => 'required|numeric|min:0.1',
        ], [
            'ingredient_id.required' => 'Pilih bahan baku terlebih dahulu',
            'ingredient_id.exists' => 'Bahan baku tidak ditemukan',
            'ingredient_id.different' => 'Bahan tidak boleh sama dengan menu',
            'usage_amount.required' => 'Jumlah pemakaian harus diisi',
            'usage_amount.numeric' => 'Jumlah pemakaian harus berupa angka',
            'usage_amount.min' => 'Jumlah pemakaian harus lebih dari 0',
        ]);

        // Cek apakah bahan sudah ada dalam resep
        $existing = Recipe::where('product_id', $productId)
            ->where('ingredient_id', $validated['ingredient_id'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Bahan ini sudah ada dalam resep menu ini');
        }

        // Buat resep baru
        Recipe::create([
            'product_id' => $productId,
            'ingredient_id' => $validated['ingredient_id'],
            'usage_amount' => $validated['usage_amount'],
        ]);

        return back()->with('success', 'Bahan baku berhasil ditambahkan ke resep!');
    }

    /**
     * Hapus bahan dari resep
     */
    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();

        return back()->with('success', 'Bahan baku dihapus dari resep.');
    }
}