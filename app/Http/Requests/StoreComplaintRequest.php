<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // All authenticated users can create complaints
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:maintenance,security,facility,neighbor,other',
            'priority' => 'required|in:low,medium,high,urgent',
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
            'title.required' => 'Judul keluhan harus diisi.',
            'description.required' => 'Deskripsi keluhan harus diisi.',
            'category.required' => 'Kategori keluhan harus dipilih.',
            'priority.required' => 'Prioritas keluhan harus dipilih.',
        ];
    }
}