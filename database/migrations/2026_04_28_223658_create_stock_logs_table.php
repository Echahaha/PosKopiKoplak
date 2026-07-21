<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out']); // 'in' untuk tambah stok, 'out' untuk stok berkurang
            $table->integer('amount'); // Jumlah barangnya
            $table->string('reason')->nullable(); // Alasan (Misal: "Penjualan #INV-001" atau "Restock Mingguan")
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
