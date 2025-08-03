<?php
session_start();
include("../include/db.php");


if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
ob_start(); // ✅ Start buffering after session check

// Fetch items with low stock (<= 5)
$low_stock_items = $conn->query("SELECT name, stock_qty FROM menu_items WHERE stock_qty <= 5");
?>

<!-- Page Content Wrapper -->
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">📦 Stock Alerts</h1>
</div>

<!-- Low Stock Alerts -->
<div class="bg-white rounded-2xl shadow-md border-l-4 border-red-500 p-4 sm:p-6">
    <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4 flex items-center gap-2">
        ⚠️ Low Stock Items
    </h2>

    <?php if ($low_stock_items->num_rows > 0): ?>
        <ul class="divide-y divide-gray-200 text-sm sm:text-base">
            <?php while ($item = $low_stock_items->fetch_assoc()): ?>
                <li class="flex justify-between py-2 sm:py-3">
                    <span class="text-gray-700 font-medium"><?php echo htmlspecialchars($item['name']); ?></span>
                    <span class="text-red-600 font-semibold"><?php echo $item['stock_qty']; ?> left</span>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-green-600 font-medium text-sm sm:text-base">✅ All items are sufficiently stocked!</p>
    <?php endif; ?>
</div>

<?php
// 🧩 Final output for layout
$content = ob_get_clean();
include("../include/admin_layout.php");