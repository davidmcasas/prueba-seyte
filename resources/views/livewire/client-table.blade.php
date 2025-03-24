<div>
    <h2 class="text-2xl my-4 px-2">Clientes</h2>
    <table class="min-w-full table-fixed text-left text-sm">
        <thead class="bg-gray-600">
        <tr>
{{--            <th class="px-4 py-2 w-1/7">Código</th>--}}
            <th class="px-4 py-2 w-2/7">
                Razón Social
                <input type="text" wire:model.live.debounce.200ms="company_name" placeholder="Filtrar por Razón Social" class="p-2 border rounded">
            </th>
            <th class="px-4 py-2 w-1/7">CIF</th>
            <th class="px-4 py-2 w-1/7">
                Municipio
                <input type="text" wire:model.live.debounce.200ms="municipality" placeholder="Filtrar por municipio" class="p-2 border rounded">
            </th>
            <th class="px-4 py-2 w-1/7">Inicio</th>
            <th class="px-4 py-2 w-1/7">Expiración</th>
            <th class="px-4 py-2 w-1/7">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr class="even:bg-gray-700">
{{--                <td class="px-4 py-2">{{ $client['code'] }}</td>--}}
                <td class="px-4 py-2">{{ $client['company_name'] }}</td>
                <td class="px-4 py-2">{{ $client['cif'] }}</td>
                <td class="px-4 py-2">{{ $client['municipality'] }}</td>
                <td class="px-4 py-2">{{ $client['contract_start_date'] }}</td>
                <td class="px-4 py-2">{{ $client['contract_end_date'] }}</td>
                <td class="px-4 py-2">
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
    <!-- Paginación -->
    <div class="mt-4 mx-4">
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
