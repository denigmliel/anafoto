<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'is_stock_unlimited')) {
                $table->boolean('is_stock_unlimited')
                    ->default(false)
                    ->after('stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_stock_unlimited')) {
                $table->dropColumn('is_stock_unlimited');
            }
        });
    }
};
