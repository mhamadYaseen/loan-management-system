<?php
namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Ensure that customer, guarantor, and loan details are pre-filled
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $customer = $this->record;

        return array_merge($data, [
            'guarantor' => $customer->guarantor ? $customer->guarantor->toArray() : [], // Populate guarantor data
            'loan' => $customer->loans->first() ? $customer->loans->first()->toArray() : [], // Populate first loan data
        ]);
    }
}
