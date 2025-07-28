<?php
include '../inc/db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get all menu items
$menu_query = "SELECT * FROM menu_items ORDER BY name ASC";
$menu_result = mysqli_query($conn, $menu_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Menu Items - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <!-- Admin Navigation -->
    <nav class="bg-gray-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold">Admin Dashboard</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-300 hover:text-white transition-colors duration-200">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <a href="../index.php" class="text-gray-300 hover:text-white transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>View Site
                    </a>
                    <a href="logout.php" class="text-red-400 hover:text-red-300 transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Menu Items</h1>
            <p class="text-gray-600">Add, edit, or remove items from your menu</p>
        </div>

        <!-- Add New Item Button -->
        <div class="mb-6">
            <button class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="fas fa-plus mr-2"></i>
                Add New Menu Item
            </button>
        </div>

        <!-- Menu Items Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php if (mysqli_num_rows($menu_result) > 0): ?>
                <?php while ($item = mysqli_fetch_assoc($menu_result)): ?>
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative">
                            <img class="w-full h-48 object-cover" 
                                src="../images/<?php echo htmlspecialchars($item['image']); ?>" 
                                alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo ($item['category'] === 'Non-Veg') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                    <i class="fas <?php echo ($item['category'] === 'Non-Veg') ? 'fa-drumstick-bite' : 'fa-leaf'; ?> mr-1"></i>
                                    <?php echo htmlspecialchars($item['category']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-orange-600">
                                    ₹<?php echo number_format($item['price']); ?>
                                </span>
                                <span class="text-sm text-gray-500">
                                    ID: <?php echo $item['id']; ?>
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between">
                                <button class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </button>
                                <button class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                            <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">No menu items yet</h2>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            Start building your menu by adding delicious dishes that customers will love!
                        </p>
                        <button class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <i class="fas fa-plus mr-2"></i>
                            Add First Item
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Statistics -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-utensils text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Menu Items</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo mysqli_num_rows($menu_result); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-leaf text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Vegetarian Items</p>
                        <p class="text-2xl font-bold text-gray-900">
                            <?php 
                            $veg_query = "SELECT COUNT(*) as count FROM menu_items WHERE category = 'Veg'";
                            $veg_result = mysqli_query($conn, $veg_query);
                            echo mysqli_fetch_assoc($veg_result)['count'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-drumstick-bite text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Non-Vegetarian Items</p>
                        <p class="text-2xl font-bold text-gray-900">
                            <?php 
                            $non_veg_query = "SELECT COUNT(*) as count FROM menu_items WHERE category = 'Non-Veg'";
                            $non_veg_result = mysqli_query($conn, $non_veg_query);
                            echo mysqli_fetch_assoc($non_veg_result)['count'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-8 text-center">
            <a href="dashboard.php" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
