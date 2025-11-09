<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'discount', 'tax', 'notes']);
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->text('notes')->nullable();
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->decimal('discount', 15, 2)->default(0);
        });
    }
};
