<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class JadwalPelajaran extends Model
{
    use HasFactory;

    public const HARI_OPTIONS = [
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
    ];

    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'kelas_id',
        'jam_pelajaran_id',
        'hari',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jamPelajaran(): BelongsTo
    {
        return $this->belongsTo(JamPelajaran::class);
    }

    public function presensiGurus(): HasMany
    {
        return $this->hasMany(PresensiGuru::class);
    }

    public static function hariOptions(): array
    {
        return self::HARI_OPTIONS;
    }

    public static function hariIni(?Carbon $now = null): string
    {
        $now ??= now();

        if ($now->dayOfWeekIso === 7) {
            return 'Minggu';
        }

        return self::HARI_OPTIONS[$now->dayOfWeekIso - 1] ?? 'Minggu';
    }
}
