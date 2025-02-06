<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\HasManyRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Route;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $modelLabel = 'کڕیار';
    protected static ?string $pluralModelLabel = 'کڕیارەکان';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                TextInput::make('name')->label('ناوی کڕیار')->required(),
                TextInput::make('phone')->label('ژ.مۆبایل')->required(),
                TextInput::make('address')->label('ناونیشان')->required(),
                Select::make('payment_type')
                    ->label('جۆری پارەدان')
                    ->options([
                        'monthly' => 'مانگانە',
                        'with_salary' => 'بە معاش',
                    ])
                    ->required()
                    ->live(), // Add live updates
                TextInput::make('salary_type')
                    ->label('جۆری مەعاش')
                    ->visible(fn(Forms\Get $get): bool => $get('payment_type') === 'with_salary')
                    ->required(fn(Forms\Get $get): bool => $get('payment_type') === 'with_salary'),
            ])->label('زانیاری کڕیار')
                ->columns(4),

            Card::make([
                TextInput::make('guarantor.name')->label('ناوی کەفیل')->required(),
                TextInput::make('guarantor.phone')->label('ژ.مۆبایلی کەفیل')->required(),
                TextInput::make('guarantor.address')->label('ناونیشانی کەفیل')->required(),
            ])->label('زانیاری کەفیل')
                ->columns(3),

            Card::make([
                TextInput::make('loan.item_name')->label('ناوی کاڵا')->required(),
                Select::make('loan.currency')
                ->label('جۆری پارە')
                ->options([
                    'USD' => 'دۆلاری ئەمریکا ($)',
                    'IQD' => 'دیناری عێراق (IQD)',
                ]),
                TextInput::make('loan.loan_amount')->label('کۆی قەرز')->numeric()->required(),
                TextInput::make('loan.down_payment')->label('پارەی دەستپێک')->numeric()->default(0),
                TextInput::make('loan.monthly_installment')->label('قەرزی مانگانە')->numeric()->required(),
                DatePicker::make('loan.buying_date')->label('بەرواری کڕین')->required(),
            ])->label('زانیاری قەرز')
                ->columns(3),
        ]);
    }

    public static function afterCreate(Customer $customer, array $data): void
    {
        // Create Guarantor
        $customer->guarantor()->create([
            'name' => $data['guarantor']['name'],
            'phone' => $data['guarantor']['phone'],
            'address' => $data['guarantor']['address'],
        ]);

        // Create Loan
        $customer->loans()->create([
            'item_name' => $data['loan']['item_name'],
            'loan_amount' => $data['loan']['loan_amount'],
            'down_payment' => $data['loan']['down_payment'],
            'monthly_installment' => $data['loan']['monthly_installment'],
            'outstanding_balance' => $data['loan']['loan_amount'] - $data['loan']['down_payment'],
            'buying_date' => $data['loan']['buying_date'],
            'status' => 'active',
            'currency' => $data['loan']['currency'],
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('ناوی کڕیار') // Customer Name
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('ژ.مۆبایل') // Customer Phone
                    ->searchable(),
                TextColumn::make('address')
                    ->label('ناونیشان') // Customer Address
                    ->searchable(),
                TextColumn::make('guarantor.name')
                    ->label('ناوی کەفیل') // Guarantor Name
                    ->searchable(),
                TextColumn::make('guarantor.phone')
                    ->label('ژ.مۆبایلی کەفیل') // Guarantor Phone
                    ->searchable(),
                TextColumn::make('guarantor.address')
                    ->label('ناونیشانی کەفیل') // Guarantor Address
                    ->searchable(),
                TextColumn::make('loans.buying_date')
                    ->label('بەرواری کرین') // Due Date
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('viewLoan')
                    ->label('بینینی قەرز') // "View Loan"
                    ->modalHeading('زانیاری قەرز') // "Loan Information"
                    ->modalButton('داخستن') // Close Button
                    ->modalWidth('xl') // Optional: Set modal size
                    ->form(function (Customer $record) {
                        $loan = $record->loans->first(); // Assuming one loan per customer
                        if (!$loan) {
                            return [
                                TextInput::make('message')
                                    ->default('No loan available for this customer.')
                                    ->disabled(),
                            ];
                        }

                        return [
                            Grid::make(2) // Creates a 2-column grid
                                ->schema([
                                    TextInput::make('item_name')
                                        ->label('ناوی کاڵا') // Item Name
                                        ->default($loan->item_name)
                                        ->disabled()
                                        ->columnSpan(1) // Makes it span 1 column in the grid
                                    , // Maximum length
                                    TextInput::make('loan_amount')
                                        ->label('کۆی قەرز') // Loan Amount
                                        ->default($loan->loan_amount)
                                        ->disabled()
                                        ->columnSpan(1), // Maximum length
                                    TextInput::make('down_payment')
                                        ->label('پارەی دەستپێک') // Down Payment
                                        ->default($loan->down_payment)
                                        ->disabled()
                                        ->columnSpan(1),
                                    TextInput::make('monthly_installment')
                                        ->label('قەرزی مانگانە') // Monthly Installment
                                        ->default($loan->monthly_installment)
                                        ->disabled()
                                        ->columnSpan(1),
                                    TextInput::make('outstanding_balance')
                                        ->label('قەرزی ماوە') // Outstanding Balance
                                        ->default($loan->outstanding_balance)
                                        ->disabled()
                                        ->columnSpan(1),
                                    TextInput::make('buying_date')
                                        ->label('بەرواری کڕین') // Buying Date
                                        ->default($loan->buying_date)
                                        ->disabled()
                                        ->columnSpan(1),
                                    Select::make('status')
                                        ->label('دۆخی قەرز') // Loan Status
                                        ->options([
                                            'active' => 'چالاک',
                                            'completed' => 'تەواو',
                                            'overdue' => 'داواکراو',
                                        ])
                                        ->default($loan->status)
                                        ->disabled()
                                        ->columnSpan(2), // Makes it span both columns
                                ]),
                        ];
                    })
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
