<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineLog extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'log_date',
        'shift',
        'temperature',
        'humidity',
        'turning_count',
        'recorded_by_id',
        'flag',
        'notes',
    ];

    protected $casts = [
        'log_date' => 'date',
        'temperature' => 'decimal:2',
        'humidity' => 'decimal:2',
        'turning_count' => 'integer',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }
}
