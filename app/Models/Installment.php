<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $fillable = ['loan_id', 'amount', 'paid_date', 'remaining_balance'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
