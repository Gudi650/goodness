<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Company model
 *
 * This model represents one company/subsidiary record
 * stored in the companies table.
 */
#[Fillable(['name', 'country', 'revenue', 'status'])]
class Company extends Model
{
    // No extra logic needed for now.
    // Laravel handles basic create/read/update/delete operations.
}
