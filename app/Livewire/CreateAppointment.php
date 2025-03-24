<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Client;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class CreateAppointment extends Component
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
        'state' => 'required|in:pending,finished,canceled',
    ];

    #[On('openAppointmentModal')]
    public function openAppointmentModal($clientId, $appointmentId = null)
    {
        $this->reset(); // Resetea el formulario
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
            $this->state = $appointment->state;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    /*public function mount($clientId, $appointmentId = null)
    {
        $this->clientId = $clientId;

        if ($appointmentId) {
            $appointment = Appointment::findOrFail($appointmentId);
            $this->appointmentId = $appointment->id;
            $this->date = Carbon::parse($appointment->date)->format('Y-m-d\TH:i');
            $this->requested_examinations = $appointment->requested_examinations;
            $this->performed_examinations = $appointment->performed_examinations;
            $this->state = $appointment->state;
        }
    }*/

    public function submit()
    {
        $this->validate();

        if ($this->appointmentId) {
            $appointment = Appointment::findOrFail($this->appointmentId);
            $appointment->update([
                'date' => $this->date,
                'requested_examinations' => $this->requested_examinations,
                'performed_examinations' => $this->performed_examinations ?? 0,
                'state' => $this->state,
            ]);
            session()->flash('message', 'Cita actualizada correctamente.');
        } else {
            Appointment::create([
                'client_id' => $this->clientId,
                'date' => $this->date,
                'requested_examinations' => $this->requested_examinations,
                'performed_examinations' => $this->performed_examinations ?? 0,
                'state' => $this->state,
            ]);
            session()->flash('message', 'Cita creada correctamente.');
        }

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.create-appointment');
    }
}
