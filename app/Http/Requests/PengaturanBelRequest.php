<?php

namespace App\Http\Requests;

use App\Models\PengaturanBel;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengaturanBelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'tipe_bel' => ['required', Rule::in(PengaturanBel::tipeOptions())],
            'jam_bunyi' => ['required', 'date_format:H:i'],
            'audio_file' => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/wav,audio/x-wav,audio/ogg,audio/mp4', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
