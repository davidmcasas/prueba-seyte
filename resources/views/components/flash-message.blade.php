<div>
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-10 right-10 bg-green-500 text-white p-4 rounded-lg shadow-md z-50">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-10 right-10 bg-red-500 text-white p-4 rounded-lg shadow-md z-50">
            {{ session('error') }}
        </div>
    @endif
</div>
