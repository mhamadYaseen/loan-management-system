@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">گۆڕینی قیست</h2>

    @if (session('success'))
        <div class="bg-green-500 text-white p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('installments.update', $installment->id) }}" method="POST">
      @csrf
      @method('PUT')    

        <!-- Amount -->
        <div class="mb-4">
            <label for="payment_amount" class="block text-gray-700 dark:text-gray-200 font-medium">قیستی دراو</label>
            <input type="number" name="payment_amount" 
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100"
                     value="{{ $paymentAmount }}"
                   step="0.01"max="{{$installment->amount}}" required>
        </div>

        <!-- Due Date -->
        <div class="mb-4">
            <label for="paid_date" class="block text-gray-700 dark:text-gray-200 font-medium">بەرواری قیست</label>
            <input type="date" name="paid_date" value="{{ $installment->paid_date }}"
                   class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100">
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
            گۆڕین
        </button>
    </form>
</div>
@endsection
