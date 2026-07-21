<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opnames')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products'); // bahan baku
            $table->integer('stok_sistem'); // snapshot stok saat opname dibuat
            $table->integer('stok_fisik')->nullable(); // diisi user pas hitung manual
            $table->integer('selisih')->nullable(); // stok_fisik - stok_sistem
            $table->text('keterangan')->nullable(); // alasan selisih: rusak/hilang/salah catat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_details');
    }
};
