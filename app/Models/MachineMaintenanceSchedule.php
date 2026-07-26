<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineMaintenanceSchedule extends Model
{
    //
    use HasFactory;

    protected $table = 'machine_maintenance_schedules';

    protected $fillable = [
        'machine_id',
        'maintenance_type',
        'scheduled_date',
        'frequency',
        'technician_id',
        'status',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_at' => 'date',
    ];

    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Recompute status based on the scheduled date. Call before displaying
     * or hook into a scheduled command (php artisan schedule:run).
     */
    public function refreshStatus(): void
    {
        if ($this->status === 'Completed') {
            return;
        }

        $this->status = $this->scheduled_date->isPast() ? 'Overdue' : 'Upcoming';
        $this->save();
    }
}
