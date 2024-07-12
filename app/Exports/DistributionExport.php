<?php

namespace App\Exports;

use App\Models\Distribution;
use App\Models\DistributionDetail;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DistributionExport implements FromCollection, ShouldAutoSize, WithStyles, WithHeadings
{
    use Exportable;
    protected array $farmerHeaderRows = [1];

    public function __construct(public Builder $distributions, public string $type)
    {

    }

    public function collection()
    {
        switch ($this->type) {
            case 'farmer_balances':
                return $this->exportFarmerBalance();
            case 'distribution_details':
                return $this->exportDistributionDetail();
        }
    }

    public function exportFarmerBalance(): Collection
    {
        $data = collect();

        $this->distributions->each(function (Distribution $distribution) use ($data) {
            $farmer = $distribution->farmer;

            $outstandingAmount = $farmer->faAccount->outstanding_amount;
            $data->push([
                $farmer->id,
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

            $distribution->transactions()->orderBy('id', 'desc')->get()->each(function (Transaction $transaction) use ($data, $distribution) {
                $data->push([
                    '',
                    $transaction->created_at,
                    $distribution->receipt_no,
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

    public function exportDistributionDetail(): Collection
    {
        $data = collect();

        $this->distributions->each(function (Distribution $distribution) use ($data) {
            $farmer = $distribution->farmer;

            $data->push([
                $distribution->receipt_no,
                $farmer->cooperative->cooperative_code ?? null,
                $farmer->cooperative->name ?? null,
                $distribution->staff->first_name . ' ' . $distribution->staff->last_name,
                $farmer->farmer_code,
                $farmer->full_name,
                $distribution->total_amount,
            ]);

            $data->push([
                '',
                'Product Category',
                'Product Name',
                'Quantity',
                'Unit',
                'Available Stocks',
                'Price Per Unit',
                'Sub Total',
            ]);


            $distribution->distributionDetails()->each(function (DistributionDetail $distributionDetail) use ($data, $distribution) {
                $data->push([
                    '',
                    $distributionDetail->category_name,
                    $distributionDetail->product_name,
                    $distributionDetail->quantity,
                    $distributionDetail->unit,
                    $distributionDetail->available_stocks,
                    $distributionDetail->price_per_unit,
                    $distributionDetail->sub_total
                ]);
            });
            $data->push(['']);

            $farmerHeaderRow = $data->count() + 1;
            $this->farmerHeaderRows[] = $farmerHeaderRow;
        });
        return $data;
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

    public function headings(): array
    {
        switch ($this->type) {
            case 'distribution_details':
                return [
                    'Receipt Number',
                    'Cooperative Code',
                    'Cooperative Name',
                    'Agent Name',
                    'Farmer Code',
                    'Farmer Name',
                    'Total Amount',
                ];
            case 'farmer_balances':
                return [
                    'Farmer Id',
                    'Farmer Code',
                    'Farmer Name',
                    'Balance',
                ];
        }
    }
}
