<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'location',
        'capacity',
        'serial_number',
        'manufacturer',
        'installed_date',
        'status',
        'iot_enabled',
        'technician_id',
        'notes',
    ];

    protected $casts = [
        'installed_date' => 'date',
        'iot_enabled' => 'boolean',
        'capacity' => 'integer',
    ];

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(MachineLog::class);
    }

    public function alarms(): HasMany
    {
        return $this->hasMany(MachineAlarm::class);
    }

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MachineMaintenanceSchedule::class);
    }

    public function calibrations(): HasMany
    {
        return $this->hasMany(MachineCalibration::class);
    }

    public function iotSensors(): HasMany
    {
        return $this->hasMany(MachineIotSensor::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
