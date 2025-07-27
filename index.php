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
<script src="https://cdn.tailwindcss.com"></script>

<!-- ✅ Hero / Welcome Section -->
<div class="w-full bg-gray-100 dark:bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-4">Welcome to Khana Khazana 🍽️</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
            Discover mouth-watering Indian dishes, cooked with love and delivered fresh to your doorstep.
        </p>
        <a href="#menuItems"
            class="inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 px-5 rounded-lg shadow-md transition">
            Explore Our Menu
        </a>
    </div>
</div>

<!-- ✅ Menu Section Heading -->
<div class="w-full px-4 py-6">
    <div class="max-w-7xl mx-auto text-center mb-8">
        <h2 class="text-3xl font-semibold text-gray-800">
            Our Delicious Menu
        </h2>
        <p class="text-gray-600 mt-2">
            Choose from a wide variety of classic and contemporary Indian dishes.
        </p>
    </div>
</div>

<!-- Search Box -->
<div class="max-w-7xl mx-auto my-10 flex flex-col md:flex-row items-center justify-between gap-4">
    <!-- Search Box -->
    <div class="w-full md:w-2/3 relative">
        <input type="text" id="search" placeholder="Search food..." autocomplete="off"
            class="w-full px-5 py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm transition" />
        <div id="suggestion-box"
            class="absolute left-0 right-0 z-20 bg-white border border-gray-200 dark:border-gray-700 rounded-b-lg shadow-lg mt-1 hidden">
        </div>
    </div>
    <!-- Filter Buttons -->
    <!-- Advanced Filter Options -->
    <div class="w-full md:w-1/3 flex flex-col md:flex-row items-center gap-4">
        <!-- Category Filter -->
        <div>
            <label for="categoryFilter"
                class="block text-sm font-medium text-gray-700 dark:text-gray-900 mb-1">Category</label>
            <select id="categoryFilter"
                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                <option value="all">All</option>
                <option value="Veg">Veg</option>
                <option value="Non-Veg">Non-Veg</option>
            </select>
        </div>
        <!-- Price Filter -->
        <div>
            <label for="priceFilter"
                class="block text-sm font-medium text-gray-700 dark:text-gray-900 mb-1">Price</label>
            <select id="priceFilter"
                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                <option value="all">All</option>
                <option value="0-100">₹0 - ₹100</option>
                <option value="101-200">₹101 - ₹200</option>
                <option value="201-500">₹201 - ₹500</option>
                <option value="501-10000">₹501+</option>
            </select>
        </div>
        <!-- Filter Button -->
        <button id="applyFilters"
            class="bg-blue-700 hover:bg-blue-800 text-white font-medium py-2 px-5 rounded-lg shadow-md transition mt-4 md:mt-6">
            Apply Filters
        </button>
    </div>
</div>

<!-- ✅ Menu Items Grid -->
<div id="menuItems" class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-4">
            <a href="#">
                <img class="rounded-t-lg w-full h-40 object-cover mb-4"
                    src="images/<?php echo htmlspecialchars($row['image']); ?>"
                    alt="<?php echo htmlspecialchars($row['name']); ?>" />
            </a>
            <div>
                <a href="#">
                    <div class="flex items-baseline gap-2 mb-2">
                        <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            <?php echo htmlspecialchars($row['name']); ?>
                        </h5>
                        <span class="text-sm text-gray-500 dark:text-gray-300 font-medium px-2 py-1 rounded border"
                            style="border-width:2px; border-style:solid; border-color:<?php echo ($row['category'] === 'Non-Veg') ? '#dc2626' : '#16a34a'; ?>; color:<?php echo ($row['category'] === 'Non-Veg') ? '#dc2626' : '#16a34a'; ?>">
                            <?php echo htmlspecialchars($row['category']); ?>
                        </span>
                    </div>
                </a>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                    <?php echo htmlspecialchars($row['description']); ?>
                </p>
                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                    <?php echo htmlspecialchars("₹ " . intval($row['price'])); ?>
                </p>

                <div class="flex items-center gap-2 mt-2">
                    <!-- Remove Button -->
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <form method="post" action="add_cart.php" class="inline-block">
                            <input type="hidden" name="type" value="remove">
                            <input type="hidden" name="menu_item_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none"
                                title="Remove from Cart">
                                &minus;
                            </button>
                        </form>
                    <?php } else { ?>
                        <a href="login.php"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none inline-block text-center"
                            title="Login to remove from Cart">
                            &minus;
                        </a>
                    <?php } ?>

                    <!-- Cart Icon & Count -->
                    <div class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600 font-semibold text-gray-800 dark:text-gray-100 select-none"
                        title="Items in Cart">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-blue-700 dark:text-blue-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m4-9l2 9" />
                        </svg>
                        <span>
                            <?php
                            // Display the number of items in the cart by fetching from the database
                            $cart_count = 0;
                            if (isset($_SESSION['user_id'])) {
                                $cart_query = "SELECT quantity as item_count FROM cart_items WHERE user_id = " . intval($_SESSION['user_id']) . " AND menu_item_id = " . intval($row['id']);
                                $cart_result = mysqli_query($conn, $cart_query);
                                $cart_data = mysqli_fetch_assoc($cart_result);
                                $cart_count = isset($cart_data['item_count']) ? $cart_data['item_count'] : 0;
                            }
                            echo $cart_count;
                            ?>
                        </span>
                        <span class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-300">in cart</span>
                    </div>

                    <!-- Add Button -->
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <form method="post" action="add_cart.php" class="inline-block">
                            <input type="hidden" name="type" value="add">
                            <input type="hidden" name="menu_item_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none"
                                title="Add to Cart">
                                +
                            </button>
                        </form>
                    <?php } else { ?>
                        <a href="login.php"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none inline-block text-center"
                            title="Login to add to Cart">
                            +
                        </a>
                    <?php } ?>
                </div>

            </div>
        </div>
    <?php } ?>
</div>


<?php include("inc/footer.php"); ?>
</body>

</html>