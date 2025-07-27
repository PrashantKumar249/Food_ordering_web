<?php
// inc/db.php: Database connection
include("inc/db.php");

// inc/header.php: Navigation bar with opening <html>, <head>, and <body> tags
include("inc/header.php");

// Fetch all menu items
$query = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $query);
?>

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

                <?php if (isset($_SESSION['user_id'])) { ?>
                <form method="post" action="add_cart.php" class="inline-block">
                    <input type="hidden" name="type" value="remove">
                    <input type="hidden" name="menu_item_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                        -
                    </button>
                </form>
                <?php } else { ?>
                <a href="login.php"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none inline-block text-center">
                     -
                </a>
                <?php } ?>

                <!-- Cart Button (non-functional, for display) -->
                <div class="inline-block">
                    <button
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none cursor-default">
                        <?php
                        // Display the number of items in the cart  by fetching from the database
                        $cart_query = "SELECT quantity as item_count FROM cart_items WHERE user_id = " . $_SESSION['user_id'] . " AND menu_item_id = " . $row['id'];
                        $cart_result = mysqli_query($conn, $cart_query);
                        $cart_data = mysqli_fetch_assoc($cart_result);
                        echo isset($cart_data['item_count']) ? $cart_data['item_count'] : 0;
                        ?>
                    </button>
                </div>

               <?php if (isset($_SESSION['user_id'])) { ?>
                <form method="post" action="add_cart.php" class="inline-block">
                    <input type="hidden" name="type" value="add">
                    <input type="hidden" name="menu_item_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none">
                        +
                    </button>
                </form>
                 <?php } else { ?>
                <a href="login.php"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 focus:outline-none inline-block text-center">
                     +
                </a>
                <?php } ?>

            </div>
        </div>
    <?php } ?>
</div>


<?php include("inc/footer.php"); ?>

</body>

</html>