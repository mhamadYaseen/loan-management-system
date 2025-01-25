<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    protected $fillable = [
        'guarantor_name',
        'guarantor_phone',
        'guarantor_address',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
