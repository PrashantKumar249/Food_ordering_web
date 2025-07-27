<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
<h2>Admin Login</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Admin Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();
        if ($password === $admin['password']) {
            $_SESSION['admin'] = $admin['id'];
            header("Location: dashboard.php");
            exit;
        }
    }
    echo "Invalid admin credentials.";
}
?>
</body>
</html>
