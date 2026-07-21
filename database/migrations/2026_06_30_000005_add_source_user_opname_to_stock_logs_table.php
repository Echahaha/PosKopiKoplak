<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_logs', 'source')) {
                // 'transaksi' | 'void' | 'manual' | 'opname'
                $table->string('source')->default('manual')->after('reason');
            }
            if (!Schema::hasColumn('stock_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('source')
                    ->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('stock_logs', 'stock_opname_id')) {
                $table->foreignId('stock_opname_id')->nullable()->after('user_id')
                    ->constrained('stock_opnames')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            foreach (['stock_opname_id', 'user_id', 'source'] as $col) {
                if (Schema::hasColumn('stock_logs', $col)) {
                    if (in_array($col, ['user_id', 'stock_opname_id'])) {
                        $table->dropForeign([$col]);
                    }
                    $table->dropColumn($col);
                }
            }
        });
    }
};
