<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if all fields are filled
    if (empty($fullName) || empty($email) || empty($password) || empty($role)) {
        echo "All fields are required!";
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $table = ($role == 'user') ? 'users' : (($role == 'admin') ? 'admins' : 'vendors');

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        echo "Email is already registered in this role.";
        exit;
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO $table (fullName, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
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
        <h2>Create Account</h2>
        <form action="create_account.php" method="post">
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="vendor">Vendor</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fullName">Full Name:</label>
                <input type="text" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Create Account</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Event Management. All Rights Reserved.
    </footer>
</body>
</html>
