<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsCategories extends Model
{


    //fillable fields
    protected $fillable = [
        'category',
        'description',
    ];


    //define relatioship with the create assets model
    public function assets()
    {
        return $this->hasMany(CreateAssets::class, 'category_id');
    }
}
