<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['auth_user']) || $_SESSION['auth_user']['role'] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Apply FEU Theme -->
</head>
<body>

    <nav class="navbar">
        <a href="#">Admin Dashboard</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');" class="btn btn-danger">Logout</a>
    </nav>

    <div class="container">
        <div class="form-container">
            <div class="card">
                <div class="card-header">
                    <h2>Welcome, Admin</h2>
                </div>
                <div class="card-body">
                    <p>You have successfully logged in as an Administrator.</p>
                    <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
