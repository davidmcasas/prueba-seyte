<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Client::query()->withSum('appointments', 'performed_examinations');

        if (!empty($this->filters['company_name'])) {
            $query->where('company_name', 'like', '%' . $this->filters['company_name'] . '%');
        }

        if (!empty($this->filters['municipality'])) {
            $query->where('municipality', 'like', '%' . $this->filters['municipality'] . '%');
        }

        if ($this->filters['contract_state']) {
            if ($this->filters['contract_state'] === "active") {
                $query->where('clients.contract_start_date', '<=', now())
                    ->where('clients.contract_end_date', '>=', now());
            } else if ($this->filters['contract_state'] === "not_active") {
                $query->where(function ($query) {
                    $query->where('clients.contract_start_date', '>=', now())
                        ->orWhere('clients.contract_end_date', '<=', now());
                });
            }
        }

        return $query->get()->map(function ($client) {
            return [
                'id' => $client->id,
                'code' => $client->code,
                'company_name' => $client->company_name,
                'cif' => $client->cif,
                'address' => $client->address,
                'municipality' => $client->municipality,
                'province' => $client->province,
                'contract_start_date' => $client->contract_start_date,
                'contract_end_date' => $client->contract_end_date,
                'examinations_included' => $client->examinations_included,
                'performed_examinations_sum' => $client->appointments_sum_performed_examinations ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID', 'Código', 'Razón Social', 'CIF', 'Dirección', 'Municipio',
            'Provincia', 'Inicio Contrato', 'Fin Contrato',
            'Reconocimientos Contratados', 'Reconocimientos Realizados'
        ];
    }
}
