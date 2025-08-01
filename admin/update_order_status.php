<?php

include("inc/db.php");
include("inc/admin_header.php");
include("inc/admin_sidebar.php");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Order Status | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="ml-64 mt-16 p-6">
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-xl font-semibold mb-4">🔁 Order Status Update</h2>
            <p class="text-gray-700"><?= htmlspecialchars($message) ?></p>
            <a href="manage_orders.php" class="inline-block mt-4 px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">← Back to Orders</a>
        </div>
    </div>

    <?php include("inc/footer.php"); ?>
</body>
</html>
