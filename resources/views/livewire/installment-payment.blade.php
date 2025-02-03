<div>
    {{-- In work, do what you enjoy. --}}
    <div class="p-6">
        <h2 class="text-lg font-bold mb-4">قیستەکان بۆ: {{ $loan->customer->name }}</h2>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-700 text-white">
                    <th class="border border-gray-300 px-4 py-2">کۆی قەرزی مانگانە</th>
                    <th class="border border-gray-300 px-4 py-2">ماوەی قەرز</th>
                    <th class="border border-gray-300 px-4 py-2">بەرواری مانگ</th>
                    <th class="border border-gray-300 px-4 py-2">دۆخی پارەدان</th>
                    <th class="border border-gray-300 px-4 py-2">پارە دانان</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($installments as $installment)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">${{ $installment->amount }}</td>
                        <td class="border border-gray-300 px-4 py-2">${{ $installment->remaining_balance }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $installment->due_date }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <span
                                class="px-2 py-1 text-xs rounded-full 
                                {{ $installment->status === 'fully_paid'
                                    ? 'bg-green-600 text-white'
                                    : ($installment->status === 'partially_paid'
                                        ? 'bg-yellow-600 text-white'
                                        : 'bg-red-600 text-white') }}">
                                {{ __(
                                    'pending' == $installment->status
                                        ? 'پارەکە نەدراوە'
                                        : ('partially_paid' == $installment->status
                                            ? 'بەشێکی پارەکە دراوە'
                                            : 'پارەکە دراوە'),
                                ) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            @if ($installment->status !== 'fully_paid')
                            <div>
                                <x-filament::modal>
                                    <x-slot name="trigger">
                                        <x-filament::button>
                                            {{ __('Pay Installment') }}
                                        </x-filament::button>
                                    </x-slot>
                            
                                    <x-slot name="heading">
                                        <h2 class="text-lg font-bold mb-4">Pay Installment #{{ $installment?->id }}</h2>
                                    </x-slot>
                            
                                    <x-slot name="description">
                                        <div>
                                            <input type="number" 
                                                   wire:model.defer="paymentAmount" 
                                                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                   min="1"
                                            >
                            
                                            <x-filament::button wire:click="pay" class="ml-4">
                                                {{ __('Pay') }}
                                            </x-filament::button>
                                        </div>
                                    </x-slot>
                                </x-filament::modal>
                            </div>
                            @else
                                <span class="text-green-500">✔ Paid</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
