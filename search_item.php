<?php
session_start();
include("inc/db.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    $result = mysqli_query($conn, "SELECT * FROM menu_items WHERE name LIKE '%$query%' OR description LIKE '%$query%'");

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
?>
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
                <div class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600 font-semibold text-gray-800 dark:text-gray-100 select-none" title="Items in Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-blue-700 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m4-9l2 9" />
                    </svg>
                    <span>
                        <?php
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
<?php
        }
    } else {
        echo "<p class='text-center col-span-3 text-gray-500 dark:text-gray-400'>No items found matching your search.</p>";
    }
}
?>
