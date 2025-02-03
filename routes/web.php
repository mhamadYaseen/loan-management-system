<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\LoanResource;
use App\Http\Controllers\InstallmentController;



Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\LoanController;

// // ...existing code...
Route::middleware(['auth'])->group(function () {
    //     Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('installments/{loan}', [InstallmentController::class,'show'])->name('installments', 'show');
    Route::post('/installments/{installment}/pay', [InstallmentController::class, 'pay'])
        ->name('installments.pay');
});
