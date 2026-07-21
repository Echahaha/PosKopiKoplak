<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan input data ke kolom berikut
    protected $fillable = ['name', 'is_menu', 'category_id', 'price', 'purchase_price', 'stock', 'unit', 'min_stock', 'image'];

    // Jangan lupa tambahkan relasi ke kategori agar tidak error saat dipanggil di view
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
