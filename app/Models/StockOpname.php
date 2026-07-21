<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = ['kode_opname', 'tanggal_opname', 'user_id', 'status', 'catatan'];

    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
