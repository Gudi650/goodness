<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquityDistribution extends Model
{
    //fillable fields for the equity distribution model
    protected $fillable = [
        'company_id',
        'shareholder',
        'shares',
        'share_value',
        'value_held',
        'notes',
    ];

    //relationships
     
    //relationship with company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
