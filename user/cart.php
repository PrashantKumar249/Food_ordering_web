<?php
include 'inc/db.php';
include 'inc/header.php';

$query = $conn->prepare('SELECT * FROM cart_items WHERE user_id = ?');
$query->bind_param('i', $_SESSION['user_id']);
$query->execute();
$result = $query->get_result();
?>

<?php
$toastMessage = '';
$messageType = 'added'; // default green

if (isset($_SESSION['flash_message'])) {
    $toastMessage = $_SESSION['flash_message'];
    $messageType = $_SESSION['message_type'] ?? 'added';

    unset($_SESSION['flash_message']);
    unset($_SESSION['message_type']);
}

// Determine classes based on type
$toastClasses = $messageType === 'removed'
    ? 'bg-red-100 border-red-300 text-red-800'
    : 'bg-green-100 border-green-300 text-green-800';
?>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }
</style>
<script>
    setTimeout(() => {
        const toast = document.getElementById('toast-message');
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
        }
    }, 2000); // Hide after 4 seconds
</script>

<?php if (!empty($toastMessage)): ?>
    <div id="toast-message"
        class="fixed bottom-5 right-5 text-sm px-4 py-3 rounded shadow-lg z-50 animate-slide-in <?= $toastClasses ?>">
        ✅ <?= htmlspecialchars($toastMessage) ?>
    </div>
<?php endif; ?>

<!-- Cart Page -->
<section class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Your Shopping Cart</h1>
            <p class="text-gray-600">Review your items and proceed to checkout</p>
        </div>

        <?php if ($result->num_rows === 0): ?>
            <!-- Empty Cart State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Looks like you haven't added any delicious items to your cart yet. 
                    Start exploring our menu and add some tasty dishes!
                </p>
                <a href="index.php" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-utensils mr-2"></i>
                    Explore Menu
                </a>
            </div>
        <?php else: ?>
            <!-- Cart Items -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500">
                    <h2 class="text-xl font-semibold text-white">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Cart Items (<?php echo $result->num_rows; ?>)
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Item
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $total = 0;
                            while ($row = $result->fetch_assoc()) {
                                $menu_item_id = $row['menu_item_id'];
                                $menu_query = $conn->prepare('SELECT name, price, image FROM menu_items WHERE id = ?');
                                $menu_query->bind_param('i', $menu_item_id);
                                $menu_query->execute();
                                $menu_result = $menu_query->get_result();
                                $menu_item = $menu_result->fetch_assoc();
                                $item_total = $menu_item['price'] * $row['quantity'];
                                $total += $item_total;
                            ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <img class="h-12 w-12 rounded-lg object-cover" 
                                                    src="../assets/images/<?php echo htmlspecialchars($menu_item['image']); ?>" 
                                                    alt="<?php echo htmlspecialchars($menu_item['name']); ?>">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($menu_item['name']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-semibold">
                                            ₹<?php echo number_format($menu_item['price']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <!-- Remove Button -->
                                            <form method="post" action="add_cart.php" class="inline-block">
                                                <input type="hidden" name="type" value="remove">
                                                <input type="hidden" name="page_name" value="cart">
                                                <input type="hidden" name="menu_item_id" value="<?php echo htmlspecialchars($row['menu_item_id']); ?>">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-all duration-200 hover:shadow-lg"
                                                    title="Remove">
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Quantity Display -->
                                            <span class="text-lg font-semibold text-gray-900 min-w-[2rem] text-center">
                                                <?php echo htmlspecialchars($row['quantity']); ?>
                                            </span>
                                            
                                            <!-- Add Button -->
                                            <form method="post" action="add_cart.php" class="inline-block">
                                                <input type="hidden" name="type" value="add">
                                                <input type="hidden" name="page_name" value="cart">
                                                <input type="hidden" name="menu_item_id" value="<?php echo htmlspecialchars($row['menu_item_id']); ?>">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit"
                                                    class="w-8 h-8 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition-all duration-200 hover:shadow-lg"
                                                    title="Add">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-lg font-bold text-orange-600">
                                            ₹<?php echo number_format($item_total); ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Cart Summary -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="text-lg font-semibold text-gray-900">
                            Total Amount:
                        </div>
                        <div class="text-2xl font-bold text-orange-600">
                            ₹<?php echo number_format($total); ?>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="px-6 py-4 bg-white border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="index.php" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continue Shopping
                        </a>
                        
                        <form action="checkout.php" method="post" class="inline-block">
                            <input type="hidden" name="total" value="<?php echo htmlspecialchars($total); ?>">
                            <button type="submit" 
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <i class="fas fa-credit-card mr-2"></i>
                                Proceed to Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'inc/footer.php'; ?>
