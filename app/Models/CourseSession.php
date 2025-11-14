<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Kalau kamu membuat enum CourseMode (online|offline|hybrid) pakai ini:
use App\Enums\CourseMode;
use App\Models\RescheduleRequest;

class CourseSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'instructor_id',
        'title',
        'mode',                 // online|offline|hybrid
        'scheduled_at',
        'duration_minutes',
        'meeting_url',
        'meeting_platform',
        'location',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /* =======================
       Relationships
       ======================= */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function rescheduleRequests()
    {
        return $this->hasMany(RescheduleRequest::class, 'course_session_id');
    }

    /* =======================
       Scopes helper
       ======================= */
    public function scopeOnline($q)
    {
        return $q->where('mode', 'online');
        // kalau pakai Enum: ->where('mode', CourseMode::Online)
    }

    public function scopeOffline($q)
    {
        return $q->where('mode', 'offline');
    }

    public function scopeHybrid($q)
    {
        return $q->where('mode', 'hybrid');
    }

    /* =======================
       Accessors kecil
       ======================= */
    public function getIsOnlineAttribute(): bool
    {
        return (string) $this->mode === 'online';
    }

    public function getIsOfflineAttribute(): bool
    {
        return (string) $this->mode === 'offline';
    }
}
