<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalMessages extends Model
{
    //fillable fields for mass assignment
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'deleted_by',
        'deleted_at',
        'company_id',
        'attachment_path',
        'attachment_name',
        'is_read',
        'delivered',
        'seen',
        'seen_at',
    ];

    //relationship to sender user
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    //relationship to receiver user
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    //scope to get only messages that are not deleted for a given user
    public function scopeNotDeletedForUser($query, $userId)
    {        return $query->where(function ($q) use ($userId) {
            $q->whereNull('deleted_by')
              ->orWhere('deleted_by', '!=', $userId);
        });
    
    }

}
