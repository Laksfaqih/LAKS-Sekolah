<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PengaturanBel extends Model
{
    use HasFactory;

    public const TIPE_OPTIONS = [
        'masuk',
        'pergantian_jam',
        'istirahat',
        'pulang',
    ];

    protected $fillable = [
        'nama',
        'tipe_bel',
        'jam_bunyi',
        'audio_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public static function tipeOptions(): array
    {
        return self::TIPE_OPTIONS;
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (! $this->audio_path) {
            return null;
        }

        return Storage::disk('public')->url($this->audio_path);
    }
}
