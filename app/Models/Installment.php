<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    public function addPayment($paymentAmount)
    {
        $this->remaining_balance -= $paymentAmount;

        if ($this->remaining_balance <= 0) {
            $this->remaining_balance = 0;
            $this->status = 'fully_paid';
        } elseif ($this->remaining_balance > 0) {
            $this->status = 'partially_paid';
        }

        $this->save();
    }
    protected $fillable = ['loan_id', 'amount', 'paid_date','due_date' ,'remaining_balance'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
