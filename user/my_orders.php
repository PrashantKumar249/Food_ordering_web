<?php
include '../include/db.php';
include '../include/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$orders_query = "SELECT o.*, COUNT(oi.id) as item_count, SUM(oi.quantity * oi.price) as total_amount 
                 FROM orders o 
                 LEFT JOIN order_items oi ON o.id = oi.order_id 
                 WHERE o.user_id = ? 
                 GROUP BY o.id 
                 ORDER BY o.created_at DESC";
$stmt = $conn->prepare($orders_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!-- My Orders Page -->
<section class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Orders</h1>
            <p class="text-gray-600">Track your order history and view past deliveries</p>
        </div>

        <?php if ($orders_result->num_rows === 0): ?>
            <!-- Empty Orders State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-box text-gray-400 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">No orders yet</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    You haven't placed any orders yet. Start exploring our delicious menu and place your first order!
                </p>
                <a href="index.php"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-utensils mr-2"></i>
                    Order Food Now
                </a>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <div class="space-y-6">
                <?php while ($order = $orders_result->fetch_assoc()): ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <!-- Order Header -->
                        <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-white bg-opacity-20 rounded-full p-2">
                                        <i class="fas fa-receipt text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-white">
                                            Order #<?php echo $order['id']; ?>
                                        </h3>
                                        <p class="text-orange-100 text-sm">
                                            <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right text-white">
                                    <div class="text-sm opacity-90">Total</div>
                                    <div class="text-xl font-bold">₹<?php echo number_format($order['total_amount']); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Delivery Address</p>
                                        <p class="text-gray-900 font-medium">
                                            <?php echo htmlspecialchars($order['delivery_address']); ?></p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Items Ordered</p>
                                        <p class="text-gray-900 font-medium"><?php echo $order['item_count']; ?> items</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-truck text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Status</p>
                                        <p class="text-gray-900 font-medium">
                                            <?php echo htmlspecialchars(ucwords(strtolower($order['status']))); ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h4>
                                <?php
                                $items_query = "SELECT oi.quantity, oi.price, m.name, m.image 
                                               FROM order_items oi 
                                               JOIN menu_items m ON oi.menu_item_id = m.id 
                                               WHERE oi.order_id = ?";
                                $items_stmt = $conn->prepare($items_query);
                                $items_stmt->bind_param("i", $order['id']);
                                $items_stmt->execute();
                                $items_result = $items_stmt->get_result();
                                ?>
                                <div class="space-y-3">
                                    <?php while ($item = $items_result->fetch_assoc()): ?>
                                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                            <div class="flex-shrink-0">
                                                <img class="w-12 h-12 rounded-lg object-cover"
                                                    src="../assets/images/<?php echo htmlspecialchars($item['image']); ?>"
                                                    alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    Qty: <?php echo $item['quantity']; ?> ×
                                                    ₹<?php echo number_format($item['price']); ?>
                                                </p>
                                            </div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                ₹<?php echo number_format($item['quantity'] * $item['price']); ?>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div
                                class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <?php $secret = "mySecretSalt123";
                                    $encoded_order_id = urlencode(base64_encode($secret . $order['id'])); ?>
                                    <a href="order_details.php?order_id=<?php echo $encoded_order_id; ?>"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Details
                                    </a>
                                    <button
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <i class="fas fa-redo mr-2"></i>
                                        Reorder
                                    </button>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <?php if (strtolower($order['status']) === 'delivered'): ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                           Delivered
                                        </span>

                                    <?php elseif (strtolower($order['status']) === 'pending'): ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-hourglass-half mr-1"></i>
                                            Pending
                                        </span>
                                    <?php elseif (strtolower($order['status']) === 'cancelled'): ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Cancelled
                                        </span>
                                    <?php else: ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Back to Menu -->
            <div class="mt-8 text-center">
                <a href="index.php"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-utensils mr-2"></i>
                    Order More Food
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../include/footer.php'; ?>