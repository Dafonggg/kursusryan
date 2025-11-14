<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_session_id',
        'user_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => AttendanceStatus::class,
    ];

    public function session()
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
