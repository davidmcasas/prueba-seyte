<div>
    @if (session()->has('message'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition.opacity
             class="fixed bottom-10 right-10 bg-green-500 text-white p-4 rounded-lg shadow-md z-50">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             x-transition.opacity
             class="fixed bottom-10 right-10 bg-red-500 text-white p-4 rounded-lg shadow-md z-50">
            {{ session('error') }}
        </div>
    @endif
    @if($isOpen)
        <div class="fixed inset-0 bg-black/85 flex justify-center items-center z-50">
            <div class="bg-gray-900 text-white p-6 rounded-lg shadow-md w-full max-w-lg mx-auto relative">
                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 text-4xl hover:text-gray-200">&times;</button>
                <h2 class="text-xl font-semibold mb-4">{{ $clientId ? ('Editar Cliente: ' . $company_name) : 'Nuevo Cliente' }}</h2>

                <form wire:submit.prevent="submit">
                    <!-- Grid de campos, dividiendo en 2 columnas por fila -->
                    <div class="grid grid-cols-2 gap-4">

                        <!-- Razon Social -->
                        <div class="flex flex-col">
                            <label for="company_name" class="block text-sm font-medium text-gray-300">Razón Social</label>
                            <input type="text" id="company_name" wire:model="company_name" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('company_name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- CIF -->
                        <div>
                            <label for="cif" class="block text-sm font-medium text-gray-300">CIF</label>
                            <input type="text" id="cif" wire:model="cif" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('cif') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-300">Dirección</label>
                            <input type="text" id="address" wire:model="address" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('address') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Municipio -->
                        <div>
                            <label for="municipality" class="block text-sm font-medium text-gray-300">Municipio</label>
                            <input type="text" id="municipality" wire:model="municipality" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('municipality') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Provincia -->
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-300">Provincia</label>
                            <input type="text" id="province" wire:model="province" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('province') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Número de Reconocimientos -->
                        <div>
                            <label for="examinations_included" class="block text-sm font-medium text-gray-300">Reconocimientos Contratados</label>
                            <input type="number" id="examinations_included" wire:model="examinations_included" class="bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('examinations_included') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fecha de Inicio -->
                        <div>
                            <label for="contract_start_date" class="block text-sm font-medium text-gray-300">Fecha de Inicio de Contrato</label>
                            <input type="date" id="contract_start_date" wire:model="contract_start_date" class="w-full bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('contract_start_date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fecha de Expiración -->
                        <div>
                            <label for="contract_end_date" class="block text-sm font-medium text-gray-300">Fecha de Expiración de Contrato</label>
                            <input type="date" id="contract_end_date" wire:model="contract_end_date" class="w-full bg-gray-800 text-white p-2 rounded border border-gray-600">
                            @error('contract_end_date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="mt-4 flex justify-between">
                        <button type="button" wire:click="closeModal" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Cerrar</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            {{ $clientId ? 'Actualizar' : 'Crear' }} Cliente
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif
</div>

