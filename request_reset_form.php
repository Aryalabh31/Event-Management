<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Password Reset</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-options">
            <h1>Event Management</h1>
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="contact_us.html">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Request Password Reset</h2>
        <form action="request_reset.php" method="post">
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="vendor">Vendor</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn">Request Reset</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Event Management</p>
    </footer>
</body>
</html>
