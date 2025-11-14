<?php
// app/Models/Certificate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'certificate_no',
        'issued_at',
        'file_path',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    // RELATION
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // Akses cepat ke student melalui enrollment (opsional)
    public function student()
    {
        return $this->hasOneThrough(
            User::class,
            Enrollment::class,
            'id',        // Foreign key di Enrollment ke Certificate.enrollment_id (mapped oleh relasi inverse)
            'id',        // Key di User
            'enrollment_id', // Local key di Certificate
            'user_id'    // Foreign key di Enrollment ke User
        );
    }

    // Helper: generate nomor sertifikat unik
    public static function generateNumber(): string
    {
        // Contoh format: RK-2025-11-XXXXXX (random upper)
        return 'RK-' . now()->format('Y') . '-' . now()->format('m') . '-' . strtoupper(Str::random(6));
    }

    // Helper: terbitkan sertifikat untuk enrollment tertentu
    public static function issueFor(Enrollment $enrollment, ?string $filePath = null): self
    {
        return self::create([
            'enrollment_id' => $enrollment->id,
            'certificate_no' => self::generateNumber(),
            'issued_at' => now(),
            'file_path' => $filePath,
        ]);
    }
}
