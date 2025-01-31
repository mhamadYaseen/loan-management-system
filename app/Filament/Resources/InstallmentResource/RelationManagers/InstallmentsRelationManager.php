<?php

namespace App\Filament\Resources\LoanResource\RelationManagers;

use App\Models\Installment;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'installments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->label('کۆی قەرزی مانگانە') // Installment Amount
                    ->money('USD'),
                TextColumn::make('remaining_balance')
                    ->label('ماوەی قەرز') // Remaining Balance
                    ->money('USD'),
                TextColumn::make('status')
                    ->label('دۆخی پارەدان') // Payment Status
                    ->badge()
                    ->colors([
                        'success' => 'paid',
                        'warning' => 'partially_paid',
                        'danger' => 'pending',
                    ]),
                TextColumn::make('due_date')
                    ->label('بەرواری مانگ') // Due Date
                    ->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('addPayment')
                    ->label('پارە دانان') // "Add Payment"
                    ->icon('heroicon-o-cash')
                    ->action(function (Installment $record, array $data) {
                        $paymentAmount = $data['payment_amount'];

                        // Add payment and update installment
                        $record->addPayment($paymentAmount);

                        // Create payment record
                        $record->payments()->create([
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
            ]);
    }
}
