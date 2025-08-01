<?php
session_start();
include("inc/db.php");
include("inc/admin_header.php");
include("inc/admin_sidebar.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ Your exact query
// Low stock items
$low_stock_items = $conn->query("SELECT name, stock_qty FROM menu_items WHERE stock_qty <= 5");
?>

<div class="p-6 sm:ml-64 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-semibold mb-6">Stock Alerts</h1>

    <!-- ✅ Your exact alert block -->
    <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-red-500">
        <h2 class="text-xl font-bold mb-4 text-gray-700">⚠️ Low Stock Alerts</h2>
        <?php if ($low_stock_items->num_rows > 0): ?>
            <ul class="space-y-2 text-sm text-gray-600">
                <?php while ($stock = $low_stock_items->fetch_assoc()): ?>
                    <li class="flex justify-between border-b py-2">
                        <span><?php echo htmlspecialchars($stock['name']); ?></span>
                        <span class="font-semibold text-red-600"><?php echo $stock['stock_qty']; ?> left</span>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-sm text-green-600">All stocks are sufficient ✅</p>
        <?php endif; ?>
    </div>

    
</div>

<?php include("inc/footer.php"); ?>
