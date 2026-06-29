<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dividends extends Model
{
    //fillable input fields
    protected $fillable = [
        'company_id',
        'amount',
        'declared_at',
        'paid_at',
        'status',
        'notes',
    ];


    //relationship with the Company model
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //relationship with the DividendDistribution model
    public function distributions()
    {
        return $this->hasMany(DividendDistribution::class);
    }

}
