<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        // Update admin credentials to the desired default account with a proper bcrypt hash.
        $newEmail = 'anafotocopy76@gmail.com';
        $newPassword = 'anafoto76';

        // If the old default admin exists, update it.
        $oldAdmin = DB::table('users')->where('email', 'anafotocopy76@gmail.com')->first();
        if ($oldAdmin) {
            DB::table('users')
                ->where('id', $oldAdmin->id)
                ->update([
                    'email' => $newEmail,
                    'password' => Hash::make($newPassword),
                    'name' => $oldAdmin->name ?: 'Administrator',
                    'role' => $oldAdmin->role ?: 'admin',
                    'is_active' => $oldAdmin->is_active ?? true,
                    'updated_at' => now(),
                ]);

            return;
        }

        // If a record with the new email exists, just rehash the password to ensure bcrypt.
        $existing = DB::table('users')->where('email', $newEmail)->first();
        if ($existing) {
            DB::table('users')
                ->where('id', $existing->id)
                ->update([
                    'password' => Hash::make($newPassword),
                    'role' => $existing->role ?: 'admin',
                    'is_active' => $existing->is_active ?? true,
                    'updated_at' => now(),
                ]);

            return;
        }

        // Otherwise, create a fresh admin account.
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => $newEmail,
            'password' => Hash::make($newPassword),
            'role' => 'admin',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // No-op: we won't revert credential changes automatically.
    }
};
