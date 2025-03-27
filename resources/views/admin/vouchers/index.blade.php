<x-layouts.app :title="'Vouchers Generados'">
    <div class="mb-4 flex justify-between items-center">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Vouchers</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <div class="p-5 bg-white dark:bg-zinc-800 shadow-md sm:rounded-lg border border-gray-200 dark:border-zinc-700">
        <table id="table" class="display w-full">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-zinc-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Venta</th>
                    <th class="px-4 py-2">Cliente</th>
                    <th class="px-4 py-2">Fecha</th>
                    <th class="px-4 py-2"><span class="sr-only">Accion</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->id }}</td>
                        <td>#{{ $voucher->sale->id }}</td>
                        <td>{{ $voucher->sale->user->name ?? '—' }}</td>
                        <td>{{ $voucher->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex items-center justify-end space-x-1">
                                <a href="{{ asset('storage/' . $voucher->path) }}" target="_blank" title="Ver boleta"
                                class="btn btn-sm bg-blue-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>

                            <!-- Descargar -->
                            <a href="{{ asset('storage/' . $voucher->path) }}" download title="Descargar boleta"
                                class="btn btn-sm bg-green-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                </svg>
                            </a> 
                            </div>
                            <!-- Ver -->
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts.app>
