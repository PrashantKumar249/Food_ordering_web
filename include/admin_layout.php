<?php
if (!isset($content))
    $content = ''; // fallback

// Admin name fallback
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Khana Khazana</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>

</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- ✅ Top Navbar -->
    <header class="bg-white shadow-md fixed top-0 w-full z-10">
        <div class="max-w-full flex justify-between items-center p-4">
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 focus:outline-none">
                ☰
            </button>
            <h1 class="text-base md:text-xl font-bold text-orange-600">Khana Khazana Admin</h1>
            <div class="text-sm md:text-base text-gray-600 font-medium">
                Welcome, <?= htmlspecialchars($admin_name) ?>
            </div>

        </div>
    </header>

    <!-- ✅ Sidebar -->
    <div class="flex pt-16">
        <aside id="sidebar"
            class="bg-white w-64 h-screen fixed top-16 left-0 z-40 shadow-md transform -translate-x-full lg:translate-x-0 lg:relative lg:top-0 transition-transform duration-200 ease-in-out overflow-y-auto">

            <nav class="flex flex-col py-4 space-y-1">
                <a href="dashboard.php"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-orange-100 hover:text-orange-600 transition rounded">
                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6m-6 0H7v6h6v-6z" />
                    </svg>
                    Dashboard
                </a>
                <a href="manage_orders.php"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-orange-100 hover:text-orange-600 transition rounded">
                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M9 17v-6h13v6M3 7h18M5 11h2a2 2 0 0 1 2 2v6H5v-6a2 2 0 0 1 2-2z" />
                    </svg>
                    Manage Orders
                </a>
                <a href="manage_menu.php"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-orange-100 hover:text-orange-600 transition rounded">
                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Manage Menu
                </a>
                <a href="manage_users.php"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-orange-100 hover:text-orange-600 transition rounded">
                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M5 20h14a2 2 0 0 0 2-2V7H3v11a2 2 0 0 0 2 2z" />
                    </svg>
                    Manage Users
                </a>
                <a href="sales_report.php"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-orange-100 hover:text-orange-600 transition rounded">
                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M11 11V3h2v8h-2zm4 0V5h2v6h-2zM7 11V7h2v4H7zM3 11v10h18V11H3z" />
                    </svg>
                    Sales Report
                </a>
                <a href="stock_alert.php"
                    class="flex items-center px-6 py-3 text-gray-700 hover:bg-orange-100 hover:text-orange-600 transition rounded">
                    <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M12 9v2m0 4h.01M21 12a9 9 0 1 0-18 0 9 9 0 0 0 18 0z" />
                    </svg>
                    Stock Alerts
                </a>
                <a href="logout.php"
                    class="flex items-center px-6 py-3 text-red-600 hover:bg-red-100 transition rounded">
                    <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-8v1m0 4h.01" />
                    </svg>
                    Logout
                </a>
            </nav>
        </aside>


        <!-- ✅ Main Content Area -->
        <main class="flex-1 p-4 w-full">
            <?= $content ?>
        </main>
    </div>

    <!-- ✅ Footer -->
    <footer class="bg-white text-center text-sm text-gray-500 mt-auto py-4 shadow-inner">
        &copy; <?= date("Y") ?> Khana Khazana Admin Panel. All rights reserved.
    </footer>
</body>

</html>