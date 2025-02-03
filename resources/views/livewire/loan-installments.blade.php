<div>
   <x-filament::button wire:click="$emit('openInstallmentModal', {{ $getRecord()->id }})">
       {{ __('View Installments') }}
   </x-filament::button>

   <livewire:installment-list />
</div>
