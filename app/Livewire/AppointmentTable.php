<?php

namespace App\Livewire;

use App\Exports\AppointmentsExport;
use App\Exports\ClientsExport;
use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AppointmentTable extends Component
{
    use WithPagination;

    public $appointments = [];
    public $page = 1;
    public $lastPage = 1;
    public $perPage = 10;  // Por defecto, 10 elementos por página

    public $from_date, $to_date, $company_name;

    protected $queryString = ['from_date', 'to_date', 'company_name', 'page'];

    public function mount()
    {
        $this->loadAppointments();
    }

    public function updated($propertyName)
    {
        $this->page = 1;
        $this->loadAppointments();
    }

    #[On('refreshTable')]
    public function loadAppointments()
    {
        $params = array_filter([
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'company_name' => $this->company_name,
            'page' => $this->page,
        ]);

        $response = Http::withToken(session('auth_token'))
            ->get(route('api.appointments.index'), $params);


        $data = $response->json();

        $this->appointments = $data['data'] ?? [];
        $this->page = $data['meta']['current_page'];
        $this->lastPage = $data['meta']['last_page'];
    }

    public function goToPage($page)
    {
        $this->page = $page;
        $this->loadAppointments();
    }

    public function editAppointment($id) {
        $appointment = Appointment::find($id);
        $this->dispatch('openAppointmentModal', $appointment->client->id, $appointment->id);
    }

    public function fillAppointment($id) {
        $appointment = Appointment::find($id);
        $this->dispatch('openFillAppointmentModal', $appointment->client->id, $appointment->id);
    }

    public function render()
    {
        return view('livewire.appointment-table', [
            //'clients' => Client::all(),
        ]);
    }



    public function exportCSV()
    {
        return Excel::download(new AppointmentsExport([
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'company_name' => $this->company_name,
        ]), 'citas.csv');
    }

    public function exportXLSX()
    {
        return Excel::download(new AppointmentsExport([
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'company_name' => $this->company_name,
        ]), 'citas.xlsx');
    }
}

