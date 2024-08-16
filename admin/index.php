<?php
session_start();
require_once '../includes/connection.php'; // Include your database connection file

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Function to execute queries
function executeQuery($query) {
    global $conn;
    $result = $conn->query($query);
    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }
    return $result;
}

// Fetch some basic stats for the dashboard
$total_users = executeQuery("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_products = executeQuery("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_orders = executeQuery("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];

// Fetch recent activity
$recentActivity = executeQuery("
    SELECT * FROM (
        SELECT 'New User' as type, username as detail, created_at FROM users
        UNION ALL
        SELECT 'New Order' as type, CONCAT('Order ID: ', id) as detail, created_at FROM orders
    ) as activity
    ORDER BY created_at DESC
    LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/owl-carousel.css">
    <link rel="stylesheet" href="../assets/css/lightbox.css">
    <link rel="stylesheet" href="style.css"> <!-- Add your custom CSS here -->
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo_Transparent.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Add Chart.js -->
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-info fixed-top">
            <a href="index.php" class="logo mr-2">
                <img src="../assets/images/Logo_Transparent.png">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Dashboard Overview</h1>
            </div>

            <div class="col-md-4">
                <div class="card bg-light shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text display-4"><?php echo htmlspecialchars($total_products); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text display-4"><?php echo htmlspecialchars($total_users); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text display-4"><?php echo htmlspecialchars($total_orders); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recentActivity as $activity): ?>
                                <li class="list-group-item">
                                    <strong><?php echo htmlspecialchars($activity['type']); ?>:</strong> <?php echo htmlspecialchars($activity['detail']); ?>
                                    <span class="float-right text-muted"><?php echo date('d M Y H:i', strtotime($activity['created_at'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Sales Overview</h5>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <a href="add_product.php" class="btn btn-primary btn-lg btn-block">Add New Product</a>
            </div>
            <div class="col-md-6">
                <a href="view_orders.php" class="btn btn-success btn-lg btn-block">View Recent Orders</a>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../assets/js/jquery-2.1.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- Plugins -->
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/accordions.js"></script>
    <script src="../assets/js/datepicker.js"></script>
    <script src="../assets/js/scrollreveal.min.js"></script>
    <script src="../assets/js/waypoints.min.js"></script>
    <script src="../assets/js/jquery.counterup.min.js"></script>
    <script src="../assets/js/imgfix.min.js"></script> 
    <script src="../assets/js/slick.js"></script> 
    <script src="../assets/js/lightbox.js"></script> 
    <script src="../assets/js/isotope.js"></script>
    <!-- Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                    datasets: [{
                        label: 'Sales',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    <!-- Global Init -->
    <script src="../assets/js/custom.js"></script>
</body>
</html>
