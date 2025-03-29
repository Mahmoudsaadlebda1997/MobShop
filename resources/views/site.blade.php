<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MobShop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

<!-- Navbar -->
<nav class="bg-blue-600 shadow-md">
    <div class="container mx-auto flex items-center justify-between py-4 px-6">
        <a href="#" class="text-2xl font-bold text-white">MobShop</a>
        <div class="hidden md:flex space-x-6">
            <a href="#" class="text-white hover:text-gray-200">Home</a>
            <a href="#" class="text-white hover:text-gray-200">Shop</a>
            <a href="#" class="text-white hover:text-gray-200">Cart</a>
            <a href="#" class="text-white hover:text-gray-200">Contact</a>
        </div>
        <div class="md:hidden">
            <button class="text-white focus:outline-none">
                ☰
            </button>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<div class="relative bg-blue-500 text-white py-20">
    <div class="container mx-auto text-center">
        <h1 class="text-4xl font-bold mb-4">Find Your Next Smartphone</h1>
        <p class="text-lg mb-6">Browse the latest and greatest mobile phones at unbeatable prices.</p>
        <a href="#phone-list" class="bg-white text-blue-500 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100">Shop Now</a>
    </div>
</div>

<!-- Container -->
<div class="container mx-auto py-12 px-6">

    <!-- Phone List -->
    <h1 class="text-3xl font-bold mb-8 text-center">Available Phones</h1>
    <div id="phone-list" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach ($phones as $phone)
            <div class="border rounded-lg shadow-md p-4 hover:shadow-lg transition duration-300">
                @if ($phone->image)
                    <img src="{{ asset('storage/' . $phone->image) }}" alt="{{ $phone->name }}" class="w-full h-56 object-cover rounded-t-lg">
                @else
                    <div class="w-full h-56 flex items-center justify-center bg-gray-200 rounded-t-lg">
                        <span class="text-gray-500">No Image Available</span>
                    </div>
                @endif
                <div class="p-4">
                    <h2 class="text-xl font-semibold">{{ $phone->name }}</h2>
                    <p class="text-gray-500">{{ $phone->category }}</p>
                    <p class="text-lg font-bold text-blue-500 mt-2">${{ $phone->price }}</p>

                    <div class="mt-4 flex gap-2">
                        <button onclick="showProductDetails({{ $phone->id }})"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            View Details
                        </button>
                        <button onclick="addToCart({{ $phone->id }})"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

<!-- Product Details Modal -->
<div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <img id="modal-image" src="" alt="Phone" class="w-full h-64 object-cover rounded-t-lg" />
        <div class="p-4">
            <h2 id="modal-name" class="text-2xl font-bold mt-2"></h2>
            <p id="modal-category" class="text-gray-500"></p>
            <p id="modal-price" class="text-lg font-bold text-blue-500 mt-2"></p>
            <div class="mt-4 flex gap-2">
                <button id="add-to-cart-btn"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                    Add to Cart
                </button>
                <button onclick="closeModal()"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto text-center">
        <p class="mb-4">&copy; 2025 MobShop. All rights reserved.</p>
        <div class="flex justify-center space-x-4">
            <a href="#" class="hover:text-gray-400">Privacy Policy</a>
            <a href="#" class="hover:text-gray-400">Terms of Service</a>
            <a href="#" class="hover:text-gray-400">Contact</a>
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script>
    // Get phones from Blade
    const phones = @json($phones);

    const cart = [];

    // Show Product Details in Modal
    function showProductDetails(id) {
        const phone = phones.find(p => p.id === id);
        if (phone) {
            const imagePath = phone.image ? `{{ asset('storage/') }}/${phone.image}` : '';
            document.getElementById('modal-image').src = imagePath;
            document.getElementById('modal-name').textContent = phone.name;
            document.getElementById('modal-category').textContent = phone.category;
            document.getElementById('modal-price').textContent = `$${phone.price}`;
            document.getElementById('add-to-cart-btn').onclick = () => addToCart(id);
            document.getElementById('product-modal').classList.remove('hidden');
        }
    }

    // Close Modal
    function closeModal() {
        document.getElementById('product-modal').classList.add('hidden');
    }

    // Add to Cart
    function addToCart(id) {
        const phone = phones.find(p => p.id === id);
        if (phone) {
            const existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ ...phone, quantity: 1 });
            }

            alert(`${phone.name} added to cart!`);
            console.log(cart);
        }
    }
</script>

</body>
</html>
