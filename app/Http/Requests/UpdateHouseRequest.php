<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHouseRequest extends FormRequest
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
            'block_number' => 'required|string|max:255|unique:houses,block_number,' . $this->route('house')->id,
            'address' => 'required|string',
            'house_type' => 'required|string|max:255',
            'land_area' => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold,reserved,maintenance',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:20',
            'handover_date' => 'nullable|date',
            'selling_price' => 'nullable|numeric|min:0',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
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
            'block_number.required' => 'Nomor blok/unit harus diisi.',
            'block_number.unique' => 'Nomor blok/unit sudah digunakan.',
            'address.required' => 'Alamat rumah harus diisi.',
            'house_type.required' => 'Tipe rumah harus diisi.',
            'land_area.required' => 'Luas tanah harus diisi.',
            'land_area.numeric' => 'Luas tanah harus berupa angka.',
            'building_area.required' => 'Luas bangunan harus diisi.',
            'building_area.numeric' => 'Luas bangunan harus berupa angka.',
            'status.required' => 'Status rumah harus dipilih.',
            'bedrooms.required' => 'Jumlah kamar tidur harus diisi.',
            'bedrooms.integer' => 'Jumlah kamar tidur harus berupa angka.',
            'bathrooms.required' => 'Jumlah kamar mandi harus diisi.',
            'bathrooms.integer' => 'Jumlah kamar mandi harus berupa angka.',
        ];
    }
}