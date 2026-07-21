<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Tampilkan halaman Master Data dengan tab:
     * Kategori | Bahan Baku | Produk | Resep | User
     */
    public function index()
    {
        // ── Tab Kategori ──
        $categories = Category::withCount('products')->orderBy('name')->get();

        // ── Tab Bahan Baku (is_menu = 0) ──
        $ingredients = Product::with('category')
            ->where('is_menu', false)
            ->orderBy('name')
            ->get();

        // ── Tab Produk / Menu Jual (is_menu = 1) ──
        $menus = Product::with('category', 'recipes')
            ->where('is_menu', true)
            ->orderBy('name')
            ->get();

        // ── Tab Resep: pakai data menus juga (untuk hitung jumlah bahan per menu) ──
        // ditampilkan sebagai tabel ringkas, drill-down ke recipes.index per produk

        // ── Tab User ──
        $users = User::orderByRaw("role = 'owner' DESC")->orderBy('name')->get();

        // Dropdown kategori untuk form tambah bahan/produk (dipakai di modal tab Bahan Baku & Produk)
        $allCategories = Category::orderBy('name')->get();

        // Dropdown bahan baku untuk keperluan info di tab Produk (opsional, jaga-jaga)
        $allIngredients = Product::where('is_menu', false)->orderBy('name')->get();

        return view('masterdata.index', compact(
            'categories',
            'ingredients',
            'menus',
            'users',
            'allCategories',
            'allIngredients'
        ));
    }
}
