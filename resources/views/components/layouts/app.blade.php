<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>

    @stack('js')

    <script>
        table = new DataTable('#table', {
            responsive: true,
            order: [
                [0, 'desc']
            ], // Ordenar por la primera columna (índice 0) de forma descendente
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
                paginate: {
                    first: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 19l-7-7 7-7M20 19l-7-7 7-7" />
        </svg>`,
                    previous: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>`,
                    next: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>`,
                    last: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5l7 7-7 7M4 5l7 7-7 7" />
        </svg>`
                }

            }
        });
    </script>






    @if (@session('swal'))
        <script>
            swal.fire(@json(session('swal')));
        </script>
    @endif
</x-layouts.app.sidebar>
