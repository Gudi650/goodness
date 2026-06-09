<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreateLiability extends Model
{
    //fillable 
    protected $fillable = [
        'code',
        'name',
        'company_id',
        'category_id', 
        'type',
        'term',
        'original_amount',
        'current_amount',
        'creditor',
        'interest_rate',
        'due_date',
        'status'
    ];

    // Relationships
    public function company()
    { 
        return $this->belongsTo(Company::class);
    }

    //rtelationship with category
    public function category()
    {
        return $this->belongsTo(LiabilityCategory::class, 'category_id');
    }

}
