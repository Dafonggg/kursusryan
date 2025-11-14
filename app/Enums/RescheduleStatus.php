<?php

namespace App\Enums;

enum RescheduleStatus: string
{
    case Pending  = 'pending';   // menunggu persetujuan admin/instruktur
    case Approved = 'approved';  // disetujui → session boleh diubah
    case Rejected = 'rejected';  // ditolak → session tetap
}
