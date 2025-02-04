@extends('layouts.app')

@section('content')
<div class="p-6 dark:bg-zinc-900 mx-auto max-w-6xl">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center">
        قیستەکان بۆ: {{ $loan->customer->name }}
    </h2>
    <div>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-gray-900 dark:text-gray-100 font-semibold">قەرزی نەدراو:</span>
                <span class="text-gray-900 dark:text-gray-100 font-semibold">${{ number_format($loan->outstanding_balance, 2) }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-900 dark:text-gray-100 font-semibold">بڕی پارەی دراو: </span>
                <span class="text-gray-900 dark:text-gray-100 font-semibold">${{ $loan->returned_money }}</span>
            </div>
    </div>
    

    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
        <table class="w-full border-collapse bg-white dark:bg-gray-800 rounded-lg"style="background-color:#18181b;">
            <thead class="devided border-b border-gray-100 dark:border-gray-700">
                <tr class="-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm font-semibold">
                    <th class="px-4 py-3 text-right">کۆی قەرزی مانگانە</th>
                    <th class="px-4 py-3 text-right">ماوەی قەرز</th>
                    <th class="px-4 py-3 text-right">بەرواری مانگ</th>
                    <th class="px-4 py-3 text-right">دۆخی پارەدان</th>
                    <th class="px-4 py-3 text-center">پارە دانان</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" style="background-color:#2c2c2d;">
                @foreach ($installments as $installment)
                    <tr class="bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium text-right">
                            ${{ number_format($installment->amount, 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium text-right">
                            ${{ number_format($installment->remaining_balance, 2) }}
                        </td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-medium text-right">
                            {{ $installment->due_date ? $installment->due_date: 'نادیار' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-md text-white 
                                {{ 
                                    $installment->status === 'fully_paid' ? 'bg-green-500' : 
                                    ($installment->status === 'partially_paid' ? 'bg-yellow-500' : 'bg-red-500') 
                                }}">
                                {{ __('pending' == $installment->status ? 'پارەکە نەدراوە' : 
                                    ('partially_paid' == $installment->status ? 'بەشێکی پارەکە دراوە' : 'پارەکە دراوە')) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ($installment->status !== 'fully_paid')
                                <form action="{{ route('installments.pay', $installment) }}" method="POST" class="flex items-right gap-2 justify-center">
                                    @csrf
                                    <input type="hidden" name="installment_id" value="{{ $installment->id }}">
                                    <input type="number" 
                                           name="payment_amount" 
                                           step="0.01"
                                           max="{{ $installment->remaining_balance }}" 
                                           required
                                           class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                                                  bg-zinc-900 dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                                  focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                    <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-semibold transition-all">
                                        {{ __('Pay') }}
                                    </button>
                                </form>
                            @else
                                <span class="text-green-500 dark:text-green-400 flex items-center gap-1 justify-center font-semibold">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    پارەکە دراوە
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
