<?php

namespace App\Models;

use App\Enums\CourseMode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'duration_months',
        'price',
        'owner_id',
        'mode',            // default_mode di tabel courses
    ];

    protected $casts = [
        'mode' => CourseMode::class,
    ];

    // otomatis isi slug kalau kosong
    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $base = Str::slug($course->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $course->slug = $slug;
            }
        });
    }

    /** Relationships */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function sessions()
    {
        return $this->hasMany(CourseSession::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /** Route model binding pakai slug (opsional, enak buat URL) */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Scopes ringkas */
    public function scopeOnline($q)  { return $q->where('mode', CourseMode::Online); }
    public function scopeOffline($q) { return $q->where('mode', CourseMode::Offline); }
    public function scopeHybrid($q)  { return $q->where('mode', CourseMode::Hybrid); }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(fn ($w) =>
            $w->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
        );
    }
}
