<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineAlarm extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'alarm_type',
        'severity',
        'triggered_at',
        'status',
        'resolved_by_id',
        'resolved_at',
        'notes',
    ];

    protected $casts = [
        'triggered_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }
}
