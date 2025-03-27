<x-layouts.app :title="__('Payment Types | Editar')">
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Panel</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.paymentTypes.index') }}">Tipos de pago</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 px-6 py-8 shadow-lg rounded-lg">
        <form action="{{ route('admin.paymentTypes.update', $paymentType) }}" method="POST">
            @csrf
            @method('PUT')
            <flux:input name="name" label="Nombre del tipo de pago" value="{{ old('name', $paymentType->name) }}" />
            <div class="flex justify-end mt-4">
                <flux:button type="submit">Actualizar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
