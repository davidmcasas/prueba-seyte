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
        // Aplicar filtros a la consulta
        $query = Client::query();

        if (!empty($this->filters['company_name'])) {
            $query->where('company_name', 'like', '%' . $this->filters['company_name'] . '%');
        }

        if (!empty($this->filters['municipality'])) {
            $query->where('municipality', 'like', '%' . $this->filters['municipality'] . '%');
        }

        return $query->get(['id', 'code', 'company_name', 'cif', 'address', 'municipality', 'province', 'contract_start_date', 'contract_end_date', 'examinations_included']);
    }

    public function headings(): array
    {
        return ['ID', 'Código', 'Razón Social', 'CIF', 'Dirección', 'Municipio', 'Provincia', 'Inicio Contrato', 'Fin Contrato', 'Reconocimientos Contratados'];
    }
}
