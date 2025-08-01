<?php
session_start();
include("inc/db.php");
include("inc/admin_header.php");
include("inc/admin_sidebar.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get search input
$search = $_GET['search'] ?? '';

// Modify SQL query if searching
if (!empty($search)) {
    $sql = "SELECT id, name, email, phone, address, created_at 
            FROM users 
            WHERE name LIKE ? 
            ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Fetch all users
    $sql = "SELECT id, name, email, phone, address, created_at FROM users ORDER BY created_at DESC";
    $result = $conn->query($sql);
}
?>

<div class="p-6 sm:ml-64 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manage Users</h1>

    <!-- 🔍 Search Form -->
    <form method="GET" class="mb-4">
        <div class="flex items-center gap-2">
            <input type="text" name="search" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>"
                   class="px-4 py-2 border border-gray-300 rounded w-64 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Search
            </button>
            <?php if (!empty($search)): ?>
                <a href="manage_users.php" class="text-sm text-gray-600 underline ml-2">Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- 📋 Users Table -->
    <div class="overflow-x-auto shadow rounded-lg">
        <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 border">ID</th>
                    <th class="px-6 py-3 border">Name</th>
                    <th class="px-6 py-3 border">Email</th>
                    <th class="px-6 py-3 border">Phone</th>
                    <th class="px-6 py-3 border">Address</th>
                    <th class="px-6 py-3 border">Registered At</th>
                    <th class="px-6 py-3 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50 border-t">
                            <td class="px-6 py-4 border"><?= htmlspecialchars($row['id']) ?></td>
                            <td class="px-6 py-4 border"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-6 py-4 border"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="px-6 py-4 border"><?= htmlspecialchars($row['phone']) ?></td>
                            <td class="px-6 py-4 border"><?= htmlspecialchars($row['address']) ?></td>
                            <td class="px-6 py-4 border"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                            <td class="px-6 py-4 border text-center">
                                <a href="user_orders.php?user_id=<?= $row['id'] ?>" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-2 rounded">
                                    View Orders
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-6">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("inc/footer.php"); ?>

<!-- ✅ AJAX Script -->
<script>
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.getElementById("userTableBody");

    function fetchUsers(query = "") {
        fetch("search_users.php?search=" + encodeURIComponent(query))
            .then(response => response.text())
            .then(data => {
                tableBody.innerHTML = data;
            });
    }

    // Live search
    searchInput.addEventListener("input", () => {
        fetchUsers(searchInput.value);
    });

    // Initial load
    window.addEventListener("DOMContentLoaded", () => {
        fetchUsers(); // Load all users initially
    });
</script>
