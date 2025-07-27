<?php
include "inc/db.php";

$type = $_POST['type'] ?? 'all';
$priceRange = $_POST['price'] ?? 'all';

$conditions = [];

if ($type === 'Veg') {
    $conditions[] = 'category = "Veg"';
} elseif ($type === 'Non-Veg') {
    $conditions[] = 'category = "Non-Veg"';
}

// Handle price range
if ($priceRange !== 'all') {
    $parts = explode('-', $priceRange);
    if (count($parts) == 2) {
        $min = (int)$parts[0];
        $max = (int)$parts[1];
        $conditions[] = "price BETWEEN $min AND $max";
    }
}

$where_sql = '';
if (!empty($conditions)) {
    $where_sql = 'WHERE ' . implode(' AND ', $conditions);
}

$query = "SELECT * FROM menu_items $where_sql ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $name = htmlspecialchars($row['name']);
        $image = htmlspecialchars($row['image']);
        $price = $row['price'];
        $desc = htmlspecialchars($row['description']);
        $category = htmlspecialchars($row['category']);

        echo '<div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-4">';
        echo '    <a href="#">';
        echo '        <img class="rounded-t-lg w-full h-40 object-cover mb-4" src="images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" />';
        echo '    </a>';
        echo '    <div>';
        echo '        <a href="#">';
        echo '            <div class="flex items-baseline gap-2 mb-2">';
        echo '                <h5 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">' . htmlspecialchars($row['name']) . '</h5>';
        echo '                <span class="text-sm text-gray-500 dark:text-gray-300 font-medium px-2 py-1 rounded border" style="border-width:2px; border-style:solid; border-color:' . ($row['category'] === 'Non-Veg' ? '#dc2626' : '#16a34a') . '; color:' . ($row['category'] === 'Non-Veg' ? '#dc2626' : '#16a34a') . ';">' . htmlspecialchars($row['category']) . '</span>';
        echo '            </div>';
        echo '        </a>';
        echo '        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">' . htmlspecialchars($row['description']) . '</p>';
        echo '        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">' . htmlspecialchars("₹ " . intval($row['price'])) . '</p>';
        echo '        <div class="flex items-center gap-2 mt-2">';
        // Remove Button
        if (isset($_SESSION['user_id'])) {
            echo '            <form method="post" action="add_cart.php" class="inline-block">';
            echo '                <input type="hidden" name="type" value="remove">';
            echo '                <input type="hidden" name="menu_item_id" value="' . $row['id'] . '">';
            echo '                <input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">';
            echo '                <input type="hidden" name="quantity" value="1">';
            echo '                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none" title="Remove from Cart">&minus;</button>';
            echo '            </form>';
        } else {
            echo '            <a href="login.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none inline-block text-center" title="Login to remove from Cart">&minus;</a>';
        }
        // Cart Icon & Count
            $cart_count = $cart_data['item_count'] ?? 0;
        echo '                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-blue-700 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m5-9v9m4-9v9m4-9l2 9" />';
        echo '                </svg>';
        echo '                <span>';
        // Display the number of items in the cart by fetching from the database
        $cart_count = 0;
        if (isset($_SESSION['user_id'])) {
            $cart_query = "SELECT quantity as item_count FROM cart_items WHERE user_id = " . intval($_SESSION['user_id']) . " AND menu_item_id = " . intval($row['id']);
            $cart_result = mysqli_query($conn, $cart_query);
            $cart_data = mysqli_fetch_assoc($cart_result);
            $cart_count = isset($cart_data['item_count']) ? $cart_data['item_count'] : 0;
        }
        echo $cart_count;
        echo '                </span>';
        echo '                <span class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-300">in cart</span>';
        echo '            </div>';
        // Add Button
        if (isset($_SESSION['user_id'])) {
            echo '            <form method="post" action="add_cart.php" class="inline-block">';
            echo '                <input type="hidden" name="type" value="add">';
            echo '                <input type="hidden" name="menu_item_id" value="' . $row['id'] . '">';
            echo '                <input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">';
            echo '                <input type="hidden" name="quantity" value="1">';
            echo '                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none" title="Add to Cart">+</button>';
        } else {
            echo '            <a href="login.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 focus:outline-none inline-block text-center" title="Login to add to Cart">+</a>';
        }
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
} else {
    echo "<p class='text-center text-gray-600 dark:text-gray-400'>No items found.</p>";
}
?>
