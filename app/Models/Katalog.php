<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    protected $table = 'katalogs';
    protected $primaryKey = 'id_katalog';
    protected $fillable = ['nama_katalog', 'deskripsi', 'harga', 'stok', 'gambar', 'status'];
    public $timestamps = true;
}
