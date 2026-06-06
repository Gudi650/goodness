<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualAccounts extends Model
{
    //store the accounts data
    protected $fillable = [
        'bank_name',
        'account_name',
        'account_number',
        'account_type',
        'card_number',
        'company_id',
        'currency',
        'balance',
        'description',
        'status'
    ];

    //relationship with company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //relationship with expenses
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'bank_id');
    }
}
