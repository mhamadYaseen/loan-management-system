<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'address']; 

    public function guarantor()
    {
        return $this->hasOne(Guarantor::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
