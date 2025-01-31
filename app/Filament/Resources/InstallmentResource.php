<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstallmentResource\Pages;
use App\Filament\Resources\InstallmentResource\RelationManagers;
use App\Models\Installment;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstallmentResource extends Resource
{
    protected static ?string $model = Installment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('loan.customer.name')
                    ->label('ناوی کڕیار') // Customer Name
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('کۆی قەرزی مانگانە') // Installment Amount
                    ->money('USD'),
                TextColumn::make('remaining_balance')
                    ->label('قەرزی ماوە') // Remaining Balance
                    ->money('USD'),
                TextColumn::make('status')
                    ->label('دۆخی پارەدان') // Payment Status
                    ->badge()
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'partially_paid',
                        'danger' => 'pending',
                    ]),
                TextColumn::make('paid_date')
                    ->label('بەرواری مانگ') // Due Date
                    ->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('addPayment')
                    ->label('پارە دانان') // "Add Payment"
                    ->action(function (Installment $record, array $data) {
                        $paymentAmount = $data['payment_amount'];

                        // Add payment and update installment
                        $record->addPayment($paymentAmount);

                        // Create payment record
                        $record->payments()->create([
                            'installment_id' => $record->id,
                            'payment_amount' => $paymentAmount,
                            'payment_date' => now(),
                        ]);

                        Notification::make()
                            ->title('Payment Added Successfully!')
                            ->success()
                            ->send();
                    })
                    ->form([
                        TextInput::make('payment_amount')
                            ->label('بڕی پارە') // Payment Amount
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(fn($record) => $record->remaining_balance),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstallments::route('/'),
            'create' => Pages\CreateInstallment::route('/create'),
            'edit' => Pages\EditInstallment::route('/{record}/edit'),
        ];
    }
}
