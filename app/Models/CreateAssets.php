<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreateAssets extends Model
{

    //fillable fields for mass assignment
    protected $fillable = [
        'code',
        'name',
        'company_id',
        'category_id',
        'type',
        'term',
        'original_value',
        'current_value',
        'depreciation_value',
        'acquired',
        'status',
    ];

    //defining the relationship with the company model
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //defining the relationship with the asset category model
    public function assetsCategory()
    {
        return $this->belongsTo(AssetsCategories::class);
    }
}
