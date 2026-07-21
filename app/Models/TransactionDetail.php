<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_id', 'product_id', 'quantity', 'price_at_time', 'addons', 'addon_total'];

    protected $casts = [
        'addons' => 'array',
    ];

    // TAMBAHKAN INI:
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
