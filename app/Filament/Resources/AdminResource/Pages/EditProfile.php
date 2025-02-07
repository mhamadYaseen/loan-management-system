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

class EditProfile extends Page
{
    public $user;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
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
                    ->schema([
                        TextInput::make('name')
                            ->label('ناو')
                            ->required()
                            ->default(Auth::user()->name),
                        TextInput::make('email')
                            ->label('ئیمەیڵ')
                            ->email()
                            ->required()
                            ->default(Auth::user()->email),
                        TextInput::make('current_password')
                            ->label('وشەی نهێنی ئێستا')
                            ->password()
                            ->nullable(),
                        TextInput::make('password')
                            ->label('وشەی نهێنی نوێ')
                            ->password()    
                            ->confirmed()
                            ->nullable(),
                        TextInput::make('password_confirmation')
                            ->label('دووبارەکردنەوەی وشەی نهێنی نوێ')
                            ->password()
                            ->nullable(),
                    ])
                    ->columns(2),
            ])
            ->statePath('user');
    }

    public function save(): void
    {
        $user = User::find(Auth::id());

        $data = $this->form->getState();

        // Check if the current password is correct
        if (!empty($data['current_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                Notification::make()->danger()->title('وشەی نهێنی ئێستا هەڵەیە!')->send();
                return;
            }
        }

        // Update the password if a new one is provided
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Remove the current_password and password_confirmation fields before updating
        unset($data['current_password']);
        unset($data['password_confirmation']);

        $user->update($data);

        Notification::make()->success()->title('پڕۆفایلەکەت نوێ کرایەوە!')->send();
    }
}
