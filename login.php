<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit();
}

$email = "";
$password = "";
$error = "";
$role = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT * FROM $role WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $role;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo"><h1>Event Management</h1></div>
        <div class="header-options">
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="contact_us.html">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="users">User</option>
                    <option value="admins">Admin</option>
                    <option value="vendors">Vendor</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="create_account.php">Create one</a></p>
        <p><a href="forgot_password.php">Forgot Password?</a></p>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Event Management. All Rights Reserved.
    </footer>
</body>
</html>
