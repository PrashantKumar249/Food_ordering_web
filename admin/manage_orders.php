<?php
session_start(); // ✅ Always start session at the top
include("../include/db.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

ob_start(); // ✅ Start buffering after session check

// Get status filter
$status_filter = $_GET['status'] ?? 'all';
$status_sql = ($status_filter !== 'all') ? "WHERE o.status = '$status_filter'" : '';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total records
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

<!-- ✅ Flash Toast Message -->
<?php
$toastMessage = '';
if (isset($_SESSION['flash_message'])) {
    $toastMessage = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}
?>

<style>
@keyframes slideIn {
    from { opacity: 0; transform: translateX(100%); }
    to { opacity: 1; transform: translateX(0); }
}
.animate-slide-in { animation: slideIn 0.3s ease-out; }
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
     class="fixed bottom-5 right-5 bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded shadow-lg z-50 animate-slide-in">
    ✅ <?= htmlspecialchars($toastMessage) ?>
</div>
<?php endif; ?>

<!-- ✅ Orders Section -->
<h1 class="text-3xl font-bold mb-6">📦 Manage Orders</h1>

<!-- Filter -->
<div class="mb-4">
    <form method="GET" class="flex flex-wrap gap-2">
        <select name="status" class="p-2 border rounded">
            <?php
            $statuses = ['all', 'Pending', 'Preparing', 'Dispatched', 'Delivered', 'Cancelled'];
            foreach ($statuses as $s):
                $selected = ($status_filter === $s) ? 'selected' : '';
                echo "<option value=\"$s\" $selected>" . ucfirst($s) . "</option>";
            endforeach;
            ?>
        </select>
        <button type="submit" class="bg-orange-500 text-black px-4 py-2 rounded">Filter</button>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-xl shadow p-4 overflow-x-auto">
    <table class="w-full text-sm min-w-[700px]">
        <thead>
        <tr class="border-b font-semibold">
            <th class="p-2 text-left">Order ID</th>
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
                        <small><?= htmlspecialchars($row['email']) ?></small>
                    </td>
                    <td class="p-2 text-center">₹<?= $row['total_amount'] ?? '0.00' ?></td>
                    <td class="p-2 text-center">
                        <form method="POST" action="update_order_status.php">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="status" class="border p-1 rounded text-sm">
                                <?php foreach ($statuses as $s):
                                    if ($s === 'all') continue; ?>
                                    <option value="<?= $s ?>" <?= strtolower($row['status']) == strtolower($s) ? 'selected' : '' ?>>
                                        <?= ucfirst($s) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="text-orange-600 text-sm ml-2">✔</button>
                        </form>
                    </td>
                    <td class="p-2 text-center"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                    <td class="p-2 text-center">
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

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
    <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&status=<?= $status_filter ?>" class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded">Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>&status=<?= $status_filter ?>"
               class="px-3 py-1 rounded <?= ($i == $page) ? 'bg-orange-500 text-black' : 'bg-gray-200 hover:bg-gray-300'; ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>&status=<?= $status_filter ?>" class="px-3 py-1 bg-gray-300 hover:bg-gray-400 rounded">Next</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
// 🧩 Final output for layout
$content = ob_get_clean();
include("../include/admin_layout.php");
