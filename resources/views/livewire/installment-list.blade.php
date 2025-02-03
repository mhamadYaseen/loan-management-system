<div>
    <x-filament::modal>
        <x-slot name="trigger">
            <x-filament::button>
                {{ __('View Installments') }}
            </x-filament::button>
        </x-slot>

        <x-slot name="heading">
            <h2 class="text-lg font-bold mb-4">Installments for Loan #{{ $loanId }}</h2>
        </x-slot>

        <x-slot name="description">
            <table class="w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2">Installment ID</th>
                        <th class="p-2">Amount</th>
                        <th class="p-2">Remaining Balance</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($installments as $installment)
                        <tr>
                            <td class="p-2 text-center">{{ $installment->id }}</td>
                            <td class="p-2 text-center">${{ number_format($installment->amount, 2) }}</td>
                            <td class="p-2 text-center">${{ number_format($installment->remaining_balance, 2) }}</td>
                            <td class="p-2 text-center">
                                <span class="px-2 py-1 text-xs font-bold {{ $installment->status === 'paid' ? 'bg-green-200' : 'bg-yellow-200' }}">
                                    {{ ucfirst($installment->status) }}
                                </span>
                            </td>
                            <td class="p-2 text-center">
                                @if ($installment->status !== 'fully_paid')
                                    <x-filament::button wire:click="$emit('openPaymentModal', {{ $installment->id }})">
                                        {{ __('Pay') }}
                                    </x-filament::button>
                                @else
                                    <span class="text-green-500">✔ Paid</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-slot>
    </x-filament::modal>

    <livewire:installment-payment />
</div>
