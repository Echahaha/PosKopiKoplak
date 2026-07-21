<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'amount',
        'reason',
        'source',
        'user_id',
        'stock_opname_id',
    ];

    // TAMBAHKAN INI:
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
