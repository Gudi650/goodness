<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiabilityCategory extends Model
{
    //fillable fields
    protected $fillable = [
        'category',
        'description',
    ];
    
}
