<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function pay(Request $request, Installment $installment)
    {
        $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:1', 'max:' . $installment->remaining_balance],
        ]);

        $paymentAmount = $request->input('payment_amount');

        // Update installment
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
}
