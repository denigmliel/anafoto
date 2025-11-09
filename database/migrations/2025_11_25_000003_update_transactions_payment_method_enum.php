<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('transactions')
            ->where('payment_method', 'credit')
            ->update(['payment_method' => 'cash']);

        DB::statement("ALTER TABLE `transactions` MODIFY `payment_method` ENUM('cash','debit','qris','transfer') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `transactions` MODIFY `payment_method` ENUM('cash','debit','credit','qris','transfer') NOT NULL");
    }
};
