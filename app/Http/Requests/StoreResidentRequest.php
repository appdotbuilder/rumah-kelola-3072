<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->canManageHouses();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'house_id' => 'required|exists:houses,id',
            'user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'id_number' => 'nullable|string|max:255',
            'relationship' => 'required|in:owner,tenant,family_member',
            'move_in_date' => 'nullable|date',
            'move_out_date' => 'nullable|date|after:move_in_date',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'house_id.required' => 'Rumah harus dipilih.',
            'house_id.exists' => 'Rumah yang dipilih tidak valid.',
            'name.required' => 'Nama penghuni harus diisi.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'relationship.required' => 'Hubungan dengan rumah harus dipilih.',
            'move_out_date.after' => 'Tanggal pindah keluar harus setelah tanggal pindah masuk.',
        ];
    }
}