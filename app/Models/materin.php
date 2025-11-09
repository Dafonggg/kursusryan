<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class materin extends Model
{
    protected $table = 'materins';
    protected $primaryKey = 'id_materin';
    protected $fillable = ['id_kursus', 'jenis_file', 'file_materin', 'link_video'];
    public $timestamps = true;

    public function kursus()
    {
        return $this->belongsTo(Kursus::class, 'id_kursus');
    }
}
