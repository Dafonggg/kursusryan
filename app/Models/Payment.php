<?php
// app/Models/Payment.php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'amount',
        'method',
        'status',
        'paid_at',
        'reference',
        'meta',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'meta' => 'array',
        'method' => PaymentMethod::class,
        'status' => PaymentStatus::class,
    ];

    // RELATIONS
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // SCOPES CEPAT
    public function scopePaid($q)
    {
        return $q->where('status', PaymentStatus::Paid);
    }

    public function scopePending($q)
    {
        return $q->where('status', PaymentStatus::Pending);
    }

    // HELPER
    public function markAsPaid(?\Carbon\Carbon $time = null): void
    {
        $this->forceFill([
            'status' => PaymentStatus::Paid,
            'paid_at' => $time ?? now(),
        ])->save();
    }
}
