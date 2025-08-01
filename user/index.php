<?php
// inc/db.php: Database connection
include("inc/db.php");

// inc/header.php: Navigation bar with opening <html>, <head>, and <body> tags
include("inc/header.php");

// Fetch all menu items
$query = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $query);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {

        $('#search').on('keyup input', function () {
            let query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: 'search_suggest.php',
                    method: 'POST',
                    data: { query: query },
                    success: function (data) {
                        $('#suggestion-box').html(data).removeClass('hidden');
                    }
                });
            } else {
                $('#suggestion-box').addClass('hidden');
            }
        });

        // When user clicks on suggestion
        $(document).on('click', '.suggestion-item', function () {
            let text = $(this).text();
            $('#search').val(text);
            $('#suggestion-box').addClass('hidden');

            $.ajax({
                url: 'search_item.php',
                method: 'POST',
                data: { query: text },
                success: function (data) {
                    $('#menuItems').html(data);
                }
            });
        });

        // Press Enter = show all matching items
        $('#search').keypress(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                let query = $(this).val();
                $('#suggestion-box').addClass('hidden');

                $.ajax({
                    url: 'search_item.php',
                    method: 'POST',
                    data: { query: query },
                    success: function (data) {
                        $('#menuItems').html(data);
                    }
                });
            }
        });

    });
</script>
<script>
    $(document).ready(function () {
        $('#applyFilters').click(function () {
            let category = $('#categoryFilter').val();
            let price = $('#priceFilter').val();
            $.ajax({
                url: 'filter_items.php',
                method: 'POST',
                data: { type: category, price: price },
                success: function (data) {
                    $('#menuItems').html(data);
                }
            });
        });
    });
</script>
<script src="main.js"></script>
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-orange-50 via-red-50 to-orange-100 py-20">
    <div class="absolute inset-0 bg-black opacity-5"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-8">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mb-6">
                <i class="fas fa-utensils text-white text-3xl"></i>
            </div>
        </div>
        <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
            Welcome to
            <span class="bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                Khana Khazana
            </span>
        </h1>
        <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
            Discover mouth-watering Indian dishes, cooked with love and delivered fresh to your doorstep.
            From traditional favorites to modern twists, we bring authentic flavors to your table.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#menuItems"
                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-utensils mr-2"></i>
                Explore Our Menu
            </a>
            <a href="about.php"
                class="inline-flex items-center px-8 py-4 border-2 border-orange-500 text-orange-600 hover:bg-orange-50 font-semibold rounded-lg transition-all duration-300">
                <i class="fas fa-info-circle mr-2"></i>
                Learn More
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                    <i class="fas fa-clock text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Fast Delivery</h3>
                <p class="text-gray-600">Hot and fresh food delivered to your doorstep within 30 minutes</p>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                    <i class="fas fa-leaf text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Fresh Ingredients</h3>
                <p class="text-gray-600">We use only the finest and freshest ingredients in all our dishes</p>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 rounded-full mb-4">
                    <i class="fas fa-star text-orange-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Authentic Taste</h3>
                <p class="text-gray-600">Traditional recipes passed down through generations for authentic flavors</p>
            </div>
        </div>
    </div>
</section>

<!-- Menu Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Delicious Menu</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Choose from a wide variety of classic and contemporary Indian dishes,
                each prepared with care and authentic spices.
            </p>
        </div>

        <!-- Enhanced Search and Filter Section -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-12 border border-gray-100">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-2 flex items-center">
                    <i class="fas fa-search-plus text-orange-500 mr-3"></i>
                    Find Your Perfect Dish
                </h3>
                <p class="text-gray-600">Search by name, ingredients, or filter by category and price</p>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <!-- Search Box -->
                <div class="xl:col-span-2">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-search mr-2 text-orange-500"></i>Search Dishes
                    </label>
                    <div class="relative">
                        <input type="text" id="search" placeholder="Try: biryani, chicken, paneer, spicy..."
                            autocomplete="off"
                            class="w-full pl-14 pr-4 py-4 rounded-2xl border-2 border-gray-200 focus:outline-none focus:ring-4 focus:ring-orange-100 focus:border-orange-500 bg-white text-gray-900 shadow-sm transition-all duration-300 text-lg font-medium" />
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fas fa-search text-orange-400 text-xl"></i>
                        </div>
                    </div>
                    <!-- Contained Search Results -->
                    <div id="suggestion-box"
                        class="absolute z-50 bg-white border-2 border-orange-200 rounded-2xl shadow-2xl mt-2 hidden max-h-80 overflow-y-auto w-full max-w-2xl">
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="xl:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="categoryFilter" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-filter mr-2 text-orange-500"></i>Category
                        </label>
                        <select id="categoryFilter"
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl bg-white text-gray-900 focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all duration-300 text-lg font-medium">
                            <option value="all">🍽️ All Categories</option>
                            <option value="Veg">🥬 Vegetarian</option>
                            <option value="Non-Veg">🍗 Non-Vegetarian</option>
                        </select>
                    </div>

                    <div>
                        <label for="priceFilter" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-rupee-sign mr-2 text-orange-500"></i>Price Range
                        </label>
                        <select id="priceFilter"
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl bg-white text-gray-900 focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all duration-300 text-lg font-medium">
                            <option value="all">💰 All Prices</option>
                            <option value="0-100">💸 Budget (₹0 - ₹100)</option>
                            <option value="101-200">💵 Value (₹101 - ₹200)</option>
                            <option value="201-500">💎 Premium (₹201 - ₹500)</option>
                            <option value="501-10000">👑 Luxury (₹501+)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Filter Button -->
            <div class="mt-6 flex justify-center">
                <button id="applyFilters"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 text-lg">
                    <i class="fas fa-magic mr-3"></i>
                    Apply Filters
                </button>
            </div>
        </div>

        <!-- Menu Items Grid -->
        <div id="menuItems" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php while ($row = mysqli_fetch_assoc($result)) {
                include('inc/menu_card.php');
            } ?>
        </div>
    </div>
</section>

<?php include("inc/footer.php"); ?>