<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix two issues in the recipes table:
 *
 * BUG 10: usage_amount was defined as integer but validated as numeric
 *         (allowing decimals like 18.5). Change to decimal(10,2) so
 *         fractional values are stored correctly.
 *
 * BUG 8:  ingredient_id FK had no onDelete clause. If a bahan baku is
 *         deleted, orphan recipe rows would cause errors during
 *         transactions. Add cascade delete.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // BUG 10: Change usage_amount from integer to decimal
            $table->decimal('usage_amount', 10, 2)->change();
        });

        // BUG 8: Rebuild ingredient_id FK with onDelete cascade
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->foreign('ingredient_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('usage_amount')->change();
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
        });

        Schema::table('recipes', function (Blueprint $table) {
            $table->foreign('ingredient_id')
                ->references('id')
                ->on('products');
        });
    }
};
