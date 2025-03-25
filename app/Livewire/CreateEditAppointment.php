<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateEditAppointment extends Component
{
    public $isOpen = false; // Controla la visibilidad del modal
    public $appointmentId;
    public $clientId;
    public $companyName;
    public $cif;
    public $date;
    public $requested_examinations;
    public $performed_examinations;
    public $state = 'pending';

    protected $rules = [
        'date' => 'required|date|after_or_equal:today',
        'requested_examinations' => 'required|integer|min:1',
        'performed_examinations' => 'nullable|integer|min:0',
    ];

    #[On('openAppointmentModal')]
    public function openAppointmentModal($clientId, $appointmentId = null)
    {
        $this->reset();
        $this->resetValidation();
        $this->isOpen = true;
        $this->clientId = $clientId;
        $client = Client::findOrFail($clientId);
        $this->companyName = $client->company_name;
        $this->cif = $client->cif;

        if ($appointmentId) {
            $appointment = Appointment::findOrFail($appointmentId);
            $this->appointmentId = $appointment->id;
            $this->date = Carbon::parse($appointment->date)->format('Y-m-d\TH:i');
            $this->requested_examinations = $appointment->requested_examinations;
            $this->performed_examinations = $appointment->performed_examinations;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function submit()
    {
        $this->validate();

        if ($this->appointmentId) {
           $this->updateAppointment();
        } else {
           $this->createAppointment();
        }

        $this->closeModal();
        $this->dispatch('refreshTable');
    }

    // Función para crear una cita
    public function createAppointment()
    {
        $apiUrl = route('api.appointments.store');
        $response = Http::withToken(session('auth_token'))->post($apiUrl, [
            'client_id' => $this->clientId,
            'date' => $this->date,
            'requested_examinations' => $this->requested_examinations,
        ]);

        // Mostrar mensaje de éxito
        if ($response->successful()) {
            session()->flash('message', 'Cita creada correctamente.');
        } else {
            session()->flash('error', 'Hubo un problema al crear la cita.');
        }

        // Limpiar los campos después de enviar
        $this->closeModal();
        $this->dispatch('refreshTable');
    }

    public function updateAppointment()
    {
        $apiUrl = route('api.appointments.update', $this->appointmentId);
        $response = Http::withToken(session('auth_token'))->patch($apiUrl, [
            'client_id' => $this->clientId,
            'date' => $this->date,
            'requested_examinations' => $this->requested_examinations,
        ]);

        // Mostrar mensaje de éxito
        if ($response->successful()) {
            session()->flash('message', 'Cita actualizada correctamente.');
        } else {
            session()->flash('error', 'Hubo un problema al actualizar la cita.');
        }

        // Limpiar los campos después de enviar
        $this->closeModal();
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.create-edit-appointment');
    }
}
