<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relasi ke Product (satu kategori bisa punya banyak produk/bahan)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
