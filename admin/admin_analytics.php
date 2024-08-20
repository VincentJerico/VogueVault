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

// Total Sales
$totalSalesQuery = "SELECT SUM(total_amount) AS total_sales FROM orders";
$totalSalesStmt = executeQuery($totalSalesQuery);
$totalSales = $totalSalesStmt->fetch(PDO::FETCH_ASSOC)['total_sales'];

// Daily Sales
$dailySalesQuery = "SELECT DATE(created_at) AS sale_date, SUM(total_amount) AS daily_sales FROM orders GROUP BY sale_date";
$dailySalesStmt = executeQuery($dailySalesQuery);
$dailySalesData = $dailySalesStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total daily sales
$totalDailySales = 0;
foreach ($dailySalesData as $day) {
    $totalDailySales += (float)$day['daily_sales'];
}

// List of Selling Items with Product Name
<<<<<<< HEAD
$sellingItemsQuery = "
=======
$sellingItemsData = [];
$sellingItemsQuery = "
    SELECT p.name, SUM(oi.quantity) AS sold_count
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id
    ORDER BY sold_count DESC
    LIMIT 10
";
// Output the query to check
echo $sellingItemsQuery;

// Query to get top selling items with total sales
$query = "
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
    SELECT p.name, SUM(oi.quantity) AS total_sold, p.price, SUM(oi.quantity * p.price) AS total_sales
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY p.id, p.name, p.price
<<<<<<< HEAD
    ORDER BY total_sales DESC
    LIMIT 10
";
$sellingItemsStmt = executeQuery($sellingItemsQuery);
$sellingItemsData = $sellingItemsStmt->fetchAll(PDO::FETCH_ASSOC);
=======
    ORDER BY total_sales DESC;
";
$stmt = executeQuery($query);
$sellingItemsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1

// Query to get total quantity sold across all products
$totalQuery = "SELECT SUM(quantity) AS total_quantity_sold FROM order_items;";
$totalStmt = executeQuery($totalQuery);
$totalSoldData = $totalStmt->fetch(PDO::FETCH_ASSOC);

$totalQuantitySold = $totalSoldData['total_quantity_sold'];

<<<<<<< HEAD
=======
// Execute the query
$sellingItemsStmt = executeQuery($sellingItemsQuery);
if ($sellingItemsStmt !== false) {
    $sellingItemsData = $sellingItemsStmt->fetchAll(PDO::FETCH_ASSOC);
}

>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
// Fetch total sales per month
$totalSalesQuery = "
    SELECT MONTHNAME(o.created_at) AS month, SUM(oi.quantity * oi.price) AS total_sales
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE YEAR(o.created_at) = YEAR(CURDATE())
    GROUP BY MONTH(o.created_at)
    ORDER BY MONTH(o.created_at)
";
$totalSalesStmt = executeQuery($totalSalesQuery);
$totalSalesData = $totalSalesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch daily sales data
$dailySalesQuery = "
    SELECT DAY(o.created_at) AS day, SUM(oi.quantity * oi.price) AS total_sales
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE MONTH(o.created_at) = MONTH(CURDATE())
    GROUP BY DAY(o.created_at)
    ORDER BY DAY(o.created_at)
";
$dailySalesStmt = executeQuery($dailySalesQuery);
$dailySalesData = $dailySalesStmt->fetchAll(PDO::FETCH_ASSOC);

<<<<<<< HEAD
$pdo = null;

?>

=======


$pdo = null;
?>


>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>VogueVault Admin Analytics</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/fonts/poppins.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo_Transparent.png">
=======
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
<<<<<<< HEAD
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
        .card-body {
            border-radius: 2%;
        }
        .card-title {
            color: #153448;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        #firstLayerCard {
            color: white;
        }
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
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
                            <a class="nav-link" href="./admin_panel.php">
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
                            <a class="nav-link active" href="./admin_analytics.php">
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
                    <h1 class="h2">Sales Overview</h1>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="display">
                            <span class="text">Today's Date:</span>
                            <i class="bi bi-calendar"></i>
                            <div id="date"></div>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body bg-primary text-white">
                                <h5 class="card-title" id="firstLayerCard">Total Sales</h5>
                                <p class="card-text display-6">₱<?php echo number_format($totalSales, 2); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body bg-success text-white">
                                <h5 class="card-title" id="firstLayerCard">Daily Sales</h5>
                                <p class="card-text display-6">₱<?php echo number_format($totalDailySales, 2); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body bg-dark text-white">
                                <h5 class="card-title" id="firstLayerCard">Total Products Sold</h5>
                                <p class="card-text display-6"><?php echo number_format($totalQuantitySold); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Monthly Sales Overview</h5>
                                <div class="chart-container">
                                    <canvas id="monthlySalesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Daily Sales (Last 30 Days)</h5>
                                <div class="chart-container">
                                    <canvas id="dailySalesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body bg-dark text-white">
                        <h5 class="card-title" id="firstLayerCard">Top Selling Items</h5>
                        <?php if (!empty($sellingItemsData)): ?>
                            <table class="table table-striped table-dark">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity Sold</th>
                                        <th>Price</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sellingItemsData as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td><?php echo number_format($item['total_sold']); ?></td>
                                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                            <td>₱<?php echo number_format($item['total_sales'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No data available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/chart.js"></script>
=======
            margin: 0;
            padding: 0;
        }

        .icon-and-text {
            display: flex;
            align-items: center; /* Aligns items vertically centered */
            margin-bottom: 5px;
            border-bottom: solid 1px gray;
        }
        .icon-and-text i, .icon-and-text h3 {
            margin-right: 5px; /* Optional: space between icon and text */
            font-size: 1.5rem;
        }

        .top-bar {
            width: 100%;
            background-color: #153448;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1001;
            position: fixed;
            top: 0;
        }

        .top-bar .logo img {
            height: 50px;
            width: 100%;
            padding: 20px;
        }

        .top-bar .search-bar {
            flex-grow: 1;
            margin-left: 20px;;
            display: flex;
            align-items: center;
            position: relative;
        }

        .top-bar .search-bar input {
            width: 80%;
            padding: 8px 15px 8px 40px;
            border-radius: 5px;
            border: none;
            outline: none;
            opacity: 100%;
            font-size: 12px;
        }

        .top-bar .search-bar input::placeholder {
            color: gray;
            opacity: 100%
        }

        .top-bar .search-bar .bi-search {
            position: absolute;
            left: 15px;
            color: #aaa;
        }

        .top-bar .admin-info {
            margin-left: 20px;
            color: white;
        }

        .search-bar {
            position: relative;
            flex: 1; 
            margin: 0 20px; 
            transition: flex 0.3s ease; 
        }
        .search-bar i {
            position: absolute;
            left: 10px; 
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .search-bar input {
            width: 100%;
            padding-left: 35px; 
            transition: padding-left 0.3s ease; 
        }
        .search-bar.expanded input {
            padding-left: 10px;
        }
        .search-bar.expanded i {
            transform: translateX(-100%); 
            opacity: 0;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 60px;
            left: 0;
            width: 200px;
            background-color: white;
            z-index: 1000;
            border-right: solid 1px gray;
            padding-top: 20px;
            flex-direction: column;
            display: flex;
            justify-content: space-between;
            padding-top: 20px;
            padding-bottom: 20px;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.2); /* Shadow effect */
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            flex-grow: 1;
        }

        .sidebar ul li {
            padding-left: 15px;
            padding-bottom: 10px;
            text-align: left;
            text-direction: none;
            display: block;
        }

        .sidebar ul li a {
            color: black;
            text-decoration: none;
            display: block;
            font-size: 13px;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .sidebar ul li.logout {
            margin-top: auto;
        }

        .sidebar ul li:hover {
            border-radius: 5px;
            transform: translateX(10px);
        }
        .sidebar ul li a:hover {
            color: #153448;
        }

        h1 {
            font-size: 11px; /* Adjust font size as needed */
            font-weight: 50;
            color: white;
            padding-left: 15px;
        }
        h2 {
            font-size: 12px;
            font-weight: light;
            color: gray;
            display: block;
            text-decoration: none;
            padding-left: 15px;
            padding-bottom: 10px;
            padding-top: 15px;
        }
        .card {
            margin-bottom: 20px; /* Optional: Add margin below each card */
        }
        .card-title {
            border-bottom: solid 1px lightgray;

        }

        .container {
            margin-left: 250px;
            margin-top: 70px; /* Adjust according to the height of the top bar */
            padding: 10px;
            width: 80%;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
                margin-top: 60px;
            }

            .sidebar ul li {
                text-align: center;
            }

            .top-bar .search-bar {
                margin-left: 0;
            }
        }

        @media (max-width: 480px) {
            .top-bar .search-bar {
                display: none;
            }

            .sidebar ul li {
                text-align: center;
            }

            h2 {
                align-items: center;
            }
        }

        .logout-container {
            margin-top: auto;
        }
        .sidebar ul li.logout a:hover {
            color: red;
        }
        .sidebar ul li.logout:hover {
            color: red;
        }

        .product-section {
            padding: 10px;
        }
        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .dropdown {
            position: relative;
        }
        .dropdown-menu {
            width: 200px; /* Adjust width if needed */
        }

        .display {
            font-size: 12px;
            color: #333;
            display: flex;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .display i {
            margin: 0 10px; /* Space around the calendar icon */
            color: #333; /* Icon color */
        }
        .display .text {
            margin-right: 10px; /* Space between text and icon */
        }
        canvas {
            width: 100%;
            height: 400px;
        }
        .chart-container {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 5px 0;
        }
        #selling-items {
            margin-top: 20px;
        }
        .chart-container {
            padding: 10px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px; /* Adjust if needed */
            height: auto;
        }
        .chart-container canvas {
            width: 100% !important; /* Make sure the canvas fills the container */
            height: auto !important; /* Allow the height to adjust based on the container */
            max-height: 400px; /* Set a maximum height to prevent overly large charts */
        }
        .card-body h5, p, li {
            color: white;
        }
        #top-selling{
            border-bottom: none;
        }

    </style>
</head>
<body>

    <div class="top-bar">
        <div class="logo">
            <h1>VogueVault Admin Dashboard</h1>
        </div>
        <div class="search-bar">
            <i class="bi bi-search"></i> <!-- Search icon -->
            <input type="text" id="searchInput" placeholder="Search...">
        </div>
        <div class="admin-info">
            <!-- <?php echo $admin_name; ?> -->
        </div>
    </div>

    <div class="sidebar">
        <ul>
            <li><a href="./admin_pannel.php"><i class="bi bi-house-fill"></i> Home</a></li>
            <li><a href="./admin_users.php"><i class="bi bi-people-fill"></i> Users</a></li>
            <li><a href="#"><i class="bi bi-tags-fill"></i> Categories</a></li>
            <li><a href="./admin_products.php"><i class="bi bi-box"></i> Products</a></li>
            <li><a href="./admin_orders.php"><i class="bi bi-cart-fill"></i> Orders</a></li>
            
            <h2>Sales Channel</h2>
            <li><a href="./admin_analytics.php"><i class="bi bi-bar-chart-fill"></i> Analytics</a></li>

            <li class="logout"><a href="#"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </div>

    

    <div class="container">
        <div class="icon-and-text">
            <i class="bi bi-bar-chart-fill"></i>
            <h3>Sales Overview</h3>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="display">
                    <span class="text">Today's Date:</span>
                    <i class="bi bi-calendar"></i>
                    <div id="date"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Total Sales -->
            <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-primary text-white">
                            <h5 class="card-title">Total Sales</h5>
                            <p class="card-text">$<?php echo number_format($totalSales, 2); ?></p>
                        </div>
                    </div>
            </div>

            <!-- Daily Sales Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body bg-success text-white">
                        <h5 class="card-title">Daily Sales</h5>
                        <p class="card-text">$<?php echo number_format($totalDailySales, 2); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Product Sold -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body bg-dark text-white">
                        <h5 class="card-title">Product Sold</h5>
                        <p class="card-text"><?php echo number_format($totalQuantitySold); ?></p>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <!-- Sales Overview Chart -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="card-title">Monthly Sales Overview</h5>
                    <canvas id="totalSalesChart"></canvas>
                </div>
            </div>

            <!-- Daily Sales Chart -->
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="card-title">Daily Sales Overview</h5>
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Selling Items -->
        <div id="selling-items" class="col-md-4">
            <div class="card">
                <div class="card-body bg-dark text-white">
                    <h5 id="top-selling"class="card-title">Top Selling Items</h5>
                    <?php if (!empty($sellingItemsData)): ?>
                        <table class="table table-striped table-dark">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity Sold</th>
                                    <th>Price</th>
                                    <th>Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sellingItemsData as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo number_format($item['sold_count']); ?></td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['total_sales'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    
    </div>

    <!-- Bootstrap JS and jQuery for Bootstrap 4 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
    <script>
        // search bar
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
        
        // JavaScript to get the current date and display it
        document.addEventListener('DOMContentLoaded', (event) => {
            const dateElement = document.getElementById('date');
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.textContent = today.toLocaleDateString(undefined, options);
        });
        

        document.getElementById('date').textContent = new Date().toLocaleDateString();

<<<<<<< HEAD
// Monthly Sales Chart
const ctxMonthlySales = document.getElementById('monthlySalesChart').getContext('2d');
const monthlySalesChart = new Chart(ctxMonthlySales, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($totalSalesData, 'month')); ?>,
        datasets: [{
            label: 'Monthly Sales',
            data: <?php echo json_encode(array_column($totalSalesData, 'total_sales')); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Daily Sales Chart
const ctxDailySales = document.getElementById('dailySalesChart').getContext('2d');
const dailySalesChart = new Chart(ctxDailySales, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($dailySalesData, 'day')); ?>,
        datasets: [{
            label: 'Daily Sales',
            data: <?php echo json_encode(array_column($dailySalesData, 'total_sales')); ?>,
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
    </script>
</body>
</html>
=======
        // Total Sales Chart
        const ctxTotalSales = document.getElementById('totalSalesChart').getContext('2d');
        const totalSalesChart = new Chart(ctxTotalSales, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($totalSalesData, 'month')); ?>,
                datasets: [{
                    label: 'Total Sales',
                    data: <?php echo json_encode(array_column($totalSalesData, 'total_sales')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Daily Sales Chart
        const ctxDailySales = document.getElementById('dailySalesChart').getContext('2d');
        const dailySalesChart = new Chart(ctxDailySales, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($dailySalesData, 'day')); ?>,
                datasets: [{
                    label: 'Daily Sales',
                    data: <?php echo json_encode(array_column($dailySalesData, 'total_sales')); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


    </script>
</body>
</html>
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
