<?php
session_start();
include("../include/db.php");
include("../include/admin_header.php");
include("../include/admin_sidebar.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

$where = "";
if ($from_date && $to_date) {
    if ($from_date && $to_date) {
    $where = "WHERE o.created_at BETWEEN '$from_date 00:00:00' AND '$to_date 23:59:59'";
    }
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

// Summary
$summary_query = "
    SELECT COUNT(*) as total_orders, SUM(total_amount) as total_revenue
    FROM orders o
    $where
";
$summary_result = mysqli_query($conn, $summary_query);
$summary = mysqli_fetch_assoc($summary_result);
?>

<div class="ml-64 p-6 min-h-screen bg-gray-100">
    <h2 class="text-2xl font-bold mb-4">📊 Sales Report</h2>

    <!-- Filter Form -->
    <form method="GET" class="flex items-center gap-4 mb-6 bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700">From:</label>
            <input type="date" name="from_date" value="<?= htmlspecialchars($from_date) ?>" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">To:</label>
            <input type="date" name="to_date" value="<?= htmlspecialchars($to_date) ?>" class="border rounded px-3 py-2">
        </div>
        <div class="mt-5">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
        </div>
    </form>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-gray-500 text-sm">Total Orders</h3>
            <p class="text-xl font-bold"><?= $summary['total_orders'] ?? 0 ?></p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-gray-500 text-sm">Total Revenue</h3>
            <p class="text-xl font-bold">₹<?= $summary['total_revenue'] ?? 0 ?></p>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded shadow p-4 overflow-x-auto">
        <h3 class="text-lg font-semibold mb-4">Detailed Orders</h3>
        <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-100 uppercase text-xs">
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
                    <tr class="border-t">
                        <td class="px-3 py-2"><?= $row['id'] ?></td>
                        <td class="px-3 py-2"><?= $row['user_id'] ?></td>
                        <td class="px-3 py-2"><?= htmlspecialchars($row['items']) ?></td>
                        <td class="px-3 py-2">₹<?= $row['total_amount'] ?></td>
                        <td class="px-3 py-2"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../include/admin_footer.php"); ?>
