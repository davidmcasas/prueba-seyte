<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="container">
                <livewire:appointment-table />
            </div>
        </div>
    </div>

    <!-- Modal de Crear/Editar Cita -->
    <livewire:create-edit-appointment />
    <!-- Modal de Rellenar Cita -->
    <livewire:fill-appointment />
</x-layouts.app>
