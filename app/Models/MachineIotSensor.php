<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineIotSensor extends Model
{
    //
    use HasFactory;

    protected $table = 'machine_iot_sensors';

    protected $fillable = [
        'machine_id',
        'sensor_code',
        'type',
        'last_reading',
        'last_reading_unit',
        'last_sync_at',
        'status',
    ];

    protected $casts = [
        'last_reading' => 'decimal:2',
        'last_sync_at' => 'datetime',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    /**
     * Called by the ingestion endpoint whenever the physical sensor pushes a reading.
     */
    public function recordReading(float $value, string $unit): void
    {
        $this->update([
            'last_reading' => $value,
            'last_reading_unit' => $unit,
            'last_sync_at' => now(),
            'status' => 'Online',
        ]);
    }
}
