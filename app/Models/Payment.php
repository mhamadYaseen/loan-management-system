<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['installment_id', 'payment_amount', 'payment_date'];

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}

