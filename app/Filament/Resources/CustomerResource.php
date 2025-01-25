<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $modelLabel = 'کڕیار';
    protected static ?string $pluralModelLabel = 'کڕیارەکان';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('ناوی کڕیار')
                    ->required(),
                TextInput::make('phone')
                    ->label('ژ.مۆبایل')
                    ->required(),
                TextInput::make('address')
                    ->label('ناونیشان')
                    ->required(),
                TextInput::make('guarantor_name')
                    ->label('ناوی کەفیل')
                    ->required(),
                TextInput::make('guarantor_phone')
                    ->label('ژ.مۆبایلی کەفیل')
                    ->required(),
                TextInput::make('guarantor_address')
                    ->label('ناونیشانی کەفیل')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('ناو')->sortable()->searchable(),
                TextColumn::make('phone')->label('ژ.مۆبایل')->sortable()->searchable(),
                TextColumn::make('address')->label('ناونیشان')->sortable()->searchable(),
                TextColumn::make('guarantor_name')->label('ناوی کەفیل')->sortable()->searchable(),
                TextColumn::make('guarantor_phone')->label('ژ.مۆبایلی کەفیل')->sortable()->searchable(),
                TextColumn::make('guarantor_address')->label('ناونیشانی کەفیل')->sortable()->searchable(),
                TextColumn::make('created_at')->label('بەروار')
                ->dateTime('d-m-Y'),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
