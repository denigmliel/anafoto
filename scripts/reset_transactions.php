<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Illuminate\Support\Facades\DB::transaction(function () {
    \App\Models\TransactionDetail::with('product')->chunk(200, function ($details) {
        foreach ($details as $detail) {
            $product = $detail->product;

            if ($product) {
                $product->increment('stock', $detail->quantity);
            }
        }
    });

    \App\Models\StockMovement::where('reference_type', 'transaction')->delete();
    \App\Models\TransactionDetail::query()->delete();
    \App\Models\Transaction::query()->delete();
});

echo "All transaction records have been cleared.\n";
