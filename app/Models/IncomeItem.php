<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeItem extends Model
{
    //fillable fields
    protected $fillable = [
        'item_name',
        'description',
        'category_id',
    ];

    //relationship with income category
    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'category_id');
    }


}
