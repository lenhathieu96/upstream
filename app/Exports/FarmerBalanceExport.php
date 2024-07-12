<?php

namespace App\Exports;

use App\Models\FarmerDetails;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FarmerBalanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;
    protected array $farmerHeaderRows = [1];
    public function __construct(public Builder $farmers)
    {
    }

    public function collection()
    {
        $data = collect();

        $this->farmers->each(function (FarmerDetails $farmer) use ($data) {
            $outstandingAmount = $farmer->faAccount->outstanding_amount;

            $data->push([
                $farmer->farmer_code,
                $farmer->full_name,
                abs($outstandingAmount) . ' - ' . ($outstandingAmount > 0 ? 'CR' : 'DB'),
            ]);

            $data->push([
                '',
                'Transaction Date',
                'Receipt Number',
                'Transaction Type',
                'Initial Balance',
                'Transaction Amount',
                'Balance Amount',
            ]);

            $farmer->transactions()->orderBy('id', 'desc')->each(function (Transaction $transaction) use ($data) {
                $data->push([
                    '',
                    $transaction->created_at,
                    empty($transaction->distribution) ? (string)$transaction->procurement->receipt_no : (string)$transaction->distribution->receipt_no,
                    $transaction->transaction_type,
                    empty($transaction->initial_balance) ? '0' : $transaction->initial_balance,
                    $transaction->transaction_amount,
                    $transaction->balance_amount,
                ]);
            });
            $data->push(['']);
            $farmerHeaderRow = $data->count() + 1;
            $this->farmerHeaderRows[] = $farmerHeaderRow;
        });

        return $data;
    }

    public function headings(): array
    {
        return [
            'Farmer Code',
            'Farmer Name',
            'Balance'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        foreach ($this->farmerHeaderRows as $farmerHeaderRow) {
            $sheet->getStyle("A1:Z1")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
            $txnHeaderRow = $farmerHeaderRow + 2;
            $sheet->getStyle("A$txnHeaderRow:Z$txnHeaderRow")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
        }
    }
}
