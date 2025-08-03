<?php
session_start();
include("../include/db.php");
include("../include/admin_header.php");
include("../include/admin_sidebar.php");

if (empty($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize default values
$item = [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'category' => '',
    'stock_qty' => '',
    'available' => 1,
    'image' => ''
];

$isEdit = false;

// Edit Mode: Get existing item
if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM menu_items WHERE id = $id");
    if ($query && mysqli_num_rows($query) === 1) {
        $item = mysqli_fetch_assoc($query);
        $isEdit = true;
    } else {
        echo "<p class='text-red-600 p-4'>Invalid item ID.</p>";
        exit();
    }
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = intval($_POST['stock_qty']);
    $available = isset($_POST['available']) ? 1 : 0;

    // Image upload
    $image = $item['image'];
    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $img_name = time() . '_' . basename($_FILES['image']['name']);
        $target = "../assets/images/" . $img_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $img_name;
        }
    }

    if (!empty($_POST['id'])) {
        // Update existing
        $id = (int)$_POST['id'];
        $sql = "UPDATE menu_items SET name='$name', description='$description', price=$price, category='$category',
                stock_qty=$stock, available=$available, image='$image' WHERE id=$id";
        mysqli_query($conn, $sql);
    } else {
        // Insert new
        $sql = "INSERT INTO menu_items (name, description, price, category, stock_qty, available, image)
                VALUES ('$name', '$description', $price, '$category', $stock, $available, '$image')";
        mysqli_query($conn, $sql);
    }

    header("Location: manage_menu.php");
    exit();
}
?>

<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-6">
    <h2 class="text-2xl font-bold mb-4"><?= $isEdit ? '✏️ Edit Menu Item' : '➕ Add New Menu Item' ?></h2>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">

        <div>
            <label class="block mb-1 font-medium">Name</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($item['name']) ?>"
                   class="w-full border px-3 py-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-medium">Description</label>
            <textarea name="description" rows="3" required
                      class="w-full border px-3 py-2 rounded"><?= htmlspecialchars($item['description']) ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium">Price (₹)</label>
                <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($item['price']) ?>"
                       class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block mb-1 font-medium">Stock Quantity</label>
                <input type="number" name="stock_qty" required value="<?= htmlspecialchars($item['stock_qty']) ?>"
                       class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        <div>
            <label class="block mb-1 font-medium">Category</label>
            <select name="category" required class="w-full border px-3 py-2 rounded">
                <option value="">Select Category</option>
                <option value="Veg" <?= $item['category'] === 'Veg' ? 'selected' : '' ?>>Veg</option>
                <option value="Non-Veg" <?= $item['category'] === 'Non-Veg' ? 'selected' : '' ?>>Non-Veg</option>
            </select>
        </div>

        <div class="flex items-center space-x-4">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="available" <?= $item['available'] ? 'checked' : '' ?>>
                <span>Available</span>
            </label>

            <?php if ($item['image']) : ?>
                <img src="../assets/images/<?= $item['image'] ?>" alt="Item Image" class="h-12 rounded">
            <?php endif; ?>
        </div>

        <div>
            <label class="block mb-1 font-medium">Image</label>
            <input type="file" name="image" accept="image/*" class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
            <?= $isEdit ? 'Update Item' : 'Add Item' ?>
        </button>
    </form>
</div>

<?php include("../include/admin_footer.php"); ?>
