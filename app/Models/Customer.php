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
        'description',
        'brand',
        'model',
        'registration_no'
    ];

    public function customerAccounts()
    {
        return $this->hasMany(CustomerAccount::class);
    }

    public function customerAccountTransactions()
    {
        return $this->hasMany(CustomerAccountTransaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
