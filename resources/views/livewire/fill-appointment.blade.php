<div>
    <x-flash-message />
    @if($isOpen)
        <div class="fixed inset-0 bg-black/85 flex justify-center items-center z-50">
            <div class="bg-gray-900 text-white p-6 rounded-lg shadow-md w-full max-w-lg mx-auto relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 text-4xl hover:text-gray-200">&times;</button>

                <h2 class="text-xl font-semibold mb-4">Registrar Reconocimientos — {{ $companyName }}</h2>
                <h3 class="text-l font-semibold mb-4"><span>CIF: {{ $cif }}</span></h3>
                <p class="text-red-400 font-semibold mb-4 text-sm">
                    <span>Ten en cuenta que no se podrá modificar la cita tras registrar el número de reconocimientos.</span>
                </p>

                @if (session()->has('message'))
                    <div class="bg-green-500 text-white p-2 mb-4 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <label class="text-sm">Reconocimientos Realizados</label>
                            <input required type="number" wire:model="performed_examinations" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('performed_examinations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between">
                        <button type="button" wire:click="closeModal" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cerrar</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            Finalizar Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
