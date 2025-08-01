<?php
require_once __DIR__ . '/../vendor/autoload.php';

include "inc/db.php";
include "inc/admin_header.php";

// Login check
if (empty($_SESSION['admin_id'])) {
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
$user_id = intval($_GET['user_id'] ); // Use session user_id if not provided

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
<!-- Order Details Page -->
<section class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Details</h1>
            <p class="text-gray-600">Here’s the summary of your order and what you received</p>
        </div>

        <!-- Order Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Order Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="text-white">
                        <h3 class="text-lg font-semibold">Order #<?php echo $order_id; ?></h3>
                        <p class="text-orange-100 text-sm"><?php echo $created_at; ?></p>
                    </div>
                    <div class="text-right text-white">
                        <div class="text-sm opacity-90">Status</div>
                        <div class="text-lg font-bold">
                            <?php echo htmlspecialchars(ucwords(strtolower($order['status']))); ?>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Order Info -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-orange-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Delivery Address</p>
                            <p class="text-gray-900 font-medium"><?php echo nl2br(htmlspecialchars($address)); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Order Items Table -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Items Ordered</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left border border-gray-200">
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
                <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-center">
                    <div class="flex space-x-2">
                        <a href="order_invoice.php?id=<?= $order['id'] ?>&action=view" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z" />
                            </svg>
                            View Invoice
                        </a>
                        <a href="order_invoice.php?id=<?= $order['id'] ?>&action=download"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Download Invoice
                        </a>
                    </div>
                    <div class="mt-4 md:mt-0 text-right">
                        <a href="manage_orders.php"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            ← Back to My Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
include "inc/footer.php";
?>