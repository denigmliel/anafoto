<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // No reliable way to restore previous role assignments.
    }
};
