<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class FillAppointment extends Component
{
    public $isOpen = false; // Controla la visibilidad del modal
    public $appointmentId;
    public $clientId;
    public $companyName;
    public $cif;
    public $performed_examinations;

    protected $rules = [
        'performed_examinations' => 'required|integer|min:0',
    ];

    #[On('openFillAppointmentModal')]
    public function openFillAppointmentModal($clientId, $appointmentId)
    {
        $this->reset();
        $this->resetValidation();
        $this->isOpen = true;
        $this->clientId = $clientId;
        $client = Client::findOrFail($clientId);
        $this->companyName = $client->company_name;
        $this->cif = $client->cif;

        $appointment = Appointment::findOrFail($appointmentId);
        $this->appointmentId = $appointment->id;
        //$this->performed_examinations = $appointment->performed_examinations;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function submit()
    {
        $this->validate();

        $appointment = Appointment::findOrFail($this->appointmentId);
        $appointment->update([ // TODO: use API
            'performed_examinations' => $this->performed_examinations ?? 0,
        ]);
        session()->flash('message', 'Cita actualizada correctamente.');

        $this->closeModal();
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.fill-appointment');
    }
}
