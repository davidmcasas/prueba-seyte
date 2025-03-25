<div class="p-4 bg-gray-800 rounded-lg h-full">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-3xl px-2 text-white">Citas</h2>

        <div class="flex flex-col space-y-2">
            <div class="flex space-x-2">
                @if(!auth()->user()->isMedic())
                <div class="flex flex-col">
                    <input type="date" wire:model.live.debounce.200ms="from_date" class="p-2 bg-gray-700 text-white rounded">
                    <span class="text-xs text-gray-400 mt-1">Desde</span>
                </div>
                <div class="flex flex-col">
                    <input type="date" wire:model.live.debounce.200ms="to_date" class="p-2 bg-gray-700 text-white rounded">
                    <span class="text-xs text-gray-400 mt-1">Hasta</span>
                </div>
                @endif
                <div class="flex flex-col">
                    <input type="text" wire:model.live.debounce.200ms="company_name" placeholder="Filtrar por Razón Social" class="p-2 bg-gray-700 text-white rounded">
                    <span class="text-xs text-gray-400 mt-1">Razón Social</span>
                </div>
            </div>
        </div>

        <div>
            @if(!auth()->user()->isMedic())
            <flux:button icon="document-arrow-down" wire:click="exportCSV()"
                    class="bg-green-500! hover:bg-green-600! hover:cursor-pointer text-white px-4 py-2 rounded">
                Exportar CSV
            </flux:button>
            <flux:button icon="document-arrow-down" button wire:click="exportXLSX()"
                    class="bg-green-500! hover:bg-green-600! hover:cursor-pointer text-white px-4 py-2 rounded ml-2">
                Exportar Excel
            </flux:button>
            @endif
        </div>
    </div>

    <table class="w-full bg-gray-700 text-white text-sm table-auto">
        <thead>
        <tr class="bg-gray-900">
            <th class="p-2">Cliente (Razón Social)</th>
            <th class="p-2">Fecha</th>
            <th class="p-2">Reconocimientos Solicitados</th>
            <th class="p-2">Reconocimientos Realizados</th>
            <th class="p-2">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($appointments as $appointment)
            <tr class="border-b border-gray-600">
                <td class="p-2 text-center">{{ $appointment['client']['company_name'] }}</td>
                <td class="p-2 text-center">{{ \Carbon\Carbon::parse($appointment['date'])->format('d/m/Y H:i') }}</td>
                <td class="p-2 text-center">{{ $appointment['requested_examinations'] }}</td>
                <td class="p-2 text-center">{{ $appointment['performed_examinations'] }}</td>
                <td class="text-center">
                    @if ($appointment['performed_examinations'] == 0)
                        @if(!auth()->user()->isMedic())
                        <button wire:click="editAppointment({{ $appointment['id'] }})" class="hover:cursor-pointer bg-blue-500 hover:bg-blue-700 rounded px-2 py-1">
                            Editar
                        </button>
                        @endif
                        @if (auth()->user()->isMedic() || auth()->user()->isAdmin())
                        <button wire:click="fillAppointment({{ $appointment['id'] }})" class="hover:cursor-pointer bg-green-500 hover:bg-green-700 rounded px-2 py-1 ml-2">
                            Registrar Reconocimientos
                        </button>
                        @endif
                    @endif
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
                class="px-4 py-2 text-white bg-gray-700 hover:bg-gray-800 rounded"
                @if($page == 1) disabled @endif>
                Anterior
            </button>

            <!-- Páginas -->
            <span class="px-4 py-2 text-white">
                @if (empty($appointments))
                    Sin datos para mostrar
                @else
                    Página {{ $page }} de {{ $lastPage }}
                @endif
            </span>

            <!-- Página siguiente -->
            <button
                wire:click="goToPage({{ $page + 1 }})"
                class="px-4 py-2 text-white bg-gray-700 hover:bg-gray-800 rounded"
                @if($page == $lastPage) disabled @endif>
                Siguiente
            </button>
        </div>
    </div>
</div>
