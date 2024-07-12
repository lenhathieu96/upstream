<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'farmer_id',
        'loan_taken_last_year',
        'loan_taken_from',
        'loan_amount',
        'purpose',
        'loan_interest',
        'interest_period',
        'security',
        'loan_repayment_amount',
        'loan_repayment_date',
    ];
}
