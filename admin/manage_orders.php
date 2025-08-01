<?php

include("inc/db.php");
include("inc/admin_header.php");
include("inc/admin_sidebar.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get status filter
$status_filter = $_GET['status'] ?? 'all';
$status_sql = ($status_filter !== 'all') ? "WHERE o.status = '$status_filter'" : '';

// Pagination setup
$limit = 10; // orders per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total records
$total_sql = "SELECT COUNT(*) as total FROM orders o $status_sql";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch paginated orders
$sql = "SELECT o.*, u.name AS user_name, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        $status_sql 
        ORDER BY o.created_at DESC 
        LIMIT $limit OFFSET $offset";
$orders = mysqli_query($conn, $sql);
?>
<?php
$toastMessage = '';
if (isset($_SESSION['flash_message'])) {
    $toastMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']); // ✅ Clear after displaying once
}
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
        class="fixed bottom-5 right-5 bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded shadow-lg z-50 animate-slide-in">
        ✅ <?= htmlspecialchars($toastMessage) ?>
    </div>
<?php endif; ?>

<div class="ml-64 mt-16 p-6">
    <h1 class="text-3xl font-bold mb-6">📦 Manage Orders</h1>

    <!-- Filter -->
    <div class="mb-4">
        <form method="GET" class="flex gap-2">
            <select name="status" class="p-2 border rounded">
                <option value="all" <?= $status_filter == 'all' ? 'selected' : '' ?>>All</option>
                <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Preparing" <?= $status_filter == 'Preparing' ? 'selected' : '' ?>>Preparing</option>
                <option value="Dispatched" <?= $status_filter == 'Dispatched' ? 'selected' : '' ?>>Dispatched</option>
                <option value="Delivered" <?= $status_filter == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                <option value="Cancelled" <?= $status_filter == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded">Filter</button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Order Id</th>
                    <th class="p-2 text-left">User</th>
                    <th class="p-2">Amount</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Date</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($orders) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2"><?= $row['id'] ?></td>
                            <td class="p-2">
                                <?= htmlspecialchars($row['user_name']) ?><br>
                                <small><?= $row['email'] ?></small>
                            </td>
                            <td class="p-2 text-center">₹<?= $row['total_amount'] ?? '0.00' ?></td>
                            <td class="p-2 text-center">
                                <form method="POST" action="update_order_status.php">
                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                    <select name="status" class="border p-1 rounded text-sm">
                                        <?php
                                        $statuses = ['Pending', 'Preparing', 'Dispatched', 'Delivered', 'Cancelled'];
                                        foreach ($statuses as $s):
                                            ?>
                                            <option value="<?= $s ?>" <?= strtolower($row['status']) == strtolower($s) ? 'selected' : '' ?>>
                                                <?= ucfirst($s) ?>
                                            </option>

                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="text-orange-600 text-sm ml-2">✔</button>
                                </form>
                            </td>
                            <td class="p-2 text-center"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                            <td class="p-2 text-center space-x-2">
                                <a href="order_details.php?order_id=<?= $row['id'] ?>&user_id=<?= $row['user_id'] ?>" class="text-blue-600">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center p-4 text-gray-500">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
        <div class="mt-6 flex justify-center space-x-2 text-sm">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&status=<?= $status_filter ?>"
                    class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded">Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&status=<?= $status_filter ?>"
                    class="px-3 py-1 rounded <?= ($i == $page) ? 'bg-orange-500 text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&status=<?= $status_filter ?>"
                    class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include("inc/footer.php"); ?>