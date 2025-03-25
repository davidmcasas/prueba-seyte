<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'requested_examinations' => 'required|integer|min:0',
            'performed_examinations' => 'nullable|integer|min:0',
        ];
    }
}
