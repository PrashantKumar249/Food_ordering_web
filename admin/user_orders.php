<?php
session_start();
include("../include/db.php");
include("../include/admin_header.php");
include("../include/admin_sidebar.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($user_id <= 0) {
    echo "<p class='p-4 text-red-500'>Invalid User ID.</p>";
    exit();
}

// Fetch orders for this user
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$orders = $order_stmt->get_result();
?>

<div class="p-6 sm:ml-64 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-semibold mb-4">User Order History</h1>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full border border-gray-200 text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 border">Order ID</th>
                    <th class="px-6 py-3 border">Total Price</th>
                    <th class="px-6 py-3 border">Status</th>
                    <th class="px-6 py-3 border">Placed At</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-6 py-4 border"><?= $order['id'] ?></td>
                            <td class="px-6 py-4 border">₹<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="px-6 py-4 border capitalize"><?= htmlspecialchars($order['status']) ?></td>
                            <td class="px-6 py-4 border"><?= date("d M Y, h:i A", strtotime($order['created_at'])) ?></td>
                        </tr>
                        
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 py-6">No orders found for this user.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="mt-6">
        <a href="manage_users.php" class="inline-block text-sm text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded shadow">
            ← Back to Manage Users
        </a>
    </div>
    </div>
</div>

<?php include("../include/admin_footer.php"); ?>
