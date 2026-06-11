<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceItems extends Model
{
    //fillable fields for the FinanceItems model
    protected $fillable = [
        'item_name',
        'description',
        'category_id',
    ];

    //relationship with the items_categories table
    public function category()
    {
        return $this->belongsTo(ItemsCategory::class, 'category_id');
    }
}
