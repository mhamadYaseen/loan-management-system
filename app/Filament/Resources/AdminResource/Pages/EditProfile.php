<?php

namespace App\Filament\Resources\AdminResource\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page
{
    public $user;

    protected static ?string $navigationIcon = 'heroicon-o-user';  // Changed icon
    protected static ?string $navigationGroup = 'ڕێکخستن';  // Added navigation group
    protected static ?string $title = 'گۆڕانکاری پڕۆفایل';  // Changed title
    protected static ?int $navigationSort = 99;  // Added sort order
    protected static string $view = 'filament.resources.admin-resource.pages.edit-profile';

    public function mount(): void
    {
        $this->user = Auth::user()->toArray();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('پڕۆفایل')
                    ->description('زانیاری پڕۆفایل بگۆڕە')  // Added description
                    ->schema([
                        TextInput::make('name')
                            ->label('ناو')
                            ->required()
                            ->maxLength(255)  // Added validation
                            ->default(Auth::user()->name),
                        TextInput::make('email')
                            ->label('ئیمەیڵ')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignorable: Auth::user())  // Improved unique validation
                            ->default(Auth::user()->email),
                        TextInput::make('current_password')
                            ->label('وشەی نهێنی ئێستا')
                            ->password()
                            ->requiredWith('password')  // Only required if changing password
                            ->currentPassword(),  // Added validation
                        TextInput::make('password')
                            ->label('وشەی نهێنی نوێ')
                            ->password()    
                            ->confirmed()
                            ->rule(Password::default())  // Added password rules
                            ->nullable(),
                        TextInput::make('password_confirmation')
                            ->label('دووبارەکردنەوەی وشەی نهێنی نوێ')
                            ->password()
                            ->requiredWith('password'),  // Only required if password is set
                    ])
                    ->columns(2),
            ])
            ->statePath('user');
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('پاشەکەوتکردن')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $user = User::find(Auth::id());
        $data = $this->form->getState();

        if (!empty($data['current_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->danger()
                    ->title('وشەی نهێنی ئێستا هەڵەیە!')
                    ->persistent()  // Added persistent
                    ->send();
                return;
            }
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);  // Using Hash::make instead of bcrypt
        } else {
            unset($data['password']);
        }

        unset($data['current_password'], $data['password_confirmation']);

        $user->update($data);

        Notification::make()
            ->success()
            ->title('پڕۆفایلەکەت نوێ کرایەوە!')
            ->duration(5000)  // Added duration
            ->send();
    }
}