<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsCategories extends Model
{
    //fillable fields
    protected $fillable = [
        'category',
        'description',
    ];
}
