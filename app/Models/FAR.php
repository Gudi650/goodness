<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAR extends Model
{
    //fillable fields for the FAR model
    protected $fillable = [
        'create_assets_id',
        'depreciation_value',
        'current_value',
        'accumulated_depreciation',
        'last_depreciation_date',
    ];

    //relationship with the create_assets table
    public function createAsset()
    {
        return $this->belongsTo(CreateAssets::class, 'create_assets_id');
    }

    //

}
