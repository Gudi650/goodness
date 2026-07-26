<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineCalibration extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'component',
        'calibration_date',
        'next_due',
        'performed_by_id',
        'certificate_path',
        'notes',
    ];

    protected $casts = [
        'calibration_date' => 'date',
        'next_due' => 'date',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by_id');
    }

    public function getHasCertificateAttribute(): bool
    {
        return ! empty($this->certificate_path);
    }
}
