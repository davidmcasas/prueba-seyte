<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'company_name' => $this->company_name,
            'cif' => $this->cif,
            'address' => $this->address,
            'municipality' => $this->municipality,
            'province' => $this->province,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'examinations_included' => $this->examinations_included,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
