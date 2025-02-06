@extends('layouts.app')

@section('content')
    <div class="p-6 dark:bg-zinc-900 mx-auto max-w-6xl">
        <h2 class="text-xl sm:text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100 text-center">
            قیستەکان بۆ: {{ $loan->customer->name }}
        </h2>
        <div>
            <div class="flex flex-col sm:flex-row items-center justify-between mb-4 space-y-4 sm:space-y-0">
                <div class="flex items-center gap-2 w-full sm:w-auto text-sm sm:text-base">
                    <span class="text-gray-900 dark:text-gray-100 font-semibold">قەرزی نەدراو:</span>
                    <span class="text-gray-900 dark:text-gray-100 font-semibold">{{$loan->currency}} {{ number_format($loan->outstanding_balance, 2) }}</span>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto text-sm sm:text-base">
                    <span class="text-gray-900 dark:text-gray-100 font-semibold">بڕی پارەی دراو:</span>
                    <span class="text-gray-900 dark:text-gray-100 font-semibold">{{$loan->currency}} {{ number_format($loan->returned_money, 2) }}</span>
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                <table class="w-full min-w-[600px] border-collapse bg-white dark:bg-gray-800 rounded-lg">
                    <thead class="border-b border-gray-100 dark:border-gray-700">
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-xs sm:text-sm font-semibold">
                            <th class="px-2 sm:px-4 py-3 text-right">کۆی قەرزی مانگانە</th>
                            <th class="px-2 sm:px-4 py-3 text-right">ماوەی قەرز</th>
                            <th class="px-2 sm:px-4 py-3 text-right">بەرواری مانگ</th>
                            <th class="px-2 sm:px-4 py-3 text-right">دۆخی پارەدان</th>
                            <th class="px-2 sm:px-4 py-3 text-center">پارە دانان</th>
                            <th class="px-2 sm:px-4 py-3 text-center">گۆڕین</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($installments as $installment)
                            <tr class="bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-2 sm:px-4 py-3 text-gray-900 dark:text-gray-100 font-medium text-right text-xs sm:text-sm">
                                    ${{ number_format($installment->amount, 2) }}
                                </td>
                                <td class="px-2 sm:px-4 py-3 text-gray-900 dark:text-gray-100 font-medium text-right text-xs sm:text-sm">
                                    ${{ number_format($installment->remaining_balance, 2) }}
                                </td>
                                <td class="px-2 sm:px-4 py-3 text-gray-900 dark:text-gray-100 font-medium text-right text-xs sm:text-sm">
                                    {{ $installment->due_date ? $installment->due_date : 'نادیار' }}
                                </td>
                                <td class="px-2 sm:px-4 py-3 text-right text-xs sm:text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-md text-white
                                        {{ $installment->status === 'fully_paid' ? 'bg-green-500' :
                                        ($installment->status === 'partially_paid' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                        {{ $installment->status === 'pending' ? 'پارەکە نەدراوە' :
                                        ($installment->status === 'partially_paid' ? 'بەشێکی پارەکە دراوە' : 'پارەکە دراوە') }}
                                    </span>
                                </td>
                                <td class="px-2 sm:px-4 py-3 text-right text-xs sm:text-sm">
                                    @if ($installment->status !== 'fully_paid')
                                        <form action="{{ route('installments.pay', $installment) }}" method="POST"
                                            class="flex flex-col sm:flex-row items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="installment_id" value="{{ $installment->id }}">
                                            <input type="number" name="payment_amount" step="0.01"
                                                max="{{ $installment->remaining_balance }}" required
                                                class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none w-full sm:w-20 px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-md
                                                  bg-zinc-900 dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                                  focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm"
                                                  placeholder="بڕی پارەکە">
                                            <input type="date" name="payment_date" required value="{{ now()->toDateString() }}"
                                                class="w-full sm:w-32 px-1 py-2 border border-gray-300 dark:border-gray-600 rounded-md
                                                  bg-zinc-900 dark:bg-gray-700 text-gray-900 dark:text-gray-100 
                                                  focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 shadow-sm">
                                            <button type="submit"
                                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-xs sm:text-sm font-semibold transition-all">
                                                پارەدان
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-2 sm:px-4 py-3 text-center">
                                    @if ($installment->status !== 'pending')
                                        <a href="{{ route('installments.edit', $installment->id) }}"
                                            class="inline-flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-300 ease-in-out transform hover:-translate-y-0.5">
                                            <span>✏️</span>
                                            <span>گۆڕین</span>
                                        </a>

                                        <div class="mt-2 text-xs sm:text-sm {{ $installment->status === 'fully_paid' 
                                            ? 'text-green-500 dark:text-green-400' 
                                            : 'text-yellow-500 dark:text-yellow-400' }} 
                                            flex items-center gap-2 justify-center font-medium">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span>
                                                {{ $installment->status === 'fully_paid' ? 'پارەکە دراوە' : 'بەشێک لە پارەکە دراوە' }}
                                                لە {{ $installment->paid_date }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection
