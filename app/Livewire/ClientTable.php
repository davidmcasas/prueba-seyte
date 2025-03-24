<?php

namespace App\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class ClientTable extends Component
{
    use WithPagination;

    public $clients = [];
    public $page = 1;
    public $lastPage = 1;
    public $perPage = 10;  // Por defecto, 10 elementos por página

    public $company_name = '';
    public $municipality = '';

    protected $queryString = ['company_name', 'municipality', 'page'];

    public function mount()
    {
        $this->loadClients();
    }

    public function updated($propertyName)
    {
        // Esto hace que cuando cualquier filtro cambia, se reinicie la página
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
            'page' => $this->page,
        ]);

        // Procesa la respuesta de la API
        $data = $response->json();

        // Asignar los datos de los clientes y la información de paginación
        $this->clients = $data['data'];
        $this->page = $data['meta']['current_page'];
        $this->lastPage = $data['meta']['last_page'];
    }

    public function goToPage($page)
    {
        // Cambiar de página y recargar los datos
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
}
