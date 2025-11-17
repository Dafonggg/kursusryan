<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'whatsapp_opt_in',
        'address',
        'kk_path',
        'ktp_path',
        'photo_path',
    ];

    protected $casts = [
        'whatsapp_opt_in' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
