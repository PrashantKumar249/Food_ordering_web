<?php
session_start();
include("../include/db.php");
if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $new_status = $_POST['status'] ?? null;

    if ($order_id && $new_status) {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "Order #$order_id status updated to '$new_status' successfully.";
header("Location: manage_orders.php");
exit();
        } else {
            $message = "Failed to update order status. Please try again.";
        }
        $stmt->close();
    } else {
        $message = "Invalid request. Order ID or status missing.";
    }
} else {
    $message = "Invalid request method.";
}
?>