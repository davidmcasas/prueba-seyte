<?php

namespace App\Livewire;

use App\Http\Requests\ClientRequest;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;


class CreateEditClient extends Component
{
    public $clientId; // ID del cliente (si se pasa uno, es edición)
    public $company_name, $cif, $address, $municipality, $province, $contract_start_date, $contract_end_date, $examinations_included;

    public function mount()
    {
        $this->updateForm();
    }

    public function updateForm() {
        // Si se pasa un ID de cliente, se trata de edición
        if ($this->clientId) {

            // Hacemos la llamada a la API para obtener los datos del cliente

            $apiUrl = route('api.clients.show', $this->clientId);
            $response = Http::withToken(session('auth_token'))->get($apiUrl);
            /*$response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('api_token'), // Usamos el token guardado en la sesión
            ])->get("http://127.0.0.1:8000/api/clients/{$clientId}");*/

            if ($response->successful()) {
                $client = $response->json()['data'];

                // Rellenamos los campos con los datos del cliente
                $this->company_name = $client['company_name'];
                $this->cif = $client['cif'];
                $this->address = $client['address'];
                $this->municipality = $client['municipality'];
                $this->province = $client['province'];
                $this->contract_start_date = $client['contract_start_date'];
                $this->contract_end_date = $client['contract_end_date'];
                $this->examinations_included = $client['examinations_included'];
            }
        }
    }

    public function submit()
    {
        // Validación ya realizada por ClientRequest
        $validatedData = $this->validate([
            'company_name' => 'required|string|max:255',
            'cif' => 'required|string|max:255|unique:clients,cif,' . $this->clientId,
            'address' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'required|date|after_or_equal:contract_start_date',
            'examinations_included' => 'required|integer',
        ]);

        if ($this->clientId) {
            $this->updateClient();
        } else {
            $this->createClient();
        }
    }

    // Función para crear un cliente
    public function createClient()
    {
        $apiUrl = route('api.clients.store');
        $response = Http::withToken(session('auth_token'))->post($apiUrl, [
            'company_name' => $this->company_name,
            'cif' => $this->cif,
            'address' => $this->address,
            'municipality' => $this->municipality,
            'province' => $this->province,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'examinations_included' => $this->examinations_included,
        ]);

        // Mostrar mensaje de éxito
        if ($response->successful()) {
            session()->flash('message', 'Cliente creado correctamente.');
        } else {
            session()->flash('error', 'Hubo un problema al crear el cliente.');
        }

        // Limpiar los campos después de enviar
        $this->reset();
        $this->dispatch('refreshTable');
    }

    #[On('editClient')]
    public function editClient($clientId)
    {
        $this->resetValidation();
        $this->clientId = $clientId;
        $this->updateForm();
    }

    // Función para editar un cliente
    public function updateClient()
    {
        $apiUrl = route('api.clients.update', $this->clientId);
        $response = Http::withToken(session('auth_token'))->patch($apiUrl, [
            'company_name' => $this->company_name,
            'cif' => $this->cif,
            'address' => $this->address,
            'municipality' => $this->municipality,
            'province' => $this->province,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'examinations_included' => $this->examinations_included,
        ]);

        // Mostrar mensaje de éxito
        if ($response->successful()) {
            session()->flash('message', 'Cliente actualizado correctamente.');
        } else {
            session()->flash('error', 'Hubo un problema al actualizar el cliente.');
        }

        // Limpiar los campos después de enviar
        $this->reset();
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.create-edit-client');
    }
}
