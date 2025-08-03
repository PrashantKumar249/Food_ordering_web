<?php
session_start();
include("../include/db.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
ob_start();

$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

$where = "";
if ($from_date && $to_date) {
    $where = "WHERE o.created_at BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'";
}

$query = "
    SELECT o.id, o.user_id, o.total_amount, o.created_at, GROUP_CONCAT(CONCAT(oi.quantity, 'x ', m.name) SEPARATOR ', ') AS items
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN menu_items m ON oi.menu_item_id = m.id
    $where
    GROUP BY o.id
    ORDER BY o.created_at DESC
";
$result = mysqli_query($conn, $query);

$summary_query = "
    SELECT COUNT(*) as total_orders, SUM(total_amount) as total_revenue
    FROM orders o
    $where
";
$summary_result = mysqli_query($conn, $summary_query);
$summary = mysqli_fetch_assoc($summary_result);
?>

<!-- Main Content -->
<div class="p-4 sm:p-6 md:p-8 bg-gray-50 min-h-screen">

    <h2 class="text-xl sm:text-2xl font-bold mb-6 text-orange-600">📊 Sales Report</h2>

    <!-- Filter Form -->
    <form method="GET" class="grid sm:grid-cols-3 gap-4 mb-6 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">From:</label>
            <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>"
                class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">To:</label>
            <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>"
                class="w-full border rounded px-3 py-2">
        </div>
        <div class="flex items-end">
            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Filter</button>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-gray-500 text-sm mb-1">Total Orders</h3>
            <p class="text-2xl font-bold text-blue-800"><?= $summary['total_orders'] ?? 0 ?></p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-gray-500 text-sm mb-1">Total Revenue</h3>
            <p class="text-2xl font-bold text-green-600">₹<?= $summary['total_revenue'] ?? 0 ?></p>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded shadow p-4 overflow-x-auto">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">Detailed Orders</h3>
        <table class="min-w-full text-sm border rounded">
            <thead class="bg-gray-100 uppercase text-xs text-gray-600">
                <tr>
                    <th class="px-3 py-2 border">Order ID</th>
                    <th class="px-3 py-2 border">User ID</th>
                    <th class="px-3 py-2 border">Items</th>
                    <th class="px-3 py-2 border">Total Price</th>
                    <th class="px-3 py-2 border">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-3 py-2"><?= $row['id'] ?></td>
                        <td class="px-3 py-2"><?= $row['user_id'] ?></td>
                        <td class="px-3 py-2"><?= htmlspecialchars($row['items']) ?></td>
                        <td class="px-3 py-2 text-green-700 font-semibold">₹<?= $row['total_amount'] ?></td>
                        <td class="px-3 py-2"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<?php
$content = ob_get_clean();
include("../include/admin_layout.php");
