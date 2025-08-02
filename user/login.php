<?php
 ob_start(); // Start output buffering
include 'inc/db.php';
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $pass);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $row = $res->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['name'];
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Incorrect email or password.";
    }
    $stmt->close();
     ob_end_flush(); // Flush output at the end
}
?>

<section
    class="min-h-screen bg-gradient-to-br from-orange-50 via-red-50 to-orange-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full mb-6">
                <i class="fas fa-utensils text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                Welcome Back
            </h2>
            <p class="text-gray-600">
                Sign in to your account to continue ordering delicious food
            </p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <?php if (isset($error_message)): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <span class="text-red-700 text-sm"><?php echo htmlspecialchars($error_message); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-6" method="post">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-orange-500"></i>Email Address
                    </label>
                    <input type="email" name="email" id="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                        placeholder="Enter your email address">
                </div>
                <!-- Password Field -->
                <div class="relative">
    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
        <i class="fas fa-lock mr-2 text-orange-500"></i>Password
    </label>

    <input type="password" name="password" id="password" required
        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
        placeholder="Enter your password">

    <!-- Eye Icon (Toggle Button) -->
    <button type="button" id="togglePassword"
        class="absolute inset-y-0 right-0 flex items-center px-3 pt-7 text-gray-500 hover:text-orange-500 focus:outline-none">
        <i class="fas fa-eye"></i>
    </button>
</div>
<script>
    const passwordInput = document.getElementById("password");
    const toggleButton = document.getElementById("togglePassword");
    const icon = toggleButton.querySelector("i");

    toggleButton.addEventListener("click", () => {
        const isVisible = passwordInput.type === "text";
        passwordInput.type = isVisible ? "password" : "text";
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    });
</script>

                <!-- Remember Me and Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember"
                            class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-sm text-orange-600 hover:text-orange-500 transition-colors duration-200">
                        Forgot password?
                    </a>
                </div>
                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </form>
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>
            <!-- Social Login Buttons -->
            <div class="grid grid-cols-2 gap-3">
                <button
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    <i class="fab fa-google text-red-500 mr-2"></i>
                    Google
                </button>
                <button
                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    <i class="fab fa-facebook text-blue-600 mr-2"></i>
                    Facebook
                </button>
            </div>
            <!-- Register Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="register.php"
                        class="font-medium text-orange-600 hover:text-orange-500 transition-colors duration-200">
                        Sign up here
                    </a>
                </p>
            </div>
        </div>
        <!-- Back to Home -->
        <div class="text-center">
            <a href="index.php" class="text-sm text-gray-600 hover:text-orange-600 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-1"></i>
                Back to Home
            </a>
        </div>
    </div>
</section>

<?php include 'inc/footer.php'; ?>
</rewritten_file>