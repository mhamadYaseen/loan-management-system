<div wire:ignore.self class="p-6">
    <div class="p-6 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th
                        class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Due Date') }}
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Amount') }}
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Remaining') }}
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Status') }}
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($loan->installments as $installment)
                    <tr class=" dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-300">
                            {{ $installment->due_date }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-300">
                            ${{ number_format($installment->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-300">
                            ${{ number_format($installment->remaining_balance, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span
                                class="px-2 py-1 text-xs rounded-full 
                                {{ $installment->status === 'fully_paid'
                                    ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100'
                                    : ($installment->status === 'partially_paid'
                                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100'
                                        : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100') }}">
                                {{ $installment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            @if($installment->status !== 'fully_paid')
                                <form action="{{ route('installments.pay', $installment) }}" 
                                      method="POST" 
                                      id="payment-form-{{ $installment->id }}"
                                      class="flex items-center space-x-2">
                                    @csrf
                                    <input type="number" 
                                           name="payment_amount"
                                           value="{{ $installment->remaining_balance }}"
                                           max="{{ $installment->remaining_balance }}"
                                           min="0.01" 
                                           step="0.01"
                                           class="w-24 rounded-md border-gray-300 dark:border-gray-600 
                                                  bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                                  focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                                  focus:border-indigo-500 dark:focus:border-indigo-400
                                                  shadow-sm text-sm"
                                           required
                                    >
                                    <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white 
                                                   px-3 py-1 rounded-md text-sm transition-colors duration-200">
                                        {{ __('Pay') }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@if (session('livewire_refresh'))
    <script>
        Livewire.emit('refreshInstallmentTable');
    </script>
@endif
