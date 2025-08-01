<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain'); // AJAX expects plain text response
include 'inc/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $menu_item_id = intval($_POST['menu_item_id']);
    $quantity = intval($_POST['quantity']) ?: 1;
    $type = $_POST['type'];

    if ($type == 'add') {
        $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
        $stmt->bind_param("ii", $user_id, $menu_item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] + $quantity;

            $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND menu_item_id = ?");
            $update->bind_param("iii", $new_quantity, $user_id, $menu_item_id);
            $update->execute();
        } else {
            $insert = $conn->prepare("INSERT INTO cart_items (user_id, menu_item_id, quantity) VALUES (?, ?, ?)");
            $insert->bind_param("iii", $user_id, $menu_item_id, $quantity);
            $insert->execute();
        }

        echo "success";
        exit;
    }

    if ($type == 'remove') {
        $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
        $stmt->bind_param("ii", $user_id, $menu_item_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $new_quantity = $row['quantity'] - $quantity;

            if ($new_quantity > 0) {
                $update = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND menu_item_id = ?");
                $update->bind_param("iii", $new_quantity, $user_id, $menu_item_id);
                $update->execute();
            } else {
                $delete = $conn->prepare("DELETE FROM cart_items WHERE user_id = ? AND menu_item_id = ?");
                $delete->bind_param("ii", $user_id, $menu_item_id);
                $delete->execute();
            }

            echo "success";
        } else {
            echo "not_in_cart";
        }
        exit;
    }

    echo "invalid_type";
    exit;
}

echo "invalid_request";
exit;
?>
