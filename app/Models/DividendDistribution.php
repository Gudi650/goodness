<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DividendDistribution extends Model
{
    //fillable input fields
    protected $fillable = [
        'dividend_id',
        'equity_id',
        'shareholder_name',
        'shares',
        'ownership_percentage',
        'amount',
        'notes',
    ];


    //relationships

    //relationship with the Dividend model
    public function dividend()
    {
        return $this->belongsTo(Dividends::class);
    }

    //relationship with the Equity model
    public function equity()
    {
        return $this->belongsTo(EquityDistribution::class);
    }

}
