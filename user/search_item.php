<?php
include("../include/db.php");
session_start();

$query = isset($_POST['query']) ? trim($_POST['query']) : '';
$sql = "SELECT * FROM menu_items WHERE name LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$like = "%$query%";
$stmt->bind_param('ss', $like, $like);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Start with the same grid wrapper as index.php
if ($result->num_rows > 0) {
    // echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">';
    while ($row = $result->fetch_assoc()) {
        include '../include/menu_card.php'; // outputs a card
    }
    echo '</div>';
} else {
    echo '<div class="text-center text-gray-500 text-xl py-12 col-span-full">No items found.</div>';
}
?>
