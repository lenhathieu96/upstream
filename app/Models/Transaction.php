<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distribution_id',
        'account_id',
        'farmer_id',
        'transaction_type',
        'transaction_amount',
        'balance_amount',
        'initial_balance',
    ];

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type', 'code');
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerDetail::class, 'farmer_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'account_id');
    }
}
