<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('kode_opname')->unique(); // contoh: OPN-20260630-001
            $table->date('tanggal_opname');
            $table->foreignId('user_id')->constrained('users'); // yang melakukan opname
            $table->enum('status', ['draft', 'selesai'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
