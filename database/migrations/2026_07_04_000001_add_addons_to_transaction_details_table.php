<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_details', 'addons')) {
                $table->json('addons')->nullable()->after('price_at_time');
            }

            if (!Schema::hasColumn('transaction_details', 'addon_total')) {
                $table->integer('addon_total')->default(0)->after('addons');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_details', 'addon_total')) {
                $table->dropColumn('addon_total');
            }

            if (Schema::hasColumn('transaction_details', 'addons')) {
                $table->dropColumn('addons');
            }
        });
    }
};
