<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharePremuims extends Model
{
    //protected fillable input fields
    protected $fillable = [
        'company_id',
        'shares_issued',
        'nominal_value',
        'issue_price',
        'premium_per_share',
        'total_premium',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
