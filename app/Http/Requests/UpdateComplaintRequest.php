<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user();
        
        $baseRules = [
            'house_id' => 'required|exists:houses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:maintenance,security,facility,neighbor,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ];

        // Staff can update additional fields
        if ($user->canManageComplaints()) {
            $baseRules = array_merge($baseRules, [
                'status' => 'required|in:open,in_progress,resolved,closed,cancelled',
                'assigned_to' => 'nullable|exists:users,id',
                'response' => 'nullable|string',
                'target_resolution_date' => 'nullable|date',
                'estimated_cost' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);
        }

        return $baseRules;
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
            'status.required' => 'Status keluhan harus dipilih.',
            'assigned_to.exists' => 'Petugas yang dipilih tidak valid.',
            'target_resolution_date.date' => 'Format tanggal target penyelesaian tidak valid.',
            'estimated_cost.numeric' => 'Estimasi biaya harus berupa angka.',
            'estimated_cost.min' => 'Estimasi biaya tidak boleh negatif.',
        ];
    }
}