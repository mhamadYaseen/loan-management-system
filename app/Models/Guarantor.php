<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    protected $fillable = ['customer_id', 'name', 'phone', 'address'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
