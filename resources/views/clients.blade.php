<x-layouts.app :title="__('Clientes')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <livewire:client-table />
        </div>
    </div>

    <!-- Modal de Crear/Editar Cliente -->
    <livewire:create-edit-client />
    <!-- Modal de Crear/Editar Cita -->
    <livewire:create-edit-appointment />
</x-layouts.app>
