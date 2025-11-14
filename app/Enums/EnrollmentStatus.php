<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Pending   = 'pending';   // baru daftar, belum divalidasi admin
    case Active    = 'active';    // sudah bayar & divalidasi
    case Completed = 'completed'; // selesai kursus + layak sertifikat
    case Expired   = 'expired';   // habis masa aktif
    case Cancelled = 'cancelled';
}
