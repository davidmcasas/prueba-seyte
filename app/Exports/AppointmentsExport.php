<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AppointmentsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Appointment::with('client:id,company_name,cif');

        if (!empty($this->filters['company_name'])) {
            $query->whereHas('client', function($q) {
                $q->where('company_name', 'like', '%' . $this->filters['company_name'] . '%');
            });
        }
        if (!empty($this->filters['from_date'] && !empty($this->filters['to_date']))) {
            $query->whereBetween('date', [$this->filters['from_date'], $this->filters['to_date']]);
        }

        // Obtener los datos y formatearlos
        return $query->get()->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'company_name' => $appointment->client->company_name ?? 'N/A',
                'cif' => $appointment->client->cif ?? 'N/A',
                'date' => $appointment->date,
                'requested_examinations' => strval($appointment->requested_examinations),
                'performed_examinations' => strval($appointment->performed_examinations),
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Cliente (Razón Social)', 'Cliente (CIF)', 'Fecha y Hora', 'Reconocimientos Solicitados', 'Reconocimientos Realizados'];
    }
}
