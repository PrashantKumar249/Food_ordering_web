<?php
include "inc/db.php";
include "inc/header.php";

// 1. Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 🟡 Get delivery address from POST
$address_query = "SELECT address FROM users WHERE id = $user_id";
$address_result = mysqli_query($conn, $address_query);
$address = mysqli_fetch_assoc($address_result)['address'];

// 2. Get cart items
$cart_query = "SELECT c.menu_item_id, m.name, m.price, c.quantity 
               FROM cart_items c
               JOIN menu_items m ON c.menu_item_id = m.id
               WHERE c.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);

if (mysqli_num_rows($cart_result) == 0) {
    echo "<div class='min-h-screen bg-gray-50 flex items-center justify-center'><div class='text-center'><h2 class='text-2xl font-semibold text-gray-900 mb-4'>Your cart is empty.</h2><a href='index.php' class='text-orange-600 hover:text-orange-500'>Continue Shopping</a></div></div>";
    include "inc/footer.php";
    exit();
}

// 3. Create order with address
$order_query = "INSERT INTO orders (user_id, delivery_address, created_at) 
                VALUES ($user_id, '$address', NOW())";
mysqli_query($conn, $order_query);
$order_id = mysqli_insert_id($conn);

// 🟢 INIT total_amount
$total_amount = 0;

// 4. Insert cart items to order_items
while ($row = mysqli_fetch_assoc($cart_result)) {
    $menu_item_id = $row['menu_item_id'];
    $quantity = $row['quantity'];
    $price = $row['price'];

    $insert_item = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) 
                    VALUES ($order_id, $menu_item_id, $quantity, $price)";
    mysqli_query($conn, $insert_item);

    $total_amount += ($quantity * $price); // 🟢 CALCULATE
}

// 🟢 5. Update total_amount in orders table
$update_total = "UPDATE orders SET total_amount = $total_amount WHERE id = $order_id";
mysqli_query($conn, $update_total);

// 6. Clear cart
$clear_cart = "DELETE FROM cart_items WHERE user_id = $user_id";
mysqli_query($conn, $clear_cart);
?>

<!-- Success Page -->
<section class="min-h-screen bg-gradient-to-br from-green-50 via-orange-50 to-green-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500 rounded-full mb-6">
                <i class="fas fa-check text-white text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                🎉 Order Placed Successfully!
            </h1>
            <p class="text-xl text-gray-600">
                Thank you for your order. We're preparing your delicious food with care.
            </p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Order Header -->
            <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white">
                        <i class="fas fa-receipt mr-2"></i>
                        Order Details
                    </h2>
                    <div class="text-white">
                        <span class="text-sm opacity-90">Order ID:</span>
                        <span class="font-bold ml-1">#<?php echo $order_id; ?></span>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Order Items -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-list mr-2 text-orange-500"></i>
                            Order Summary
                        </h3>
                        
                        <div class="space-y-4">
                            <?php
                            $summary_query = "SELECT oi.quantity, oi.price, m.name, m.image 
                                              FROM order_items oi
                                              JOIN menu_items m ON oi.menu_item_id = m.id
                                              WHERE oi.order_id = $order_id";
                            $summary_result = mysqli_query($conn, $summary_query);

                            $grand_total = 0;
                            while ($row = mysqli_fetch_assoc($summary_result)) {
                                $name = $row['name'];
                                $qty = $row['quantity'];
                                $price = $row['price'];
                                $total = $qty * $price;
                                $grand_total += $total;
                            ?>
                                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <img class="w-12 h-12 rounded-lg object-cover" 
                                            src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" 
                                            alt="<?php echo htmlspecialchars($name); ?>">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?php echo htmlspecialchars($name); ?>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Qty: <?php echo $qty; ?> × ₹<?php echo number_format($price); ?>
                                        </p>
                                    </div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        ₹<?php echo number_format($total); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-orange-500"></i>
                            Order Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>
                                    <span class="font-medium text-gray-900">Delivery Address</span>
                                </div>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($address); ?></p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-clock text-orange-500 mr-2"></i>
                                    <span class="font-medium text-gray-900">Order Time</span>
                                </div>
                                <p class="text-gray-600 text-sm"><?php echo date('F j, Y \a\t g:i A'); ?></p>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-truck text-orange-500 mr-2"></i>
                                    <span class="font-medium text-gray-900">Estimated Delivery</span>
                                </div>
                                <p class="text-gray-600 text-sm">30-45 minutes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                        <span class="text-2xl font-bold text-orange-600">₹<?php echo number_format($grand_total); ?></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="index.php" 
                        class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200">
                        <i class="fas fa-utensils mr-2"></i>
                        Order More Food
                    </a>
                    
                    <a href="my_orders.php" 
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-box mr-2"></i>
                        View My Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-phone text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Need Help?</h3>
                <p class="text-gray-600 text-sm">Contact us at +91 98765 43210</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-clock text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Track Order</h3>
                <p class="text-gray-600 text-sm">Get real-time updates on your order</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 rounded-full mb-4">
                    <i class="fas fa-star text-orange-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Rate Us</h3>
                <p class="text-gray-600 text-sm">Share your experience with us</p>
            </div>
        </div>
    </div>
</section>

<?php include "inc/footer.php"; ?>
