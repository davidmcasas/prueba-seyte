<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //'code' => 'string|max:255|unique:clients,code',
            'company_name' => 'required|string|max:255',
            'cif' => [
                'required','string','max:255',
                Rule::unique('clients', 'cif')->ignore($this->cif, 'cif'),
            ],
            'address' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
            'examinations_included' => 'required|integer',
            //'deleted_at' => 'date',
        ];
    }
}
