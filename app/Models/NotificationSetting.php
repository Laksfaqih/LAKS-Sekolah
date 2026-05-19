<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    public const KEY_SCHEDULE_REMINDER_MINUTES = 'schedule_reminder_minutes';

    public const DEFAULT_SCHEDULE_REMINDER_MINUTES = 10;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    public static function set(string $key, mixed $value): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function getScheduleReminderMinutes(): int
    {
        return (int) self::get(
            self::KEY_SCHEDULE_REMINDER_MINUTES,
            self::DEFAULT_SCHEDULE_REMINDER_MINUTES
        );
    }
}
