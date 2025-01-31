<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'customer_id',
        'item_name',
        'loan_amount',
        'down_payment',
        'monthly_installment',
        'remaining_months',
        'outstanding_balance',
        'buying_date',
        'status',
    ];

    public static function createInstallments(Loan $loan)
    {
        $remaining_balance = $loan->outstanding_balance;
        $monthly_amount = $loan->monthly_installment;
        $remaining_months = ceil($loan->outstanding_balance / $loan->monthly_installment); // Calculate required months

        $due_date = (clone $loan->created_at)->addMonth(); // Clone to avoid modifying original timestamp

        for ($i = 0; $i < $remaining_months; $i++) {
            $installment_amount = min($remaining_balance, $monthly_amount); // Ensure last installment is correct

            $loan->installments()->create([
                'amount' => $installment_amount,
                'remaining_balance' => $installment_amount, // Initially full amount due
                'status' => 'pending',
                'due_date' => $due_date,
            ]);

            $remaining_balance -= $installment_amount;
            $due_date = $due_date->addMonth(); // Move to the next month
        }
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
