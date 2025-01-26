<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'company',
        'description'
    ];

    public function customerCredits()
    {
        return $this->hasMany(CustomerCredit::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
