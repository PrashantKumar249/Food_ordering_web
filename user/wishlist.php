<?php
include '../include/db.php';
include '../include/header.php';

$query = $conn->prepare('SELECT * FROM wishlist_items WHERE user_id = ?');
$query->bind_param('i', $_SESSION['user_id']);
$query->execute();
$result = $query->get_result();
?>

<?php
$toastMessage = '';
$messageType = 'added';

if (isset($_SESSION['flash_message'])) {
    $toastMessage = $_SESSION['flash_message'];
    $messageType = $_SESSION['message_type'] ?? 'added';

    unset($_SESSION['flash_message']);
    unset($_SESSION['message_type']);
}

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
    }, 2000);
</script>

<?php if (!empty($toastMessage)): ?>
    <div id="toast-message"
        class="fixed bottom-5 right-5 text-sm px-4 py-3 rounded shadow-lg z-50 animate-slide-in <?= $toastClasses ?>">
        <?= $messageType === 'removed' ? '❌' : '✅' ?>
        <?= htmlspecialchars($toastMessage) ?>
    </div>
<?php endif; ?>

<section class="min-h-screen bg-pink-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Wishlist ❤️</h1>
            <p class="text-gray-600">Save your favorite dishes for later!</p>
        </div>

        <?php if ($result->num_rows === 0): ?>
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-pink-100 rounded-full mb-6">
                    <i class="fas fa-heart-broken text-pink-400 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your Wishlist is empty</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Browse our menu and click the ♥ icon to save dishes you love!
                </p>
                <a href="index.php"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-utensils mr-2"></i>
                    Explore Menu
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php while ($row = $result->fetch_assoc()):
                    $menu_item_id = $row['menu_item_id'];
                    $menu_query = $conn->prepare('SELECT name, price, image FROM menu_items WHERE id = ?');
                    $menu_query->bind_param('i', $menu_item_id);
                    $menu_query->execute();
                    $menu_result = $menu_query->get_result();
                    $menu_item = $menu_result->fetch_assoc();
                    ?>
                    <div
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-200 border border-gray-100">
                        <img src="../assets/images/<?= htmlspecialchars($menu_item['image']); ?>"
                            alt="<?= htmlspecialchars($menu_item['name']); ?>" class="h-48 w-full object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1"><?= htmlspecialchars($menu_item['name']); ?>
                            </h3>
                            <p class="text-orange-600 font-bold text-xl mb-3">₹<?= number_format($menu_item['price']); ?></p>
                            <div class="flex items-center justify-between space-x-2">
                                <!-- Move to Cart -->
                                <form action="add_cart.php" method="POST" class="w-1/2">
                                    <input type="hidden" name="type" value="add">
                                    <input type="hidden" name="menu_item_id" value="<?= $menu_item_id; ?>">
                                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="page_name" value="wishlist">
                                    <button type="submit"
                                        class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg transition duration-200">
                                        <i class="fas fa-cart-plus mr-1"></i> Move to Cart
                                    </button>
                                </form>

                                <!-- Remove from Wishlist -->
                                <form action="add_wishlist.php" method="POST" class="w-1/2">
                                    <input type="hidden" name="menu_item_id" value="<?= $menu_item_id; ?>">
                                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
                                    <input type="hidden" name="page_name" value="wishlist.php">
                                    <button type="submit"
                                        class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition duration-200">
                                        <i class="fas fa-trash-alt mr-1"></i> Remove from Wishlist
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../include/footer.php'; ?>