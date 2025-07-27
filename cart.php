<?php
include 'inc/db.php';
include 'inc/header.php';

$query = $conn->prepare('SELECT * FROM cart_items WHERE user_id = ?');
$query->bind_param('i', $_SESSION['user_id']);
$query->execute();
$result = $query->get_result();
if ($result->num_rows === 0) {
    echo "<p class='text-center text-gray-600'>Your cart is empty.</p>";
} else {
    echo "<div class='max-w-7xl mx-auto px-4 py-6'>";
    echo "<h2 class='text-3xl font-semibold text-gray-800 mb-4'>Your Cart</h2>";
    echo "<table class='min-w-full bg-white rounded-lg shadow-md'>";
    echo "<thead class='bg-gray-200 dark:bg-gray-700'>";
    echo "<tr>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider'>Item</th>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider'>Price</th>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider'>Quantity</th>";
    echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider'>Total</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $total = 0;
    while ($row = $result->fetch_assoc()) {
        $menu_item_id = $row['menu_item_id'];
        $menu_query = $conn->prepare('SELECT name, price FROM menu_items WHERE id = ?');
        $menu_query->bind_param('i', $menu_item_id);
        $menu_query->execute();
        $menu_result = $menu_query->get_result();
        $menu_item = $menu_result->fetch_assoc();
        $total += $menu_item['price'] * $row['quantity'];
        $item_total = $menu_item['price'] * $row['quantity'];

        echo "<tr class='border-b dark:border-gray-700'>";
        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($menu_item['name']) . "</td>";
        echo "<td class='px-6 py-4 whitespace-nowrap'>₹ " . htmlspecialchars($menu_item['price']) . "</td>";
        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row['quantity']) . "</td>";
        echo "<td class='px-6 py-4 whitespace-nowrap'>₹ " . htmlspecialchars($item_total) . "</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

    echo "<div class='mt-4 flex justify-end'>";
    echo "<h3 class='text-xl font-semibold'>Total: ₹ " . htmlspecialchars($total) . "</h3>";
    echo "</div>";

    // Checkout button
    echo "<div class='mt-4 flex justify-end'>";
    echo "<form action='checkout.php' method='post'>";
    echo "<input type='hidden' name='total' value='" . htmlspecialchars($total) . "'>";
    echo "<button type='submit' class='inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300'>Checkout</button>";
    echo "</form>";
    echo "</div>";
}
?>