<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function show(Loan $loan)
    {

        return view('loans.show', compact('loan'));
    }
}
