<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'acc_no',
        'acc_type',
        'typee',
        'acc_open_date',
        'status',
        'farmer_id',
        'staff_id',
        'cash_balance',
        'credit_balance',
        'balance',
        'dist_balance',
        'loan_acc_no',
        'loan_amount',
        'outstanding_amount',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerDetails::class, 'farmer_id');
    }

    public function setAccTypeAttribute($value)
    {
        $this->attributes['acc_type'] = $value;

        $prefix = ($value === 'FRA') ? '11' : (($value === 'FOA') ? '22' : '');
        $counter = FaAccount::where('acc_type', $value)->max('acc_no');
        $counterWithoutPrefix = (int) substr($counter, strlen($prefix));
        $nextCounter = $counterWithoutPrefix + 1;
        $number = $prefix . str_pad($nextCounter, 10, '0', STR_PAD_LEFT);
        $this->attributes['acc_no'] = $number;
        $this->attributes['loan_acc_no'] = $number;

    }
}
