<?php
include '../include/db.php';
include '../include/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update_query = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssssi", $name, $email, $phone, $address, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['username'] = $name;
        $success_message = "Profile updated successfully!";
        // Refresh user data
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_result = $stmt->get_result();
        $user = $user_result->fetch_assoc();

    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}

// Get order statistics
$orders_query = "SELECT COUNT(*) as total_orders FROM orders WHERE user_id = ?";
$orders_stmt = $conn->prepare($orders_query);
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
$total_orders = $orders_result->fetch_assoc()['total_orders'];

// Get cart items count
$cart_query = "SELECT SUM(quantity) as cart_items FROM cart_items WHERE user_id = ?";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
$cart_items = $cart_result->fetch_assoc()['cart_items'] ?? 0;
?>

<!-- Profile Page -->
<section class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
            <p class="text-gray-600">Manage your account information and preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="text-center mb-6">
                        <div
                            class="w-24 h-24 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-user text-white text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></h2>
                        <p class="text-gray-600">Member since
                            <?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?></p>
                    </div>

                    <!-- Statistics -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-bag text-orange-500 mr-3"></i>
                                <span class="text-gray-700">Total Orders</span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo $total_orders; ?></span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-cart text-orange-500 mr-3"></i>
                                <span class="text-gray-700">Cart Items</span>
                            </div>
                            <span class="font-semibold text-gray-900"><?php echo $cart_items; ?></span>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="space-y-2">
                        <a href="my_orders.php"
                            class="block w-full text-left px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition-colors duration-200">
                            <i class="fas fa-box mr-2"></i>
                            My Orders
                        </a>
                        <a href="cart.php"
                            class="block w-full text-left px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition-colors duration-200">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Shopping Cart
                        </a>
                        <a href="index.php"
                            class="block w-full text-left px-4 py-3 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-lg transition-colors duration-200">
                            <i class="fas fa-utensils mr-2"></i>
                            Order Food
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-edit mr-2 text-orange-500"></i>
                        Edit Profile
                    </h3>

                    <?php if (isset($success_message)): ?>
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span
                                    class="text-green-700 text-sm"><?php echo htmlspecialchars($success_message); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error_message)): ?>
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                                <span class="text-red-700 text-sm"><?php echo htmlspecialchars($error_message); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form method="post" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-orange-500"></i>Full Name
                                </label>
                                <input type="text" name="name" id="name"
                                    value="<?php echo htmlspecialchars($user['name']); ?>" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-orange-500"></i>Email Address
                                </label>
                                <input type="email" name="email" id="email"
                                    value="<?php echo htmlspecialchars($user['email']); ?>" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Phone Field -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-orange-500"></i>Phone Number
                                </label>
                                <input type="tel" name="phone" id="phone"
                                    value="<?php echo htmlspecialchars($user['phone']); ?>" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            </div>

                            <!-- Password Field (Read-only) -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-orange-500"></i>Password
                                </label>
                                <input type="password" value="••••••••" disabled
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                                <p class="text-xs text-gray-500 mt-1">Password cannot be changed here</p>
                            </div>
                        </div>

                        <!-- Address Field -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>Delivery Address
                            </label>
                            <textarea name="address" id="address" rows="3" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                placeholder="Enter your complete delivery address"><?php echo htmlspecialchars($user['address']); ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>

                            <a href="logout.php"
                                class="inline-flex items-center px-4 py-2 border border-red-300 text-red-600 font-medium rounded-lg hover:bg-red-50 transition-all duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../include/footer.php'; ?>