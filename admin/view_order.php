<?php
include '../inc/db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get order ID from URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$order_id) {
    header("Location: orders.php");
    exit();
}

// Get order details
$order_query = "SELECT o.*, u.name as user_name, u.phone as user_phone, u.email as user_email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    header("Location: orders.php");
    exit();
}

// Get order items
$items_query = "SELECT oi.*, m.name as item_name, m.image as item_image 
                FROM order_items oi 
                JOIN menu_items m ON oi.menu_item_id = m.id 
                WHERE oi.order_id = ?";
$items_stmt = $conn->prepare($items_query);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <!-- Admin Navigation -->
    <nav class="bg-gray-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold">Admin Dashboard</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-300 hover:text-white transition-colors duration-200">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <a href="orders.php" class="text-gray-300 hover:text-white transition-colors duration-200">
                        <i class="fas fa-shopping-bag mr-1"></i>Orders
                    </a>
                    <a href="../index.php" class="text-gray-300 hover:text-white transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>View Site
                    </a>
                    <a href="logout.php" class="text-red-400 hover:text-red-300 transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Details</h1>
                    <p class="text-gray-600">Order #<?php echo $order_id; ?> - <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                </div>
                <a href="orders.php" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Orders
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-info-circle mr-2 text-orange-500"></i>
                    Order Information
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Order ID:</span>
                        <span class="text-sm font-semibold text-gray-900">#<?php echo $order['id']; ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Order Date:</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Completed
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-user mr-2 text-orange-500"></i>
                    Customer Information
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Name:</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($order['user_name']); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Phone:</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($order['user_phone']); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Email:</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($order['user_email']); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Delivery Address:</span>
                        <span class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($order['delivery_address']); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500">
                <h2 class="text-xl font-semibold text-white">
                    <i class="fas fa-list mr-2"></i>
                    Order Items
                </h2>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    <?php 
                    $total_amount = 0;
                    while ($item = $items_result->fetch_assoc()): 
                        $item_total = $item['quantity'] * $item['price'];
                        $total_amount += $item_total;
                    ?>
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <img class="w-16 h-16 rounded-lg object-cover" 
                                    src="../images/<?php echo htmlspecialchars($item['item_image']); ?>" 
                                    alt="<?php echo htmlspecialchars($item['item_name']); ?>">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                </p>
                                <p class="text-sm text-gray-500">
                                    Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price']); ?>
                                </p>
                            </div>
                            <div class="text-sm font-semibold text-gray-900">
                                ₹<?php echo number_format($item_total); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Total Amount -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                        <span class="text-2xl font-bold text-orange-600">₹<?php echo number_format($total_amount); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <button class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Order
                </button>
                <button class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-print mr-2"></i>
                    Print Invoice
                </button>
            </div>
            
            <a href="orders.php" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Orders
            </a>
        </div>
    </div>
</body>
</html>
