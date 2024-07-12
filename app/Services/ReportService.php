<?php

namespace App\Services;

use App\Models\FarmerDetails;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;

class ReportService
{
    public function exportFarmerBalanceReport(Builder $farmers)
    {
        $file = fopen('php://output', 'w');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Fix for Excel

        $farmers->each(function (FarmerDetails $farmer) use ($file) {
            fputcsv($file, [
                'Farmer Id',
                'Farmer Name',
                'Balance'
            ]);

            fputcsv($file, [
                $farmer->id,
                $farmer->full_name,
                $farmer->faAccount->outstanding_amount . $farmer->faAccount->outstanding_amount > 0 ? ' - CR' : ' - DB'
            ]);
            fputcsv($file, [
                'Transaction Date',
                'Receipt Number',
                'Initial Balance',
                'Transaction Amount',
                'Balance Amount',
                'Transaction Type',
            ]);

            $farmer->transactions()->each(function (Transaction $transaction) use ($file) {
                fputcsv($file, [
                    $transaction->created_at,
                    $transaction->distribution->receipt_no,
                    $transaction->initial_balance,
                    $transaction->transaction_amount,
                    $transaction->balance_amount,
                    $transaction->transaction_type
                ]);
            });
        });
        fclose($file);
    }
}