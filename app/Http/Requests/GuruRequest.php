<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuruRequest extends FormRequest
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
        $guruId = $this->route('guru')?->id;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:100', Rule::unique('gurus', 'nip')->ignore($guruId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('gurus', 'email')->ignore($guruId)],
            'no_hp' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
