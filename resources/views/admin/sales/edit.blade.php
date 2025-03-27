<x-layouts.app :title="'Editar Venta #'.$sale->id">
    <div class="grid grid-cols-12 gap-4">
        <!-- 🧾 Detalle de venta -->
        <div class="col-span-12 md:col-span-4 bg-white dark:bg-zinc-800 p-4 rounded shadow border dark:border-zinc-700 flex flex-col">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white border-b">Editar Detalle</h2>

            <div id="cart" class="space-y-2 text-gray-700 dark:text-white flex-1 overflow-y-auto"></div>

            <div class="mt-4 border-t pt-4">
                <p class="text-right font-semibold">Total: S/. <span id="total">0.00</span></p>
            </div>

            <form action="{{ route('admin.sales.update', $sale) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="payment_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Tipo de pago
                    </label>
                    <select name="payment_type_id" id="payment_type_id"
                        class="w-full px-3 py-2 rounded border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white">
                        @foreach ($paymentTypes as $type)
                            <option value="{{ $type->id }}" {{ $sale->payment_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="products_json" id="products_json">
                <button type="submit" class="btn btn-blue w-full mt-4">Actualizar venta</button>
            </form>
        </div>

        <!-- 🛍️ Productos -->
        <div class="col-span-12 md:col-span-8 bg-white dark:bg-zinc-800 p-4 rounded shadow border dark:border-zinc-700 flex flex-col">
            <div class="mb-4">
                <input type="text" id="search" placeholder="Buscar producto..."
                    class="w-full px-4 py-2 rounded border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white"
                    onkeyup="filterProducts()">
            </div>

            <div id="products-container" class="overflow-y-auto max-h-[650px] pr-1 flex-1">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4" id="products-grid">
                    @foreach ($products->where('stock', '>', 0) as $product)
                        <div class="bg-white dark:bg-zinc-700 p-3 rounded shadow hover:scale-105 transition cursor-pointer product-card"
                            data-name="{{ strtolower($product->name) }}"
                            onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price - $product->discount }})">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-44 object-cover rounded mb-2">
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white truncate">{{ $product->name }}</h3>
                            <p class="text-base">
                                <span class="line-through text-red-400">S/. {{ number_format($product->price, 2) }}</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">S/. {{ number_format($product->price - $product->discount, 2) }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        let cart = [];
        const stocks = {
            @foreach ($products as $product)
                {{ $product->id }}: {{ $product->stock + ($sale->products->find($product->id)?->pivot->quantity ?? 0) }},
            @endforeach
        };

        function getProductStock(id) {
            return stocks[id] ?? 0;
        }

        function addToCart(id, name, price) {
            const product = cart.find(p => p.id === id);
            const stock = getProductStock(id);

            if (product) {
                if (product.quantity + 1 > stock) {
                    alert(`No puedes agregar más de ${stock} unidades de "${name}"`);
                    return;
                }
                product.quantity += 1;
            } else {
                if (stock < 1) {
                    alert(`"${name}" no tiene stock disponible.`);
                    return;
                }
                cart.push({ id, name, price, quantity: 1 });
            }

            renderCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(p => p.id !== id);
            renderCart();
        }

        function decreaseQuantity(id) {
            const product = cart.find(p => p.id === id);
            if (product) {
                product.quantity -= 1;
                if (product.quantity <= 0) {
                    cart = cart.filter(p => p.id !== id);
                }
                renderCart();
            }
        }

        function renderCart() {
            const cartDiv = document.getElementById('cart');
            cartDiv.innerHTML = '';
            let total = 0;

            cart.forEach(product => {
                const subtotal = product.price * product.quantity;
                total += subtotal;

                cartDiv.innerHTML += `
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="text-sm font-medium">${product.name}</p>
                            <p class="text-xs">S/. ${product.price.toFixed(2)} x ${product.quantity}</p>
                        </div>
                        <div class="text-right space-y-1">
                            <p class="text-sm font-semibold">S/. ${subtotal.toFixed(2)}</p>
                            <div class="flex gap-2 justify-end text-xs">
                                <button onclick="decreaseQuantity(${product.id})" title="Disminuir cantidad">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400 hover:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" />
                                    </svg>
                                </button>
                                <button onclick="removeFromCart(${product.id})" title="Quitar del carrito">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 hover:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            document.getElementById('total').textContent = total.toFixed(2);
            document.getElementById('products_json').value = JSON.stringify(cart);
        }

        function filterProducts() {
            let input = document.getElementById('search').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name;
                card.style.display = name.includes(input) ? 'block' : 'none';
            });
        }

        // Pre-cargar productos de la venta
        window.onload = () => {
            @foreach ($sale->products as $product)
                cart.push({
                    id: {{ $product->id }},
                    name: "{{ $product->name }}",
                    price: {{ $product->pivot->price }},
                    quantity: {{ $product->pivot->quantity }}
                });
            @endforeach

            renderCart();
        };
    </script>
    @endpush
</x-layouts.app>
