<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $email = 'anafotocopy76@gmail.com';
        $password = 'anafoto76';

        $user = DB::table('users')->where('email', $email)->first();
        if ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($password),
                    'role' => $user->role ?: 'admin',
                    'is_active' => $user->is_active ?? true,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // intentionally left blank
    }
};
