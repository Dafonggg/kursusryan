<?php

namespace App\Models;

use App\Enums\RescheduleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RescheduleRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_session_id',
        'requested_by',
        'proposed_at',
        'status',
        'reason',
        'decided_by',
        'decided_at',
        'decision_notes',
    ];

    protected $casts = [
        'proposed_at' => 'datetime',
        'decided_at' => 'datetime',
        'status' => RescheduleStatus::class, // pending|approved|rejected
    ];

    // RELATIONS
    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function decider()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    // SCOPES
    public function scopePending($q)
    {
        return $q->where('status', RescheduleStatus::Pending);
    }

    // HELPERS
    public function approve(): void
    {
        $this->update(['status' => RescheduleStatus::Approved]);
    }

    public function reject(?string $reason = null): void
    {
        $this->update([
            'status' => RescheduleStatus::Rejected,
            'decision_notes' => $reason ?? $this->decision_notes,
        ]);
    }
}
