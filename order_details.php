<?php
include "inc/db.php";
include "inc/header.php";

// Login check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get order ID from URL
if (!isset($_GET['order_id'])) {
    echo "<div class='text-center py-10'>❌ Invalid order.</div>";
    exit();
}

$order_id = intval($_GET['order_id']);

// Check if order belongs to the user
$order_query = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "<div class='text-center py-10'>❌ Order not found.</div>";
    exit();
}

$order = mysqli_fetch_assoc($order_result);
$created_at = date("d M Y, h:i A", strtotime($order['created_at']));
$address = $order['delivery_address'];
?>

<!-- MAIN CONTENT WRAPPER -->
<div class="min-h-screen flex flex-col bg-gray-50 pb-24"> <!-- pb-24 for footer space -->
    <div class="max-w-4xl mx-auto px-4 flex-grow pt-10">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🧾 Order Details</h2>

            <div class="mb-4">
                <p><strong>🆔 Order ID:</strong> #<?php echo $order_id; ?></p>
                <p><strong>📅 Date:</strong> <?php echo $created_at; ?></p>
                <p><strong>📍 Delivery Address:</strong> <?php echo nl2br(htmlspecialchars($address)); ?></p>
            </div>

            <table class="w-full text-sm text-left border border-gray-300 mt-4">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">Item</th>
                        <th class="px-4 py-2 border">Quantity</th>
                        <th class="px-4 py-2 border">Price (₹)</th>
                        <th class="px-4 py-2 border">Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $item_query = "SELECT oi.quantity, oi.price, m.name 
                                   FROM order_items oi
                                   JOIN menu_items m ON oi.menu_item_id = m.id
                                   WHERE oi.order_id = $order_id";
                    $item_result = mysqli_query($conn, $item_query);

                    $grand_total = 0;
                    while ($row = mysqli_fetch_assoc($item_result)) {
                        $name = $row['name'];
                        $qty = $row['quantity'];
                        $price = $row['price'];
                        $total = $qty * $price;
                        $grand_total += $total;

                        echo "<tr>
                                <td class='px-4 py-2 border'>$name</td>
                                <td class='px-4 py-2 border'>$qty</td>
                                <td class='px-4 py-2 border'>₹$price</td>
                                <td class='px-4 py-2 border'>₹$total</td>
                              </tr>";
                    }

                    echo "<tr class='font-semibold bg-gray-50'>
                            <td colspan='3' class='text-right px-4 py-2 border'>Grand Total</td>
                            <td class='px-4 py-2 border'>₹$grand_total</td>
                          </tr>";
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- give a back  button -->
    <div class="max-w-4xl mx-auto px-4 mt-6">
        <a href="my_orders.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
            ← Back to My Orders
        </a>
    </div>

    <!-- FIXED FOOTER -->
    <footer class="bg-gray-800 text-white text-center py-4 w-full fixed bottom-0 left-0 z-50">
        <p>&copy; <?php echo date('Y'); ?> Khana Khazana. All rights reserved.</p>
    </footer>
</div>
