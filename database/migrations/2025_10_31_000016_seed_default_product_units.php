<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $products = DB::table('products')->select('id', 'unit', 'price')->get();

        $now = now();
        $rows = $products->map(function ($product) use ($now) {
            return [
                'product_id' => $product->id,
                'name' => $product->unit ?: 'PCS',
                'price' => $product->price ?? 0,
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        if (! empty($rows)) {
            DB::table('product_units')->insert($rows);
        }
    }

    public function down(): void
    {
        DB::table('product_units')->delete();
    }
};
