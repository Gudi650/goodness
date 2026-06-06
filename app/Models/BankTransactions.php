<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransactions extends Model
{
    //fillables
    protected $fillable = [
        'bank_id',
        'company_id',
        'balance_after',
        'affecting_balance',
        'expense_id',
        'invoice_id',
        'order_id',
        'transaction_type'
    ];


    //relationship with virtual accounts
    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccounts::class, 'bank_id');
    }

    //relationship with expenses
    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }

    //relationship with invoices
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    //relationship with orders
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    

}
