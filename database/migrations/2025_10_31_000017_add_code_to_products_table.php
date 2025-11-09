<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('code', 16)->nullable()->unique()->after('category_id');
            });
        }

        $productsWithoutCode = DB::table('products')
            ->whereNull('code')
            ->select('id')
            ->get();

        if ($productsWithoutCode->isEmpty()) {
            return;
        }

        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $segment = function (int $length) use ($alphabet): string {
            $result = '';
            $maxIndex = strlen($alphabet) - 1;

            for ($i = 0; $i < $length; $i++) {
                $result .= $alphabet[random_int(0, $maxIndex)];
            }

            return $result;
        };

        $generator = function () use ($segment) {
            return $segment(3) . '-' . $segment(4);
        };

        foreach ($productsWithoutCode as $product) {
            do {
                $code = $generator();
                $exists = DB::table('products')->where('code', $code)->exists();
            } while ($exists);

            DB::table('products')
                ->where('id', $product->id)
                ->update(['code' => $code]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'code')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_code_unique');
            $table->dropColumn('code');
        });
    }
};
