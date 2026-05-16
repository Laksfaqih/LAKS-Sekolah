<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BellTrigger extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PLAYED = 'played';

    public const STATUS_SKIPPED_NO_AUDIO = 'skipped_no_audio';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'pengaturan_bel_id',
        'nama',
        'tipe_bel',
        'audio_path',
        'triggered_at',
        'status',
        'played_at',
        'played_by_browser',
        'failure_reason',
    ];

    protected function casts(): array
    {
        return [
            'triggered_at' => 'datetime',
            'played_at' => 'datetime',
        ];
    }

    public function pengaturanBel(): BelongsTo
    {
        return $this->belongsTo(PengaturanBel::class);
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (! $this->audio_path) {
            return null;
        }

        return Storage::disk('public')->url($this->audio_path);
    }
}
