<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'client' => [
                'id' => $this->client->id,
                'code' => $this->client->code,
                'company_name' => $this->client->company_name,
                'cif' => $this->client->cif,
                'address' => $this->client->address,
                'municipality' => $this->client->municipality,
                'province' => $this->client->province,
                'contract_start_date' => $this->client->contract_start_date,
                'contract_end_date' => $this->client->contract_end_date,
                'examinations_included' => $this->client->examinations_included,
                'created_at' => $this->client->created_at,
                'updated_at' => $this->client->updated_at,
                'deleted_at' => $this->client->deleted_at,
            ],
            'date' => $this->date,
            'requested_examinations' => $this->requested_examinations,
            'performed_examinations' => $this->performed_examinations,
            'state' => $this->state,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
