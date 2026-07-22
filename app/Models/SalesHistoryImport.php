<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesHistoryImport extends Model
{
    /**
     * Tabel yang digunakan (data penjualan historis dari Kasir Pintar).
     */
    protected $table = 'sales_history_import';

    /**
     * Kolom timestamps: tabel hanya punya created_at, tidak punya updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'kode_barang_asal',
        'nama_barang',
        'kategori',
        'qty_terjual',
        'pendapatan',
        'product_id',
        'matched_status',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'pendapatan'  => 'decimal:2',
        'qty_terjual' => 'integer',
    ];

    /**
     * Relasi ke produk di POS (jika berhasil di-match).
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
