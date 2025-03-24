<div>
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-10 right-10 bg-green-500 text-white p-4 rounded-lg shadow-md z-50">
            {{ session('message') }}
        </div>
    @endif
    @if($isOpen)
        <div class="fixed inset-0 bg-black/85 flex justify-center items-center z-50">
            <div class="bg-gray-900 text-white p-6 rounded-lg shadow-md w-full max-w-lg mx-auto relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-200">&times;</button>

                <h2 class="text-xl font-semibold mb-4">{{ $appointmentId ? 'Editar Cita' : 'Nueva Cita' }} — {{ $companyName }}</h2>
                <h3 class="text-l font-semibold mb-4"><span>CIF: {{ $cif }}</span></h3>

                @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-2 mb-4 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label class="text-sm">Fecha y Hora</label>
                            <input type="datetime-local" wire:model="date" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm">Reconocimientos solicitados</label>
                            <input type="number" wire:model="requested_examinations" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('requested_examinations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

{{--                        <div class="flex flex-col">--}}
{{--                            <label class="text-sm">Exámenes realizados</label>--}}
{{--                            <input type="number" wire:model="performed_examinations" class="bg-gray-800 text-white p-2 rounded border border-gray-600">--}}
{{--                            @error('performed_examinations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror--}}
{{--                        </div>--}}

{{--                        <div class="flex flex-col">--}}
{{--                            <label class="text-sm">Estado</label>--}}
{{--                            <select wire:model="state" class="bg-gray-800 text-white p-2 rounded border border-gray-600">--}}
{{--                                <option value="pending">Pendiente</option>--}}
{{--                                <option value="finished">Finalizado</option>--}}
{{--                                <option value="canceled">Cancelado</option>--}}
{{--                            </select>--}}
{{--                            @error('state') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror--}}
{{--                        </div>--}}
                    </div>

                    <div class="mt-4 flex justify-between">
                        <button type="button" wire:click="closeModal" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cerrar</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            {{ $appointmentId ? 'Actualizar' : 'Crear' }} Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
