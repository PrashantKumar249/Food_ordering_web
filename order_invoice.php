<?php
session_start();
ini_set('display_errors', 1);
require_once("inc/db.php");
require_once __DIR__ . '/vendor/autoload.php';

// Validate login and order ID
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("Access denied.");
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch order details
$order_query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Fetch user info
$user_query = "SELECT name, address FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$user_name = $user['name'] ?? 'Customer';
$user_address = $user['address'] ?? 'N/A';

// Fetch order items
$items_query = "SELECT mi.name, mi.price, oi.quantity 
                FROM order_items oi 
                JOIN menu_items mi ON oi.menu_item_id = mi.id 
                WHERE oi.order_id = ?";
$stmt = $conn->prepare($items_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

// Build invoice HTML
$invoiceHtml = '
<style>
    body { font-family: Arial, sans-serif; color: #333; }
    .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
    h1 { text-align: center; color: #333; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .total-row { font-weight: bold; }
    .text-right { text-align: right; }
</style>

<div class="invoice-box">
    <h1>Khana Khazana Invoice</h1>
    <p><strong>Invoice #: </strong> #' . $order['id'] . '</p>
    <p><strong>Date: </strong>' . date('d M Y, h:i A', strtotime($order['created_at'])) . '</p>
    <p><strong>Customer: </strong>' . htmlspecialchars($user_name) . '</p>
    <p><strong>Address: </strong>' . htmlspecialchars($user_address) . '</p>

    <table>
        <tr>
            <th>Item</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Subtotal (₹)</th>
        </tr>';

$total = 0;
while ($item = $items_result->fetch_assoc()) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $invoiceHtml .= '
        <tr>
            <td>' . htmlspecialchars($item['name']) . '</td>
            <td>' . number_format($item['price'], 2) . '</td>
            <td>' . $item['quantity'] . '</td>
            <td>' . number_format($subtotal, 2) . '</td>
        </tr>';
}

$invoiceHtml .= '
        <tr class="total-row">
            <td colspan="3" class="text-right">Total</td>
            <td>' . number_format($total, 2) . ' ₹</td>
        </tr>
    </table>
    <p style="margin-top: 30px;">Thank you for ordering from Khana Khazana!</p>
</div>';

if (!class_exists('\Mpdf\Mpdf')) {
    die("mPDF not loaded");
}


// Generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->SetTitle("Invoice - Order #" . $order['id']);
$mpdf->WriteHTML($invoiceHtml);
$mpdf->Output("Invoice_Order_{$order['id']}.pdf", \Mpdf\Output\Destination::INLINE); // Use DOWNLOAD to force download
?>
