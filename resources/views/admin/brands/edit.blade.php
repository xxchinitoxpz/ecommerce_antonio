<x-layouts.app :title="__('Brands | edit')">
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Panel</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.brands.index') }}">Marcas</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 px-6 py-8 shadow-lg rounded-lg">
        <form action="{{ route('admin.brands.update' ,$brand) }}" method="POST">
            @csrf
            @method('PUT')
            <flux:input class="" label="Nombre de marca" name="name" value="{{old('name', $brand->name)}}" placeholder="Ingrese el nombre de la marca" />

            <div class="flex justify-end mt-4">
                <flux:button type="submit">Modificar</flux:button>
            </div>
        </form>
    </div>
    
</x-layouts.app>
