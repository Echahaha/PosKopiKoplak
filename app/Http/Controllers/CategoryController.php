<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        Category::create($validated);

        return back()->with('success', 'Kategori "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    /**
     * Perbarui nama kategori.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
        ], [
            'name.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori.
     * Tidak boleh dihapus jika masih dipakai oleh produk/bahan baku (FK constraint).
     */
    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori "' . $category->name . '" tidak bisa dihapus karena masih dipakai oleh ' . $category->products()->count() . ' produk/bahan baku.');
        }

        $nama = $category->name;
        $category->delete();

        return back()->with('success', 'Kategori "' . $nama . '" berhasil dihapus.');
    }
}
