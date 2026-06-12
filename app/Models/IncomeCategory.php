<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    //fillable fields
    protected $fillable = [
        'category_name',
        'description',
    ];


    //relationship with income items
    public function items()
    {
        return $this->hasMany(IncomeItem::class, 'category_id');
    }

}
