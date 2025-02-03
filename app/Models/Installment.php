<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Installment extends Model
{
    public function pay(float $paymentAmount, Installment $installment)
    {

        if($installment->remaining_balance < $paymentAmount){
            return redirect()->back()->with('error', 'Payment amount exceeds remaining balance');
        }
        $installment->remaining_balance -= $paymentAmount;

        if ($installment->remaining_balance <= 0) {
            $installment->remaining_balance = 0;
            $installment->status = 'fully_paid';
        } else {
            $installment->status = 'partially_paid';
        }

        $installment->save();

        // Update loan
        $loan = Loan::find($installment->loan_id);
        $loan->outstanding_balance -= $paymentAmount;
        $loan->remaining_months = ceil($loan->outstanding_balance / $loan->monthly_installment);
        $loan->save();

        // Log the payment
        $installment->payments()->create([
            'payment_amount' => $paymentAmount,
            'payment_date' => now(),
        ]);

        // 🔥 Force Livewire to refresh
        return redirect()->back()->with('livewire_refresh', true);
    }
    protected $fillable = ['loan_id', 'amount', 'paid_date', 'due_date', 'remaining_balance'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
