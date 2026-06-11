<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsCategory extends Model
{
    //fillable fields for the ItemsCategory model
    protected $fillable = [
        'category_name',
        'description',
    ];

    //relationship with the finance_items table
    public function items()
    {
        return $this->hasMany(FinanceItems::class, 'category_id');
    }
    
}
