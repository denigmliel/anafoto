<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class TransactionDetailExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles, WithEvents, WithTitle
{
    private $start;
    private $end;
    private string $rangeLabel;

    public function __construct($start, $end, string $rangeLabel)
    {
        $this->start = $start;
        $this->end = $end;
        $this->rangeLabel = $rangeLabel;
    }

    public function collection()
    {
        $transactions = Transaction::with(['user', 'details'])
            ->whereBetween('transaction_date', [$this->start, $this->end])
            ->orderBy('transaction_date')
            ->get();

        $rows = new Collection();

        foreach ($transactions as $transaction) {
            if ($transaction->details->isEmpty()) {
                $rows->push($this->mapRow($transaction, null));
                continue;
            }

            foreach ($transaction->details as $detail) {
                $rows->push($this->mapRow($transaction, $detail));
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode',
            'Kasir',
            'Metode Pembayaran',
            'Subtotal',
            'Total',
            'Dibayar',
            'Kembalian',
            'Item',
            'Qty',
            'Unit',
            'Harga',
            'Subtotal Item',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '#,##0.00', // Subtotal
            'F' => '#,##0.00', // Total
            'G' => '#,##0.00', // Dibayar
            'H' => '#,##0.00', // Kembalian
            'J' => '#,##0',    // Qty
            'L' => '#,##0.00', // Harga
            'M' => '#,##0.00', // Subtotal Item
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                $sheet->setAutoFilter($sheet->calculateWorksheetDimension());
            },
        ];
    }

    public function title(): string
    {
        return 'Transaksi ' . ucfirst($this->rangeLabel);
    }

    private function mapRow(Transaction $transaction, $detail): array
    {
        return [
            optional($transaction->transaction_date)->format('d-m-Y H:i'),
            $transaction->code,
            $transaction->user->name ?? 'Kasir',
            $transaction->payment_method,
            (float) $transaction->subtotal,
            (float) $transaction->total_amount,
            (float) $transaction->payment_amount,
            (float) ($transaction->change_amount ?? 0),
            $detail->product_name ?? '-',
            $detail->quantity ?? 0,
            $detail->unit ?? '-',
            (float) ($detail->price ?? 0),
            (float) ($detail->subtotal ?? 0),
        ];
    }
}
