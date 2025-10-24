<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facility->name }} - HFfinder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;700&family=Sedan+SC&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; }
        .sedan-font { font-family: 'Sedan SC', serif; }
        .match-orange { background: #F1E8D7; }
        .primary-orange { background: #F46A06; }
        .text-orange { color: #F46A06; }
    </style>
</head>
<body class="bg-white">
    <!-- Header -->
    <header class="bg-white shadow-md py-4 px-8 flex justify-between items-center">
        <h1 class="text-4xl font-bold">HFfinder</h1>
        <nav class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-orange font-bold text-lg flex items-center gap-2">
                🏠 Home
            </a>
            <a href="{{ route('facilities.create') }}" class="text-orange font-bold text-lg">Create facility</a>
            <a href="{{ route('contact') }}" class="text-orange font-bold text-lg flex items-center gap-2">
                📞 ContactUs
            </a>
            <a href="{{ route('login') }}" class="text-orange font-bold text-lg flex items-center gap-2">
                👤 Login
            </a>
        </nav>
    </header>

    <!-- Hero Image -->
   <div class="relative">
        <img src="{{ asset($facility->image) }}" alt="{{ $facility->name }}"
             class="w-full h-[500px] object-cover rounded-b-[40px]">
    </div>


    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-8 py-12">
        <!-- Facility Info Card -->
        <div class="match-orange rounded-3xl shadow-2xl p-12 mb-16 -mt-32 relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-4xl font-bold mb-2">{{ $facility->name }}</h2>
                    <div class="flex items-center gap-2">
                        @for($i = 0; $i < 4; $i++)
                            <span class="text-orange text-2xl">⭐</span>
                        @endfor
                        <span class="text-xl ml-2">4 Stars</span>
                    </div>
                </div>
                <a href="{{ route('coffee-shops') }}" class="text-orange font-bold text-xl hover:underline">
                    ← Back to Coffee Shops
                </a>
            </div>

            <hr class="border-orange border-2 mb-6">

            <h3 class="text-3xl font-bold text-orange mb-4">Restaurant description</h3>

            <p class="sedan-font text-2xl leading-relaxed text-gray-800">
                {{ $facility->description ?? 'Soy asian table restaurant is a restaurant located in the heart of kimihurura it was brought by japanese to bring the taste of asian food in africa.' }}
            </p>
        </div>

        <!-- Services Section -->
        <div class="mb-16">
            <h3 class="text-4xl font-bold text-gray-900 mb-8">Services we offer</h3>
            <hr class="border-gray-900 border-2 mb-12">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($facility->services as $service)
                    <div class="match-orange border-[10px] border-[#F1E8D7] rounded-2xl p-8 shadow-lg flex items-center justify-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $service->name }}</span>
                    </div>
                @empty
                    <div class="col-span-2 text-center text-gray-500">No services available</div>
                @endforelse
            </div>
        </div>

        <!-- Explore Menu Button -->
        <div class="text-center mb-12">
            <button onclick="document.getElementById('menu-section').scrollIntoView({behavior: 'smooth'})"
                    class="primary-orange text-white text-4xl font-bold px-16 py-6 rounded-2xl shadow-2xl hover:bg-orange-600 transition">
                Explore our menu
            </button>
        </div>

        <!-- Menu Section -->
        <div id="menu-section" class="mb-16">
            <!-- Category Filter -->
            <div class="match-orange rounded-xl p-4 mb-8 flex flex-wrap justify-center gap-4 shadow-lg">
                <button onclick="filterMenu('all')"
                        class="category-btn active px-6 py-3 rounded-lg font-bold text-xl transition hover:bg-white">
                    All
                </button>
                <button onclick="filterMenu('Coffee drinks')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-xl transition hover:bg-white">
                    Coffee drinks
                </button>
                <button onclick="filterMenu('Main dishes')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-xl transition hover:bg-white">
                    Main dishes
                </button>
                <button onclick="filterMenu('snacks')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-xl transition hover:bg-white">
                    snacks
                </button>
                <button onclick="filterMenu('soft drinks')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-xl transition hover:bg-white">
                    soft drinks
                </button>
                <button onclick="filterMenu('alcoholic drinks')"
                        class="category-btn px-6 py-3 rounded-lg font-bold text-xl transition hover:bg-white">
                    alcoholic drinks
                </button>
            </div>

            <!-- Shopping Cart Icon -->
            <div class="fixed top-32 right-8 z-50">
                <div class="relative">
                    <button onclick="toggleCart()"
                            class="match-orange w-16 h-16 rounded-full shadow-2xl flex items-center justify-center border border-gray-900 hover:scale-110 transition">
                        🛒
                    </button>
                    <div class="absolute -top-2 -right-2 bg-white border border-gray-900 rounded-full w-8 h-8 flex items-center justify-center">
                        <span id="cart-count" class="text-orange font-bold text-lg">0</span>
                    </div>
                </div>
            </div>

            <!-- Menu Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="menu-items-container">
                @forelse($facility->menuItems as $item)
                    <div class="menu-item bg-white rounded-xl shadow-lg overflow-hidden" data-category="{{ $item->category }}">
                        <img src="{{ asset($item->image ?? 'images/default-menu.jpg') }}"
                             alt="{{ $item->name }}"
                             class="w-full h-48 object-cover">

                        <div class="p-6">
                            <h4 class="text-2xl font-bold mb-4">{{ $item->name }}</h4>

                            <div class="flex items-center gap-4 mb-4">
                                <span class="text-lg font-bold">QTY:</span>
                                <button onclick="decreaseQty({{ $item->id }})"
                                        class="text-3xl font-bold hover:text-orange">-</button>
                                <span id="qty-{{ $item->id }}" class="text-3xl font-bold">1</span>
                                <button onclick="increaseQty({{ $item->id }})"
                                        class="text-3xl font-bold hover:text-orange">+</button>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="primary-orange text-white rounded-xl py-3 text-center">
                                    <span class="text-xl font-bold">{{ number_format($item->price) }} RWF</span>
                                </div>
                                <button onclick="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})"
                                        class="primary-orange text-white rounded-xl py-3 text-xl font-bold hover:bg-orange-600 transition">
                                    Add to order
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 text-xl py-12">
                        No menu items available
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-4 mt-12">
                <button class="text-orange text-4xl font-bold hover:scale-110 transition">←</button>
                @for($i = 1; $i <= 4; $i++)
                    <button class="match-orange border border-gray-900 w-14 h-14 rounded-full flex items-center justify-center text-2xl font-bold hover:bg-orange-600 hover:text-white transition">
                        {{ $i }}
                    </button>
                @endfor
                <button class="text-orange text-4xl font-bold hover:scale-110 transition">→</button>
            </div>
        </div>

        <!-- Gallery Section -->
        <div class="mb-16">
            <h3 class="text-4xl font-bold text-gray-900 mb-8">Our Gallery</h3>
            <hr class="border-gray-900 border-2 mb-12">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($facility->gallery as $image)
                    <div class="match-orange border-[10px] border-[#F1E8D7] rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ asset($image->image) }}"
                             alt="Gallery"
                             class="w-full h-64 object-cover rounded-xl">
                    </div>
                @empty
                    <div class="col-span-2 text-center text-gray-500">No gallery images available</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-gray-900 py-8 text-center">
        <p class="text-xl text-gray-900">Copyright@ 2022 Design by B.Moise</p>
    </footer>

    <!-- Cart Modal (Hidden by default) -->
    <div id="cart-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-3xl font-bold">Your Order</h3>
                <button onclick="toggleCart()" class="text-4xl hover:text-orange">×</button>
            </div>

            <div id="cart-items" class="space-y-4 mb-6">
                <!-- Cart items will be inserted here -->
            </div>

            <div class="border-t pt-4">
                <div class="flex justify-between items-center text-2xl font-bold mb-6">
                    <span>Total:</span>
                    <span id="cart-total" class="text-orange">0 RWF</span>
                </div>
                <button class="primary-orange text-white w-full py-4 rounded-xl text-2xl font-bold hover:bg-orange-600 transition">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    </div>

    <script>
        let cart = {};
        let quantities = {};

        function filterMenu(category) {
            const items = document.querySelectorAll('.menu-item');
            const buttons = document.querySelectorAll('.category-btn');

            buttons.forEach(btn => btn.classList.remove('active', 'text-orange', 'bg-white'));
            event.target.classList.add('active', 'text-orange', 'bg-white');

            items.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function increaseQty(itemId) {
            const qtyEl = document.getElementById(`qty-${itemId}`);
            let qty = parseInt(qtyEl.textContent);
            qtyEl.textContent = ++qty;
        }

        function decreaseQty(itemId) {
            const qtyEl = document.getElementById(`qty-${itemId}`);
            let qty = parseInt(qtyEl.textContent);
            if (qty > 1) {
                qtyEl.textContent = --qty;
            }
        }

        function addToCart(itemId, itemName, itemPrice) {
            const qty = parseInt(document.getElementById(`qty-${itemId}`).textContent);

            if (cart[itemId]) {
                cart[itemId].quantity += qty;
            } else {
                cart[itemId] = {
                    name: itemName,
                    price: itemPrice,
                    quantity: qty
                };
            }

            updateCartDisplay();

            // Reset quantity to 1
            document.getElementById(`qty-${itemId}`).textContent = '1';
        }

        function removeFromCart(itemId) {
            delete cart[itemId];
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartCount = document.getElementById('cart-count');
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');

            let totalItems = 0;
            let totalPrice = 0;
            let cartHTML = '';

            for (const [id, item] of Object.entries(cart)) {
                totalItems += item.quantity;
                totalPrice += item.price * item.quantity;

                cartHTML += `
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-bold text-lg">${item.name}</h4>
                            <p class="text-gray-600">Qty: ${item.quantity} × ${item.price.toLocaleString()} RWF</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-orange">${(item.price * item.quantity).toLocaleString()} RWF</span>
                            <button onclick="removeFromCart(${id})" class="text-red-600 hover:text-red-800 text-2xl">🗑️</button>
                        </div>
                    </div>
                `;
            }

            cartCount.textContent = totalItems;
            cartItems.innerHTML = cartHTML || '<p class="text-center text-gray-500">Your cart is empty</p>';
            cartTotal.textContent = totalPrice.toLocaleString() + ' RWF';
        }

        function toggleCart() {
            const modal = document.getElementById('cart-modal');
            modal.classList.toggle('hidden');
        }

        // Initialize first category as active
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.category-btn').classList.add('text-orange', 'bg-white');
        });
    </script>
</body>
</html>
