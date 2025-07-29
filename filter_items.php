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
        include 'inc/menu_card.php'; // Include the card template for each item
    }

    echo '</div>'; // ✅ Close the grid
} else {
    echo "<p class='text-center text-gray-600 dark:text-gray-400'>No items found.</p>";
}
?>
