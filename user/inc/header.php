<?php
include "inc/db.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Khana Khazana - Delicious Indian Food Delivery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../assets/favicon/favicon.ico">
    <meta name="description"
        content="Order delicious Indian food online. Fast delivery, fresh ingredients, authentic taste.">
    <meta name="keywords" content="Indian food, food delivery, online ordering, restaurant">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef7ee',
                            100: '#fdedd4',
                            200: '#fbd7a8',
                            300: '#f8bb71',
                            400: '#f5953a',
                            500: '#f3741d',
                            600: '#e45a12',
                            700: '#bd4311',
                            800: '#963614',
                            900: '#792e14',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 text-gray-900 antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand -->
                <div class="flex-shrink-0">
                    <a href="index.php" class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-utensils text-white text-lg"></i>
                        </div>
                        <span
                            class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                            Khana Khazana
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="about.php"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        <i class="fas fa-info-circle mr-1"></i>About
                    </a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Cart with Badge -->
                        <a href="cart.php"
                            class="relative text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-shopping-cart mr-1"></i>Cart
                            <?php
                            // Get cart count
                            $cart_count = 0;
                            if (isset($_SESSION['user_id'])) {
                                $cart_query = "SELECT SUM(quantity) as total FROM cart_items WHERE user_id = " . intval($_SESSION['user_id']);
                                $cart_result = mysqli_query($conn, $cart_query);
                                $cart_data = mysqli_fetch_assoc($cart_result);
                                $cart_count = isset($cart_data['total']) ? $cart_data['total'] : 0;
                            }
                            if ($cart_count > 0):
                                ?>
                                <span
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    <?php echo $cart_count; ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <!-- Profile Dropdown -->
                        <div class="relative" id="profile-dropdown-container">
                            <button id="profile-dropdown-btn" type="button"
                                class="flex items-center space-x-2 text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="profile-dropdown-menu"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200 hidden">
                                <a href="profile.php"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="my_orders.php"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-box mr-2"></i>My Orders
                                </a>
                                <hr class="my-1">
                                <a href="logout.php"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php"
                            class="bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-sign-in-alt mr-1"></i>Login
                        </a>
                        <a href="register.php"
                            class="border border-orange-500 text-orange-600 hover:bg-orange-50 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200">
                            <i class="fas fa-user-plus mr-1"></i>Register
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Toggle -->
                <div class="md:hidden">
                    <button id="mobile-menu-button"
                        class="text-gray-700 hover:text-orange-600 focus:outline-none focus:text-orange-600 transition-colors duration-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="index.php"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="about.php"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-colors duration-200">
                        <i class="fas fa-info-circle mr-2"></i>About
                    </a>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="cart.php"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-colors duration-200">
                            <i class="fas fa-shopping-cart mr-2"></i>Cart
                            <?php if ($cart_count > 0): ?>
                                <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                    <?php echo $cart_count; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <a href="profile.php"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-colors duration-200">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>
                        <a href="my_orders.php"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-colors duration-200">
                            <i class="fas fa-box mr-2"></i>My Orders
                        </a>
                        <a href="logout.php"
                            class="block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-colors duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="register.php"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition-colors duration-200">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- JavaScript for Mobile Menu and Dropdown -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Profile dropdown toggle
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('profile-dropdown-btn');
            const menu = document.getElementById('profile-dropdown-menu');
            const container = document.getElementById('profile-dropdown-container');

            if (btn && menu) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });

                // Hide dropdown when clicking outside
                document.addEventListener('click', function (e) {
                    if (!container.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    </script>