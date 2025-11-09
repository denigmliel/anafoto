<?php

use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('kasir:clear-dummy-data', function () {
    $counts = DB::transaction(function () {
        $movementDeleted = StockMovement::query()
            ->where('type', 'out')
            ->where('reference_type', 'transaction')
            ->delete();

        $detailDeleted = TransactionDetail::query()->delete();
        $transactionDeleted = Transaction::query()->delete();

        return [
            'transactions' => $transactionDeleted,
            'details' => $detailDeleted,
            'movements' => $movementDeleted,
        ];
    });

    $this->info(sprintf(
        'Deleted %d transactions, %d transaction details, and %d stock movements.',
        $counts['transactions'],
        $counts['details'],
        $counts['movements']
    ));
})->purpose('Remove dummy transactions and related stock movements');
