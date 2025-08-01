<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Dashboard | Khana Khazana</title>
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/favicon/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 antialiased">
    <!-- Admin Navbar -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow z-50 border-b border-gray-200">
        <div class="max-w-9xl mx-auto px-6 flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div class="text-orange-600 text-2xl">🍽️</div>
                <span class="text-xl font-semibold text-red-600">Khana Khazana</span>
            </div>

            <!-- Nav Links -->
            <div class="flex items-center space-x-6">
                <a href="dashboard.php"
                    class="text-sm text-gray-700 hover:text-orange-600 font-medium flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l9-9 9 9M4 10v10h16V10" />
                    </svg>
                    <span>Home</span>
                </a>

                <span class="text-sm font-semibold text-gray-800">
                    Hello, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>
                </span>
            </div>
        </div>
    </nav>
</body>

</html>