<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('item_name')
                ->label('ناوی کاڵا') // Item Name
                ->disabled(),
            TextInput::make('loan_amount')
                ->label('کۆی قەرز') // Loan Amount
                ->disabled(),
            TextInput::make('down_payment')
                ->label('پارەی دەستپێک') // Down Payment
                ->disabled(),
            TextInput::make('monthly_installment')
                ->label('قەرزی مانگانە') // Monthly Installment
                ->disabled(),
            DatePicker::make('buying_date')
                ->label('بەرواری کڕین') // Buying Date
                ->disabled(),
            Select::make('status')
                ->label('دۆخی قەرز') // Loan Status
                ->options([
                    'active' => 'چالاک',
                    'completed' => 'تەواو',
                    'overdue' => 'داواکراو',
                ])
                ->disabled(),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
