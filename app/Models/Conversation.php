<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Ambil pesan terbaru (untuk list chat)
    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
