<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PresensiGuru extends Model
{
    use HasFactory;

    public const STATUS_OPTIONS = [
        'hadir',
        'izin',
        'sakit',
    ];

    protected $fillable = [
        'guru_id',
        'jadwal_pelajaran_id',
        'tanggal',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function jadwalPelajaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }

    public static function statusOptions(): array
    {
        return self::STATUS_OPTIONS;
    }
}
