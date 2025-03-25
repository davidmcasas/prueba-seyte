<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class AppointmentTable extends Component
{
    public $from_date, $to_date, $company_name;
    public $appointments = [];

    public function updated($propertyName)
    {
        $this->loadAppointments();
    }

    #[On('refreshTable')]
    public function loadAppointments()
    {
        $params = array_filter([
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'company_name' => $this->company_name,
        ]);

        $response = Http::withToken(session('auth_token'))
            ->get(route('api.appointments.index'), $params);

        $this->appointments = $response->json()['data'] ?? [];
    }

    public function mount()
    {
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
}

