<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_id = $_POST['user_id'];
  $menu_item_id = $_POST['menu_item_id'];
  $quantity = $_POST['quantity'];
  $type = $_POST['type'];
  if ($type == 'add') {
    $stmt = $conn->prepare("SELECT * FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
    $stmt->bind_param("ii", $user_id, $menu_item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $new_quantity = $row['quantity'] + 1;

      $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND menu_item_id = ?");
      $update->bind_param("iii", $new_quantity, $user_id, $menu_item_id);
      $update->execute();
    } else {
      $insert = $conn->prepare("INSERT INTO cart_items (user_id, menu_item_id, quantity) VALUES (?, ?, ?)");
      $insert->bind_param("iii", $user_id, $menu_item_id, $quantity);
      $insert->execute();
    }

    header("Location: index.php");
  }
  
  if ($type == "remove") {
    $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
    $stmt->bind_param("ii", $user_id, $menu_item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $new_quantity = $row['quantity'] - 1;

      if ($new_quantity > 0) {
        $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND menu_item_id = ?");
        $update->bind_param("iii", $new_quantity, $user_id, $menu_item_id);
        $update->execute();
      } else {
        // quantity 0 or less, so remove item
        $delete = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
        $delete->bind_param("ii", $user_id, $menu_item_id);
        $delete->execute();
      }
    }

    header("Location: index.php");
    exit();
  }
}

?>