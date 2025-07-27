<?php
session_start();
include("inc/db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khana Khazana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-gray-800 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Brand Name -->
                <div class="flex-shrink-0 text-2xl font-bold text-yellow-400">
                    <a href="index.php">Khana Khazana</a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-4">
                    <a href="index.php" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Home</a>
                    <a href="about.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">About</a>
                    <?php
                    if(isset($_SESSION['user_id'])): ?>
                   <a href="cart.php" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Cart</a>
                   <a href="#" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Hello <?php echo $_SESSION['username']; ?></a>
                    <?php else: ?>
                       <a href="#" class="px-3 py-2 rounded-md text-sm font-medium bg-gray-900">Hello User</a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Login</a>
                    <?php endif; ?>


                    <a href="admin/index.php" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700">Login as Admin</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden px-2 pt-2 pb-3 space-y-1 bg-gray-700">
            <a href="index.php" class="block px-3 py-2 rounded-md text-base font-medium bg-gray-900">Home</a>
            <a href="about.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-600">About</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-600">Logout</a>
            <?php else: ?>
                <a href="login.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-600">Login</a>
            <?php endif; ?>
            <a href="admin/index.php" class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-600">Login as Admin</a>
        </div>
    </nav>

    <script>
        // Toggle mobile menu
        document.getElementById('mobile-menu-button').addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    
