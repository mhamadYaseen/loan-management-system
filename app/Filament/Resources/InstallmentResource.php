<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstallmentResource\Pages;
use App\Filament\Resources\InstallmentResource\RelationManagers;
use App\Models\Installment;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
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
                TextColumn::make('loan.item_name')
                    ->label('ناوی کاڵا') // Item Name
                ,
                TextColumn::make('loan.customer.name')
                    ->label('ناوی کڕیار') // Customer Name
                ,
                TextColumn::make('amount')
                    ->label('قیستە') // Amount
                ,
                TextColumn::make('remaining_balance')
                    ->label('باقی') // Remaining
                ,
                TextColumn::make('status')
                    ->label('دۆخ') // Status
                ,
                TextColumn::make('due_date')
                    ->label('بەرواری داواکردن') // Due Date
                ,
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('pay')
                    ->label('پارەدان') // Pay
                    ->action(function (Installment $record, array $data): void {
                        $record->pay($data['amount'], $record);
                        Notification::make()
                            ->title('پارەدانی پاشەکەوتکرا')
                            ->body('پارەکە بەسەرکەوتویی پارەدرا')
                            ->send();
                    })
                    ->form([
                        TextInput::make('amount')
                            ->label('بڕی پارە') // Amount
                            ->numeric()
                            ->required(),
                    ])
                    ->modalHeading('پارەدان') // Payment
                    ->modalButton('پارەدان') // Pay
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getTableRecordUrl(Installment $record): string
    {
        return route('admin.loans.show', $record->loan_id); // This will show installments for the loan

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
