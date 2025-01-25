<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'guarantor_name',
        'guarantor_phone',
        'guarantor_address',
        'address',
    ];

    public function guarantor()
    {
        return $this->belongsTo(Guarantor::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
