<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role model
 * 
 * Represents a role/position that can be assigned to users.
 * Examples: Admin, Manager, Employee, Viewer
 */
#[Fillable(['name', 'description'])]
class Role extends Model
{
    /**
     * Get all users that have this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
