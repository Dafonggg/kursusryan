<?php

namespace App\Models;

use App\Enums\MaterialType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'type',
        'title',
        'path',     // file lokal
        'url',      // link video / drive / youtube
        'order',
        'scope'     // online/offline/both (kalau kamu aktifkan batch scope)
    ];

    protected $casts = [
        'type' => MaterialType::class,
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /** Scope cepat (buat filter dari sisi UI) */
    public function scopeVideo($q)
    {
        return $q->where('type', MaterialType::Video);
    }

    public function scopeDocument($q)
    {
        return $q->where('type', MaterialType::Document);
    }

    /** Urutkan materi awal */
    protected static function booted()
    {
        static::creating(function ($material) {
            if ($material->order === null) {
                $material->order = static::where('course_id', $material->course_id)->max('order') + 1;
            }
        });
    }
}
