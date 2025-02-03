<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('item_name')
                ->label('ناوی کاڵا') // Item Name
            ,
            TextInput::make('loan_amount')
                ->label('کۆی قەرز') // Loan Amount
            ,
            TextInput::make('down_payment')
                ->label('پارەی دەستپێک') // Down Payment
            ,
            TextInput::make('monthly_installment')
                ->label('قەرزی مانگانە') // Monthly Installment
            ,
            DatePicker::make('buying_date')
                ->label('بەرواری کڕین') // Buying Date

            ,
            Select::make('status')
                ->label('دۆخی قەرز') // Loan Status
                ->options([
                    'active' => 'چالاک',
                    'completed' => 'تەواو',
                    'overdue' => 'داواکراو',
                ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('ناوی کڕیار') // Customer Name
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_name')
                    ->label('ناوی کاڵا') // Item Name
                    ->searchable(),
                Tables\Columns\TextColumn::make('loan_amount')
                    ->label('کۆی قەرز') // Loan Amount
                    ->money('USD'),
                Tables\Columns\TextColumn::make('down_payment')
                    ->label('پارەی دەستپێک') // Down Payment
                    ->money('USD'),
                Tables\Columns\TextColumn::make('monthly_installment')
                    ->label('قەرزی مانگانە') // Monthly Installment
                    ->money('USD'),
                Tables\Columns\TextColumn::make('remaining_months')
                    ->label('باقی مانگەکان') // Remaining Months
                    ->searchable(),
                Tables\Columns\TextColumn::make('outstanding_balance')
                    ->label('قەرزی نەدراو') // Outstanding Balance
                    ->money('USD'),
                Tables\Columns\TextColumn::make('buying_date')
                    ->label('بەرواری کڕین') // Buying Date
                    ->date('Y/m/d'),
                Tables\Columns\TextColumn::make('status')
                    ->label('دۆخی قەرز') // Loan Status
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'completed',
                        'danger' => 'overdue',
                    ]),
                    
            
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('installments')
                    ->label('قیستەکان')
                    ->url(fn (Loan $record): string => route('installments', ['loan' => $record->id]))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
