<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',    // ID Menu (Misal: Es Kopi Susu)
        'ingredient_id', // ID Bahan Baku (Misal: Biji Kopi)
        'usage_amount'   // Jumlah pemakaian (Misal: 18)
    ];

    // Relasi ke Product (Menu/Produk)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Ingredient (Bahan Baku)
    public function ingredient()
    {
        return $this->belongsTo(Product::class, 'ingredient_id');
    }
}
