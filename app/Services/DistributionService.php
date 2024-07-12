<?php

namespace App\Services;

use App\Models\Distribution;
use App\Models\DistributionBalance;
use App\Models\DistributionDetail;
use App\Models\FaAccount;
use App\Models\FarmerDetails;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DistributionService
{
    public function create(array $attribute): ?Distribution
    {
        $farmerId = $attribute['farmer_id'];
        $staff = request()->user('sanctum');
        $farmer = FarmerDetails::find($farmerId);

        if (
            empty($attribute['products'])
            || empty($farmerId)
            || empty($farmer)
            || $farmer->staff->id !== $staff->staff->id
        ) {
            throw new \Exception('This Farmer Does Not Belong To You!');
        }

        $distribution = Distribution::create([
            'receipt_no' => now()->format('YmdHisv'),
            'agent_id' => $staff->staff->id,
            'farmer_id' => $farmerId,
            'distribution_date' => $attribute['distribution_date'],
            'total_amount' => 0,
        ]);

        // Create Distribution Detail
        $this->bulkCreateDistributionDetail($distribution, $attribute['products']);

        // Create Order in HeroMarket Service
        $this->createDistributionOrderInHeroMarket($distribution, $attribute['products']);

        return $distribution;
    }

    public function bulkCreateDistributionDetail(Distribution $distribution, array $products): void
    {
        $latestTransaction = Transaction::where('farmer_id', $distribution->farmer->id)->latest('id');
        $initBalance = $latestTransaction->exists() ? $latestTransaction->first()->balance_amount : 0;
        foreach ($products as $productData) {
            $subTotal = $productData['quantity'] * $productData['price_per_unit'];

            $qtyBalance = DistributionBalance::query()->where('product_id', $productData['product_id'])
                ->where('farmer_id', $distribution->farmer_id);

            DistributionDetail::create([
                'distribution_id' => $distribution->id,
                'product_id' => $productData['product_id'],
                'product_name' => $productData['product_name'],
                'category_id' => $productData['category_id'],
                'category_name' => $productData['category_name'],
                'quantity' => $productData['quantity'],
                'price_per_unit' => $productData['price_per_unit'],
                'sub_total' => $subTotal,
                'unit' => $productData['unit'],
                'available_stocks' => ($qtyBalance->exists() ? $qtyBalance->sum('quantity') : 0) + $productData['quantity'],
            ]);

            DistributionBalance::create([
                'product_id' => $productData['product_id'],
                'product_name' => $productData['product_name'],
                'farmer_id' => $distribution->farmer_id,
                'quantity' => $productData['quantity'],
            ]);

            Transaction::create([
                'distribution_id' => $distribution->id,
                'account_id' => $distribution->agent_id,
                'farmer_id' => $distribution->farmer_id,
                'transaction_type' => 'Input Distribution',
                'initial_balance' => $initBalance,
                'transaction_amount' => $subTotal,
                'balance_amount' => $initBalance + $subTotal,
            ]);

            $initBalance += $subTotal;
        }

        $total = $distribution->distributionDetails()->sum('sub_total');
        $faAccount = FaAccount::where('farmer_id', $distribution->farmer->id)->first();
        $faAccount->loan_amount += $total;
        $faAccount->outstanding_amount -= $total;
        $faAccount->save();

        $distribution->update([
            'total_amount' => $total
        ]);
    }

    public function createDistributionOrderInHeroMarket(Distribution $distribution, array $productData)
    {
        $distributionOrderApi = config('upstream.HEROMARKET_URL') . '/api/v2/order/distribution/create';

        $payload = [
            'upstream_farmer_id' => $distribution->farmer_id,
            'products' => array_map(function ($product) {
                return [
                    'id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price_per_unit'],
                    'stock_id' => $product['stock_id'],
                ];
            }, $productData)
        ];
        $response = Http::withOptions(['verify' => false])->post($distributionOrderApi, $payload);

        $result = json_decode($response->getBody(), true);
        if (empty($result['data'])) {
            throw new \Exception('The Farmer Not Found In Hero Market, Please Contact Admin!');
        }

        $distribution->heromarket_combined_order_id = $result['data']['id'];
        $distribution->save();
    }

    public function exportDistributionsToCsv(Builder $builder)
    {
        $file = fopen('php://output', 'w');
        fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Fix for Excel

        $builder->each(function (Distribution $distribution) use ($file) {
            fputcsv($file, [
                'Farmer Id',
                'Farmer Name',
                'Balance'
            ]);

            $farmer = $distribution->farmer;
            fputcsv($file, [
                $farmer->id,
                $farmer->full_name,
                $farmer->faAccount->outstanding_amount . ' - DB'
            ]);
            fputcsv($file, [
                'Transaction Date',
                'Receipt Number',
                'Initial Balance',
                'Transaction Amount',
                'Balance Amount',
                'Transaction Type',
            ]);
            $distribution->transactions()->each(function (Transaction $transaction) use ($file, $distribution) {
                fputcsv($file, [
                    $transaction->created_at,
                    $distribution->receipt_no,
                    ''
                ]);
            });
        });
        fclose($file);
        return $file;
    }
}