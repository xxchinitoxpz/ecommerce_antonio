<x-layouts.app :title="__('Categories | edit')">
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Panel</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.categories.index') }}">Categorias</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 px-6 py-8 shadow-lg rounded-lg">
        <form action="{{ route('admin.categories.update' ,$category) }}" method="POST">
            @csrf
            @method('PUT')
            <flux:input class="" label="Nombre de categoria" name="name" value="{{old('name', $category->name)}}" placeholder="Ingrese el nombre de la categoria" />

            <div class="flex justify-end mt-4">
                <flux:button type="submit">Modificar</flux:button>
            </div>
        </form>
    </div>
    
</x-layouts.app>
