<x-layouts.app :title="__('Products | Edit')">
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Panel</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('admin.products.index') }}">Productos</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Editar</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 px-6 py-8 shadow-lg rounded-lg">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input label="Nombre del producto" name="name" value="{{ old('name', $product->name) }}" />
                <flux:input label="Precio" name="price" type="number" step="0.01"
                    value="{{ old('price', $product->price) }}" />
                <flux:input label="Descuento" name="discount" type="number" step="0.01"
                    value="{{ old('discount', $product->discount) }}" />
                <flux:input label="Stock" name="stock" type="number" value="{{ old('stock', $product->stock) }}" />

                <!-- Select categoría -->
                <div>
                    <label for="category_id"
                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Categoría</label>
                    <select name="category_id" id="category_id"
                        class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-white">
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select marca -->
                <div>
                    <label for="brand_id"
                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Marca</label>
                    <select name="brand_id" id="brand_id"
                        class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-white">
                        <option value="">Seleccione una marca</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Imagen actual -->


                <!-- Nueva imagen -->
                <div class="col-span-2">
                    <label for="image"
                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Cambiar imagen
                        (opcional)</label>
                    <input type="file" name="image" id="image"
                        class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-zinc-700 border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                </div>
                @if ($product->image)
                    <div class="col-span-2">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Imagen
                            actual</label>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Imagen del producto"
                            class="w-32 h-32 object-cover rounded">
                    </div>
                @endif
            </div>

            <div class="flex justify-end mt-6">
                <flux:button type="submit">Actualizar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
