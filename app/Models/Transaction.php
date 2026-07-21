<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['invoice_number', 'total_amount', 'pay_amount', 'change_amount', 'payment_method', 'user_id'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Relasi ke kasir yang membuat transaksi (boleh null jika data lama).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
