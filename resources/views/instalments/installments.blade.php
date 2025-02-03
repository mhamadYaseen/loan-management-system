@extends('layouts.app')

@section('content')
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
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $installment->status === 'fully_paid' ? 'bg-green-600 text-white' : 
                               ($installment->status === 'partially_paid' ? 'bg-yellow-600 text-white' : 'bg-red-600 text-white') }}">
                            {{ __('pending' == $installment->status ? 'پارەکە نەدراوە' : 
                                ('partially_paid' == $installment->status ? 'بەشێکی پارەکە دراوە' : 'پارەکە دراوە')) }}
                        </span>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if ($installment->status !== 'fully_paid')
                            <form action="{{ route('installments.pay', $installment) }}" method="POST">
                                @csrf
                                <input type="hidden" name="installment_id" value="{{ $installment->id }}">
                                <input type="number" name="payment_amount" step="0.01"
                                       max="{{ $installment->remaining_balance }}" required
                                       class="w-20 border rounded p-1 text-right">
                                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                                    {{ __('Pay') }}
                                </button>
                            </form>
                        @else
                            <span class="text-green-500">✔ Paid</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
