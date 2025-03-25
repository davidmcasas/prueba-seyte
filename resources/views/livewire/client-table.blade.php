<div class="p-4 bg-gray-800 rounded-lg h-full">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl px-2 text-white">Clientes</h2>

        <div class="flex flex-col space-y-2">
            <div class="flex space-x-2">
                <div class="flex flex-col">
                    <input type="text" wire:model.live.debounce.200ms="company_name" placeholder="Filtrar por Razón Social" class="p-2 bg-gray-700 text-white rounded">
                    <span class="text-xs text-gray-400 mt-1">Razón Social</span>
                </div>
                <div class="flex flex-col">
                    <input type="text" wire:model.live.debounce.200ms="municipality" placeholder="Filtrar por Municipio" class="p-2 bg-gray-700 text-white rounded">
                    <span class="text-xs text-gray-400 mt-1">Municipio</span>
                </div>
            </div>
        </div>

        <div>
            <flux:button icon="plus-circle" wire:click="$dispatch('editClient')"
                         class="bg-blue-500! hover:bg-blue-600! hover:cursor-pointer text-white px-4 py-2 rounded">
                Nuevo Cliente
            </flux:button>
            <flux:button icon="document-arrow-down" wire:click="exportCSV()"
                         class="bg-green-500! hover:bg-green-600! hover:cursor-pointer text-white px-4 py-2 rounded ml-2">
                Exportar CSV
            </flux:button>
            <flux:button icon="document-arrow-down" button wire:click="exportXLSX()"
                         class="bg-green-500! hover:bg-green-600! hover:cursor-pointer text-white px-4 py-2 rounded ml-2">
                Exportar Excel
            </flux:button>
        </div>
    </div>

    <table class="w-full bg-gray-700 text-white rounded-lg text-sm table-auto">
        <thead>
        <tr class="bg-gray-900">
            <th class="p-2 w-2/7">Razón Social</th>
            <th class="p-2 w-1/7">CIF</th>
            <th class="p-2 w-1/7">Municipio</th>
            <th class="p-2 w-1/7">Inicio</th>
            <th class="p-2 w-1/7">Expiración</th>
            <th class="p-2 w-1/7">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr class="border-b border-gray-600">
                <td class="p-2 text-center">{{ $client['company_name'] }}</td>
                <td class="p-2 text-center">{{ $client['cif'] }}</td>
                <td class="p-2 text-center">{{ $client['municipality'] }}</td>
                <td class="p-2 text-center">{{ \Carbon\Carbon::parse($client['contract_start_date'])->format('d/m/Y') }}</td>
                <td class="p-2 text-center">{{ \Carbon\Carbon::parse($client['contract_end_date'])->format('d/m/Y') }}</td>
                <td class="text-center">
                    <button wire:click="editClient({{ $client['id'] }})" class="hover:cursor-pointer bg-blue-500 hover:bg-blue-700 rounded px-2 py-1">
                        Editar
                    </button>
                    <button wire:click="openAppointmentModal({{ $client['id'] }})"
                            class="hover:cursor-pointer bg-green-500 hover:bg-green-700 text-white px-2 py-1 rounded ml-2">
                        Citar
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="mt-4">
        <div class="flex justify-between">
            <!-- Página anterior -->
            <button
                wire:click="goToPage({{ $page - 1 }})"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-800 rounded"
                @if($page == 1) disabled @endif>
                Anterior
            </button>

            <!-- Páginas -->
            <span class="px-4 py-2">
            Página {{ $page }} de {{ $lastPage }}
        </span>

            <!-- Página siguiente -->
            <button
                wire:click="goToPage({{ $page + 1 }})"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-800 rounded"
                @if($page == $lastPage) disabled @endif>
                Siguiente
            </button>
        </div>
    </div>
</div>
