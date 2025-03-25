<div class="p-4 bg-gray-800 rounded-lg">
    <h2 class="text-2xl mb-4 px-2">Citas</h2>
    <div class="flex space-x-4 mb-4">
        <input type="date" wire:model.live.debounce.200ms="from_date" class="p-2 bg-gray-700 text-white rounded">
        <input type="date" wire:model.live.debounce.200ms="to_date" class="p-2 bg-gray-700 text-white rounded">
        <input type="text" wire:model.live.debounce.200ms="company_name" placeholder="Filtrar por Razón Social" class="p-2 bg-gray-700 text-white rounded">

{{--        <select wire:model="client_id" class="p-2 bg-gray-700 text-white rounded">--}}
{{--            <option value="">Todos los clientes</option>--}}
{{--            @foreach ($clients as $client)--}}
{{--                <option value="{{ $client->id }}">{{ $client->razon_social }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}

{{--        <select wire:model="state" class="p-2 bg-gray-700 text-white rounded">--}}
{{--            <option value="">Todos los estados</option>--}}
{{--            <option value="pending">Pendiente</option>--}}
{{--            <option value="finished">Finalizado</option>--}}
{{--            <option value="canceled">Cancelado</option>--}}
{{--        </select>--}}
    </div>

    <table class="w-full bg-gray-700 text-white rounded-lg">
        <thead>
        <tr class="bg-gray-900">
            <th class="p-2">Cliente (Razón Social)</th>
            <th class="p-2">Fecha</th>
            <th class="p-2">Reconocimientos Solicitados</th>
            <th class="p-2">Reconocimientos Realizados</th>
            <th class="p-2">Acciones</th>
{{--            <th class="p-2">Estado</th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach ($appointments as $appointment)
            <tr class="border-b border-gray-600">
                <td class="p-2">{{ $appointment['client']['company_name'] }}</td>
                <td class="p-2">{{ \Carbon\Carbon::parse($appointment['date'])->format('d/m/Y H:i') }}</td>
                <td class="p-2 text-center">{{ $appointment['requested_examinations'] }}</td>
                <td class="p-2 text-center">{{ $appointment['performed_examinations'] }}</td>
                <td class="p-2 text-center">
                    @if ($appointment['performed_examinations'] == 0)
                    <button wire:click="editAppointment({{ $appointment['id'] }})" class="hover:cursor-pointer bg-blue-500 hover:bg-blue-700 rounded px-2 py-1">
                        Editar
                    </button>
                    <button wire:click="fillAppointment({{ $appointment['id'] }})" class="hover:cursor-pointer bg-green-500 hover:bg-green-700 rounded px-2 py-1 ml-2">
                        Registrar Reconocimientos
                    </button>
                    @endif
                </td>
{{--                <td class="p-2 text-center">--}}
{{--                    @if ($appointment['state'] === 'pending')--}}
{{--                        <span class="bg-yellow-500 text-black p-1 rounded">Pendiente</span>--}}
{{--                    @elseif ($appointment['state'] === 'finished')--}}
{{--                        <span class="bg-green-500 text-black p-1 rounded">Finalizado</span>--}}
{{--                    @else--}}
{{--                        <span class="bg-red-500 text-black p-1 rounded">Cancelado</span>--}}
{{--                    @endif--}}
{{--                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
