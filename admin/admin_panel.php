<?php
session_start();
require_once '../includes/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

function executeQuery($query) {
    global $pdo;
    if (!$pdo) {
        die("Database connection not established.");
    }
    $stmt = $pdo->query($query);
    if ($stmt === false) {
        die("Error executing query: " . $pdo->errorInfo()[2]);
    }
    return $stmt;
}

$total_users = executeQuery("SELECT COUNT(*) as count FROM users")->fetch()['count'];
$total_products = executeQuery("SELECT COUNT(*) as count FROM products")->fetch()['count'];
$total_orders = executeQuery("SELECT COUNT(*) as count FROM orders")->fetch()['count'];

$recentActivity = executeQuery("
    SELECT * FROM (
        SELECT 'New User' as type, username as detail, created_at FROM users
        UNION ALL
        SELECT 'New Order' as type, CONCAT('Order ID: ', id) as detail, created_at FROM orders
    ) as activity
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault Admin Dashboard</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/fonts/poppins.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo_Transparent.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background-color: white;
            border-right: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 10px 20px;
        }
        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
        }
        .sidebar .nav-link.active {
            background-color: #e9ecef;
        }
        .top-bar {
            background-color: #153448;
            padding: 10px 0;
        }
        .top-bar h1 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 0;
        }
        .search-bar {
            position: relative;
        }
        .search-bar input {
            padding-left: 30px;
        }
        .search-bar .bi-search {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
        }
        .main-content {
            margin-left: 200px;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                height: auto;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-3 col-lg-2">
                    <a href="admin_panel.php" class="logo">
                        <img src="../assets/images/white-logo.png">
                    </a>
                </div>
                <div class="col-md-6 col-lg-8">
                    <div class="search-bar">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2 text-end">
                    <span class="text-white">Admin</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="./admin_panel.php">
                                <i class="bi bi-house-fill me-2"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./admin_users.php">
                                <i class="bi bi-people-fill me-2"></i>Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-tags-fill me-2"></i>Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./admin_products.php">
                                <i class="bi bi-box me-2"></i>Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./admin_orders.php">
                                <i class="bi bi-cart-fill me-2"></i>Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./admin_analytics.php">
                                <i class="bi bi-bar-chart-fill me-2"></i>Analytics
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="../logout.php" class="btn btn-outline-danger w-100">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Dashboard Overview</h2>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Products</h5>
                                <p class="card-text display-4"><?php echo htmlspecialchars($total_products); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text display-4"><?php echo htmlspecialchars($total_users); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <h5 class="card-title">Total Orders</h5>
                                <p class="card-text display-4"><?php echo htmlspecialchars($total_orders); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recentActivity as $activity): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><strong><?php echo htmlspecialchars($activity['type']); ?>:</strong> <?php echo htmlspecialchars($activity['detail']); ?></span>
                                    <span class="badge bg-primary rounded-pill"><?php echo date('d M Y H:i', strtotime($activity['created_at'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const searchInput = document.getElementById('searchInput');
            const searchBar = document.querySelector('.search-bar');

            searchInput.addEventListener('input', () => {
                if (searchInput.value.trim() === '') {
                    searchBar.classList.remove('expanded');
                } else {
                    searchBar.classList.add('expanded');
                }
            });
        });
    </script>
</body>
</html>