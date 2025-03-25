<?php

namespace App\Livewire;

use App\Exports\ClientsExport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;
use Maatwebsite\Excel\Facades\Excel;

class ClientTable extends Component
{
    use WithPagination;

    public $clients = [];
    public $page = 1;
    public $lastPage = 1;
    public $perPage = 10;  // Por defecto, 10 elementos por página

    public $company_name = '';
    public $municipality = '';
    public $contract_state = '';

    protected $queryString = ['company_name', 'municipality', 'contract_state', 'page'];

    public function mount()
    {
        $this->loadClients();
    }

    public function updated($propertyName)
    {
        $this->page = 1;
        $this->loadClients();
    }

    #[On('refreshTable')]
    public function loadClients()
    {
        // Llamada a la API con la página actual
        $apiUrl = route('api.clients.index');
        $response = Http::withToken(session('auth_token'))->get($apiUrl, [
            'company_name' => $this->company_name,
            'municipality' => $this->municipality,
            'contract_state' => $this->contract_state,
            'page' => $this->page,
        ]);

        // Asignar los datos de los clientes y la información de paginación
        if ($data = $response->json()) {
            $this->clients = $data['data'];
            $this->page = $data['meta']['current_page'];
            $this->lastPage = $data['meta']['last_page'];
        }
    }

    public function goToPage($page)
    {
        $this->page = $page;
        $this->loadClients();
    }

    public function render()
    {
        return view('livewire.client-table', [
            'clients' => $this->clients,
        ]);
    }

    public function editClient($clientId)
    {
        // Emitir un evento con el clientId
        $this->dispatch('editClient', $clientId);
    }

    public function openAppointmentModal($clientId)
    {
        // Emitir un evento con el clientId
        $this->dispatch('openAppointmentModal', $clientId);
    }

    public function exportCSV()
    {
        return Excel::download(new ClientsExport([
                'company_name' => $this->company_name,
                'municipality' => $this->municipality,
                'contract_state' => $this->contract_state,
            ]), 'clientes.csv');
    }

    public function exportXLSX()
    {
        return Excel::download(new ClientsExport([
                'company_name' => $this->company_name,
                'municipality' => $this->municipality,
                'contract_state' => $this->contract_state,
            ]), 'clientes.xlsx');
    }
}
