<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentitasSekolah extends Model
{
    use HasFactory;

    protected $table = 'identitas_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'npsn',
        'alamat',
        'telepon',
        'email',
        'website',
        'nama_kepala_sekolah',
    ];
}
