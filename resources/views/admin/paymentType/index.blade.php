<x-layouts.app :title="__('Payment Types')">
    <div class="mb-4 flex justify-between items-center">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.paymentTypes.index') }}">Tipos de pago</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <a href="{{ route('admin.paymentTypes.create') }}" class="btn btn-blue text-xs">Nuevo</a>
    </div>

    <div class="p-5 relative overflow-x-auto shadow-md sm:rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <table id="table" class="display w-full">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-zinc-700 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 w-10">ID</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3"><span class="sr-only">Acciones</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paymentType as $type)
                    <tr>
                        <td>{{ $type->id }}</td>
                        <td>{{ $type->name }}</td>
                        <td>
                            <div class="flex items-center justify-end space-x-1">
                                <a href="{{ route('admin.paymentTypes.edit', $type) }}" class="btn bg-emerald-800 btn-sm text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                                <form class="delete-form" action="{{ route('admin.paymentTypes.destroy', $type) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn bg-red-700 btn-sm text-white">
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
            // Confirmación con SweetAlert
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '¡Sí, bórralo!',
                        cancelButtonText: 'Cancelar'
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts.app>
