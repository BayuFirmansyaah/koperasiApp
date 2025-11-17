<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnggotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $anggotaId = $this->route('anggota') ? $this->route('anggota')->id : null;
        $userId = $this->route('anggota') ? $this->route('anggota')->user_id : null;

        return [
            // User data
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => $this->isMethod('POST') ? 'required|min:8|confirmed' : 'nullable|min:8|confirmed',
            
            // Anggota data
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('anggotas', 'nik')->ignore($anggotaId),
            ],
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'pekerjaan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
