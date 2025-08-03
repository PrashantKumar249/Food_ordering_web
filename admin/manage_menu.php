<?php
session_start();
include("../include/db.php");
include("../include/admin_header.php");
include("../include/admin_sidebar.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM menu_items WHERE id = $delete_id");
    header("Location: manage_menu.php");
    exit();
}

// Fetch all menu items
$result = mysqli_query($conn, "SELECT * FROM menu_items ORDER BY id DESC");
?>

<div class="ml-64 p-4">
    <h2 class="text-2xl font-bold mb-4">🍽 Manage Menu Items</h2>

    <!-- Add New Button -->
    <div class="mb-6">
        <a href="edit_menu_item.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ➕ Add New Menu Item
        </a>
    </div>

    <!-- Display Existing Items -->
    <div class="bg-white shadow-md rounded p-4">
        <h3 class="text-xl font-semibold mb-4">📋 Menu Items</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border">
                <thead>
                    <tr class="bg-gray-100 text-xs uppercase">
                        <th class="px-3 py-2">Image</th>
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Category</th>
                        <th class="px-3 py-2">Price</th>
                        <th class="px-3 py-2">Stock</th>
                        <th class="px-3 py-2">Available</th>
                        <th class="px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="border-t">
                            <td class="px-3 py-2">
                                <?php if ($row['image']): ?>
                                    <img src="../assets/images/<?= $row['image'] ?>" class="h-12 w-12 object-cover rounded">
                                <?php else: ?>
                                    <span class="text-gray-400">No image</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['name']) ?></td>
                            <td class="px-3 py-2"><?= htmlspecialchars($row['category']) ?></td>
                            <td class="px-3 py-2">₹<?= $row['price'] ?></td>
                            <td class="px-3 py-2"><?= $row['stock_qty'] ?></td>
                            <td class="px-3 py-2">
                                <?php $is_available = ($row['available'] && $row['stock_qty'] > 0); ?>
                                <span class="<?= $is_available ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $is_available ? 'Yes' : 'No' ?>
                                </span>
                            </td>
                            <td class="px-3 py-2">
                                <a href="edit_menu_item.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                                |
                                <a href="?delete=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this item?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../include/admin_footer.php"); ?>
