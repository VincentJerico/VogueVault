<?php
session_start();
require_once '../includes/connection.php';
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "voguevaultdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch some stats for the dashboard
$productCount = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$userCount = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$orderCount = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="admin-navbar">
            <a href="index.php">Dashboard</a>
            <a href="products.php">Products</a>
            <a href="categories.php">Categories</a>
            <a href="users.php">Users</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <div class="admin-container">
        <h1>Welcome to VogueVault Admin Dashboard</h1>
        <div class="dashboard-stats">
            <div class="stat-box">
                <h3>Total Products</h3>
                <p><?php echo $productCount; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Users</h3>
                <p><?php echo $userCount; ?></p>
            </div>
            <div class="stat-box">
                <h3>Total Orders</h3>
                <p><?php echo $orderCount; ?></p>
            </div>
        </div>
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <a href="add_product.php" class="admin-btn">Add New Product</a>
            <a href="view_orders.php" class="admin-btn">View Recent Orders</a>
        </div>
    </div>
</body>
</html>