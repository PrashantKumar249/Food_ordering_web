<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_id = $_POST['user_id'];
  $menu_item_id = $_POST['menu_item_id'];
  $page = $_POST['page_name'] ?? 'index.php#menuItems';

    $stmt = $conn->prepare("SELECT * FROM wishlist_items WHERE user_id = ? AND menu_item_id = ?");
    $stmt->bind_param("ii", $user_id, $menu_item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // delete from wishlist
      $stmt = $conn->prepare("DELETE FROM wishlist_items WHERE user_id = ? AND menu_item_id = ?");
      $stmt->bind_param("ii", $user_id, $menu_item_id);
      $stmt->execute();
    } else {
      $insert = $conn->prepare("INSERT INTO wishlist_items (user_id, menu_item_id) VALUES (?, ?)");
      $insert->bind_param("ii", $user_id, $menu_item_id);
      $insert->execute();
    }
    $stmt->close();
    $conn->close();
    header("Location: $page");
}

?>