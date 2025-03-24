<div class="p-4 bg-gray-800 text-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">
        {{ $clientId ? ('Editar Cliente: ' . $company_name) : 'Crear Cliente' }}
    </h2>
    <form wire:submit.prevent="submit">

        <!-- Mensaje de error o éxito -->
        @if (session()->has('message'))
            <div class="text-green-500 mb-4">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="text-red-500 mb-4">{{ session('error') }}</div>
        @endif

        <!-- Grid de campos, dividiendo en 2 columnas por fila -->
        <div class="grid grid-cols-1 sm:grid-cols-4  gap-4">

            <!-- Razon Social -->
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-300">Razón Social</label>
                <input type="text" id="company_name" wire:model="company_name" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- CIF -->
            <div>
                <label for="cif" class="block text-sm font-medium text-gray-300">CIF</label>
                <input type="text" id="cif" wire:model="cif" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('cif') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Dirección -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-300">Dirección</label>
                <input type="text" id="address" wire:model="address" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Municipio -->
            <div>
                <label for="municipality" class="block text-sm font-medium text-gray-300">Municipio</label>
                <input type="text" id="municipality" wire:model="municipality" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('municipality') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Provincia -->
            <div>
                <label for="province" class="block text-sm font-medium text-gray-300">Provincia</label>
                <input type="text" id="province" wire:model="province" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('province') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha de Inicio -->
            <div>
                <label for="contract_start_date" class="block text-sm font-medium text-gray-300">Fecha de Inicio de Contrato</label>
                <input type="date" id="contract_start_date" wire:model="contract_start_date" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('contract_start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha de Expiración -->
            <div>
                <label for="contract_end_date" class="block text-sm font-medium text-gray-300">Fecha de Expiración de Contrato</label>
                <input type="date" id="contract_end_date" wire:model="contract_end_date" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('contract_end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Número de Reconocimientos -->
            <div>
                <label for="examinations_included" class="block text-sm font-medium text-gray-300">Número de Reconocimientos</label>
                <input type="number" id="examinations_included" wire:model="examinations_included" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('examinations_included') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

        </div>

        <!-- Botón de envío -->
        <div class="mt-6">
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                @if ($clientId)
                    Actualizar Cliente
                @else
                    Crear Cliente
                @endif
            </button>
        </div>

    </form>
</div>
