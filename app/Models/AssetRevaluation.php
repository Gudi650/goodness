<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetRevaluation extends Model
{
    //fillable fields for the asset revaluation model
    protected $fillable = [
        'company_id',
        'asset_id',
        'book_value',
        'revalued_amount',
        'surplus',
        'date_of_revaluation',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function asset()
    {
        return $this->belongsTo(CreateAssets::class, 'asset_id', 'id');
    }
}
