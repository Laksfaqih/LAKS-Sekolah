<?php

namespace App\Http\Requests;

use App\Models\JadwalPelajaran;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JadwalPelajaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $jadwalId = $this->route('jadwal_pelajaran')?->id;

        return [
            'guru_id' => [
                'required',
                'exists:gurus,id',
                Rule::unique('jadwal_pelajarans')
                    ->where(fn ($query) => $query
                        ->where('hari', $this->input('hari'))
                        ->where('jam_pelajaran_id', $this->input('jam_pelajaran_id')))
                    ->ignore($jadwalId),
            ],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'kelas_id' => [
                'required',
                'exists:kelas,id',
                Rule::unique('jadwal_pelajarans')
                    ->where(fn ($query) => $query
                        ->where('hari', $this->input('hari'))
                        ->where('jam_pelajaran_id', $this->input('jam_pelajaran_id')))
                    ->ignore($jadwalId),
            ],
            'jam_pelajaran_id' => ['required', 'exists:jam_pelajarans,id'],
            'hari' => ['required', Rule::in(JadwalPelajaran::hariOptions())],
        ];
    }

    public function messages(): array
    {
        return [
            'guru_id.unique' => 'Guru sudah memiliki jadwal pada hari dan jam tersebut.',
            'kelas_id.unique' => 'Kelas sudah memiliki jadwal pada hari dan jam tersebut.',
        ];
    }
}
