<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Company model
 *
 * This model represents one company/subsidiary record
 * stored in the companies table. Each company can have
 * multiple users assigned to it.
 */
#[Fillable(['name', 'country', 'revenue', 'status'])]
class Company extends Model
{
    /**
     * Get all users that belong to this company.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all departments that belong to this company.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get all virtual accounts that belong to this company.
     */
    public function virtualAccounts(): HasMany
    {
        return $this->hasMany(VirtualAccounts::class);
    }

    /**
     * Get all equity distributions that belong to this company.
     */
    public function equityDistributions(): HasMany
    {
        return $this->hasMany(EquityDistribution::class);
    }

    /**
     * Get all define equity that belong to this company
     */
    public function sharesDefinitions(): HasMany
    {
        return $this->hasMany(SharesDefinitions::class);
    }

}
