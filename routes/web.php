<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\LoanResource;

Route::get('/', function () {
    return view('welcome');
});
