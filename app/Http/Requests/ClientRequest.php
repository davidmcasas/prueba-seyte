<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'string|max:255|unique:clients,code',
            'company_name' => 'string|max:255',
            'cif' => 'string|max:255|unique:clients,cif',
            'address' => 'string|max:255',
            'municipality' => 'string|max:255',
            'province' => 'string|max:255',
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'examinations_included' => 'integer',
            'deleted_at' => 'date',
        ];
    }
}
