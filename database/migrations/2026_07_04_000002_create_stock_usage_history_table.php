<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('stock_usage_history')) {
            Schema::create('stock_usage_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ingredient_id')->constrained('products')->cascadeOnDelete();
                $table->date('tanggal');
                $table->decimal('jumlah_terpakai', 12, 2)->default(0);
                $table->timestamps();

                $table->unique(['ingredient_id', 'tanggal']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_usage_history');
    }
};
