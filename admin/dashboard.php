<?php
include("../include/db.php"); // DB connection
include("../include/admin_header.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch stats
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_amount) AS revenue FROM orders WHERE status='delivered'")->fetch_assoc()['revenue'] ?? 0;
$pending_orders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status='Pending'")->fetch_assoc()['total'];
$delivered_orders = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status='Delivered'")->fetch_assoc()['total'];

// Top 5 best-selling items
$top_items = $conn->query("
    SELECT m.name, SUM(oi.quantity) AS total_qty
    FROM order_items oi
    JOIN menu_items m ON oi.menu_item_id = m.id
    GROUP BY oi.menu_item_id
    ORDER BY total_qty DESC
    LIMIT 5
");

// Low stock items
$low_stock_items = $conn->query("SELECT name, stock_qty FROM menu_items WHERE stock_qty <= 5");
?>

<?php include('../include/admin_sidebar.php'); ?>

<!-- Main Content -->
<div class="ml-64 mt-16 px-6 py-10 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">
        Welcome, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?> 🎉
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-orange-500">
            <h2 class="text-lg font-semibold text-gray-700">👥 Total Users</h2>
            <p class="text-3xl font-bold text-orange-600 mt-2"><?php echo $total_users; ?></p>
        </div>

        <!-- ✅ New Total Orders Card -->
        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-orange-500">
            <h2 class="text-lg font-semibold text-gray-700">📦 Total Orders</h2>
            <p class="text-3xl font-bold text-orange-600 mt-2">
                <?php
                $total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
                echo $total_orders;
                ?>
            </p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-orange-500">
            <h2 class="text-lg font-semibold text-gray-700">💰 Total Revenue</h2>
            <p class="text-3xl font-bold text-orange-600 mt-2">₹<?php echo number_format($total_revenue, 2); ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-orange-500">
            <h2 class="text-lg font-semibold text-gray-700">🕓 Pending Orders</h2>
            <p class="text-3xl font-bold text-orange-600 mt-2"><?php echo $pending_orders; ?></p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-orange-500">
            <h2 class="text-lg font-semibold text-gray-700">✅ Delivered Orders</h2>
            <p class="text-3xl font-bold text-orange-600 mt-2"><?php echo $delivered_orders; ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Selling Items -->
        <div class="bg-white p-6 rounded-2xl shadow border-l-4 border-orange-500">
            <h2 class="text-xl font-bold mb-4 text-gray-700">🏆 Top 5 Best-Selling Items</h2>
            <?php if ($top_items->num_rows > 0): ?>
                <ul class="space-y-2 text-sm text-gray-600">
                    <?php while ($item = $top_items->fetch_assoc()): ?>
                        <li class="flex justify-between border-b py-2">
                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                            <span class="font-semibold"><?php echo $item['total_qty']; ?> sold</span>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-sm text-gray-500">No sales data available.</p>
            <?php endif; ?>
        </div>

        <!-- Low Stock Alerts -->
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
</div>

<?php include('../include/admin_footer.php'); ?>