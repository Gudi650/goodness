<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharesDefinitions extends Model
{
    //fillables 
    protected $fillable = [
        'company_id',
        'authorized_shares',
        'issued_shares',
        'remaining_shares',
        'share_value',
        'notes',
    ];

    //relationships 

    //relationship with company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
