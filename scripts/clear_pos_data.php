<?php

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

/** @var Kernel $kernel */
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

DB::statement('SET FOREIGN_KEY_CHECKS=0');

$tables = [
    TransactionDetail::class,
    Transaction::class,
    StockMovement::class,
    ProductUnit::class,
    Product::class,
];

foreach ($tables as $modelClass) {
    /** @var \Illuminate\Database\Eloquent\Model $modelClass */
    $modelClass::truncate();
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "Cleared transactions and products successfully." . PHP_EOL;
