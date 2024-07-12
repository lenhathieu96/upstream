<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'distribution_id' => $this->distribution_id,
            'account_id' => $this->account_id,
            'farmer_id' => $this->farmer_id,
            'transaction_type' => $this->transaction_type,
            'initial_balance' => $this->initial_balance,
            'transaction_amount' => $this->transaction_amount,
            'balance_amount' => $this->balance_amount,
            'created_at' => $this->created_at,
        ];
    }
}
