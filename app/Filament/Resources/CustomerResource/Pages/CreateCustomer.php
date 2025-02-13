<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\Loan;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
    protected function handleRecordCreation(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            // Create the customer
            $customer = Customer::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'payment_type' => $data['payment_type'],
            ]);
            if ($data['payment_type'] === 'with_salary') {
                $customer->salary_type = $data['salary_type'];
            }
            // Create the guarantor
            $customer->guarantor()->create([
                'name' => $data['guarantor']['name'],
                'phone' => $data['guarantor']['phone'],
                'address' => $data['guarantor']['address'],
            ]);

            // Create the loan
            $customer->loans()->create([
                'item_name' => $data['loan']['item_name'],
                'loan_amount' => $data['loan']['loan_amount'],
                'down_payment' => $data['loan']['down_payment'],
                'monthly_installment' => $data['loan']['monthly_installment'],
                'remaining_months' => ceil(($data['loan']['loan_amount'] - $data['loan']['down_payment']) / $data['loan']['monthly_installment']),
                'outstanding_balance' => $data['loan']['loan_amount'] - $data['loan']['down_payment'],
                'buying_date' => $data['loan']['buying_date'],
                'status' => 'active',
                'currency' => $data['loan']['currency'],
            ]);
            Loan::createInstallments($customer->loans->first());
            return $customer;
        });
    }

    protected function getRedirectUrl(): string
    {
        return '/admin/customers';
    }
}
