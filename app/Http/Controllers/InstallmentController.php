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
            'payment_date' => ['required', 'date'],
        ]);
        $paymentAmount = $request->input('payment_amount');
        $payment_date = $request->input('payment_date');

        // Update installment
        $installment->remaining_balance -= $paymentAmount;
        $installment->paid_date = $payment_date;

        if ($installment->remaining_balance <= 0) {
            $installment->remaining_balance = 0;
            $installment->status = 'fully_paid';
        } else {
            $installment->status = 'partially_paid';
        }

        $installment->save();

        // Update loan
        $loan = $installment->loan;
        $loan->outstanding_balance -= $paymentAmount;
        $loan->returned_money += $paymentAmount;
        $loan->remaining_months = ceil($loan->outstanding_balance / $loan->monthly_installment);
        $loan->save();

        // Log the payment
        $installment->payments()->create([
            'payment_amount' => $paymentAmount,
            'payment_date' => $payment_date,
        ]);

        // 🔥 Force Livewire to refresh
        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function show($loan_id)
    {
        $installments = Installment::where('loan_id', $loan_id)->get();
        $returned_mony = Loan::where('loan_id', $loan_id);
        $loan = Loan::find($loan_id);
        return view('installment.installments', ['installments' => $installments, 'loan' => $loan]);
    }

    public function edit(Installment $installment)
    {
        $paymentAmount = $installment->amount - $installment->remaining_balance ?? 0;
        return view('installment.edit', compact('installment', 'paymentAmount'));
    }

    public function update(Request $request, Installment $installment)
    {
        $validated = $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:0', 'max:' . $installment->amount],
            'paid_date' => ['required', 'date'],
        ]);

        $newPaymentAmount = $validated['payment_amount']; // New payment amount
        $oldPaidAmount = $installment->amount - $installment->remaining_balance; // How much was paid before
        $balanceDifference = $newPaymentAmount - $oldPaidAmount; // Difference between old and new payment

        // Update installment balance
        $installment->remaining_balance = max(0, $installment->amount - $newPaymentAmount); // Ensure no negative balance
        $installment->paid_date = $validated['paid_date'];

        // Recalculate status
        if ($installment->remaining_balance == $installment->amount) {
            $installment->status = 'pending';
            $installment->paid_date = null; // Reset paid date if unpaid
        } elseif ($installment->remaining_balance == 0) {
            $installment->status = 'fully_paid';
        } else {
            $installment->status = 'partially_paid';
        }

        $installment->save();

        // 🔥 Fix Incorrect Loan Balances
        $loan = $installment->loan;

        // Correctly adjust outstanding balance and returned money
        $loan->outstanding_balance -= $balanceDifference; // If new payment is more, decrease; if less, increase
        $loan->returned_money += $balanceDifference; // Increase if user paid more, decrease if less

        // Ensure outstanding balance never goes negative
        if ($loan->outstanding_balance < 0) {
            $loan->outstanding_balance = 0;
        }

        // Ensure returned money never exceeds total loan amount
        if ($loan->returned_money > $loan->loan_amount) {
            $loan->returned_money = $loan->loan_amount;
        }

        // Update remaining months based on new balance
        $loan->remaining_months = $loan->outstanding_balance > 0
            ? ceil($loan->outstanding_balance / $loan->monthly_installment)
            : 0;

        $loan->save();

        return redirect(route('installments', $loan->id))->with('success', 'Installment updated successfully!');
    }
}
