<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\LoanResource;
use App\Http\Controllers\InstallmentController;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;

Route::post('admin/login', function () {
    return view('auth.login');
})->name('login');

    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', function () {
        Auth::logout();
        return redirect('/admin/login');
    })->name('logout');
    //     Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('installments/{loan}', [InstallmentController::class, 'show'])->name('installments');
    Route::get('/installments/{installment}/edit', [InstallmentController::class, 'edit'])->name('installments.edit');
    Route::put('/installments/{installment}', [InstallmentController::class, 'update'])->name('installments.update');

    Route::post('/installments/{installment}/pay', [InstallmentController::class, 'pay'])
        ->name('installments.pay');
    });
    