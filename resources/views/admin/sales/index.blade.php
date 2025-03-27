<x-layouts.app :title="'Ventas'">
    <div class="mb-4 flex justify-between items-center">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Ventas</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <a href="{{ route('admin.sales.create') }}" class="btn btn-blue text-xs">Nueva venta</a>
    </div>

    <div
        class="p-5 relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <table id="table" class="display w-full">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-zinc-700 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 w-10">ID</th>
                    <th class="px-6 py-3">Usuario</th>
                    <th class="px-6 py-3">Tipo de pago</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3 text-right"><span class="sr-only">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->user->name ?? '—' }}</td>
                        <td>{{ $sale->paymentType->name ?? '—' }}</td>
                        <td>S/. {{ number_format($sale->total, 2) }}</td>
                        <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end space-x-1">
                                <!-- Editar -->
                                <a href="{{ route('admin.sales.edit', $sale) }}"
                                    class="btn bg-emerald-800 btn-sm  text-white" title="Editar venta">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>

                                <!-- Detalle -->
                                @if ($sale->voucher)
                                    <a href="{{ asset('storage/' . $sale->voucher->path) }}" target="_blank"
                                        class="btn btn-sm bg-blue-600 text-white" title="Ver boleta">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-sm text-gray-400 italic">Sin boleta</span>
                                @endif


                                <!-- Eliminar -->
                                <form class="delete-form" action="{{ route('admin.sales.destroy', $sale) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm bg-red-700 text-white" title="Eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @push('js')
        <script>
            // Confirmación antes de eliminar
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡Esta acción no se puede deshacer!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts.app>
