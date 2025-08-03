<?php
include '../include/db.php'; // Make sure to connect to your DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)$_POST['rating'];
    $user_id = (int)$_POST['user_id'];
    $menu_item_id = (int)$_POST['menu_item_id'];

    if ($rating >= 1 && $rating <= 5) {
        // if rating is there then update else insert
        $check_entry = $conn->prepare("SELECT * FROM rating WHERE user_id = ? AND menu_item_id = ?");
        $check_entry->bind_param("ii", $user_id, $menu_item_id);
        $check_entry->execute();
        if($check_entry->get_result()->num_rows > 0) {
            // Update existing rating
            $stmt = $conn->prepare("UPDATE rating SET rating = ? WHERE user_id = ? AND menu_item_id = ?");
            $stmt->bind_param("iii", $rating, $user_id, $menu_item_id);
        } else {
            // Insert new rating
            $stmt = $conn->prepare("INSERT INTO rating (user_id, menu_item_id, rating) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $menu_item_id, $rating);
        }
        $stmt->execute();
        echo "Rating saved successfully.";
    } else {
        echo "Invalid rating.";
    }
} else {
    echo "Invalid request.";
}
?>
