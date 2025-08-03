<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
include "../include/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

ob_start();

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (!$order_id || !$user_id) {
    echo "<div class='text-center py-10 text-red-600'>❌ Invalid order.</div>";
    exit();
}

$order_query = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "<div class='text-center py-10 text-red-600'>❌ Order not found.</div>";
    exit();
}

$order = mysqli_fetch_assoc($order_result);
$created_at = date("d M Y, h:i A", strtotime($order['created_at']));
$address = $order['delivery_address'];
?>

<section class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Title -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Order Details</h1>
            <p class="text-gray-600 text-sm sm:text-base">Here’s the summary of the order placed by the user.</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center text-black">
                    <div>
                        <h3 class="text-lg font-semibold">Order #<?= $order_id ?></h3>
                        <p class="text-sm opacity-90"><?= $created_at ?></p>
                    </div>
                    <div class="text-sm text-black sm:text-right">
                        <div class="opacity-80">Status</div>
                        <div class="text-lg font-bold">
                            <?= htmlspecialchars(ucwords(strtolower($order['status']))) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6">
                <!-- Address -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center shrink-0">
                        <i class="fas fa-map-marker-alt text-orange-600"></i>
                    </div>
                    <div class="text-sm sm:text-base">
                        <p class="text-gray-500">Delivery Address</p>
                        <p class="text-gray-900 font-medium whitespace-pre-line"><?= htmlspecialchars($address) ?></p>
                    </div>
                </div>

                <!-- Order Items -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Items Ordered</h4>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full text-sm text-left">
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
                                    $name = htmlspecialchars($row['name']);
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

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse md:flex-row md:justify-between md:items-center gap-4 mt-6">
                    <!-- Left: Invoice Buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="order_invoice.php?id=<?= $order['id'] ?>&action=view" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-black text-sm font-medium rounded-md shadow-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            View Invoice
                        </a>
                        <a href="order_invoice.php?id=<?= $order['id'] ?>&action=download"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-black text-sm font-medium rounded-md shadow-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Download Invoice
                        </a>
                    </div>

                    <!-- Right: Back Button -->
                    <a href="manage_orders.php"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-black font-semibold rounded-md shadow-md transition hover:shadow-lg">
                        ← Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
$content = ob_get_clean();
include("../include/admin_layout.php");
