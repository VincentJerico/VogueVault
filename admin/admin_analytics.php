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

// Fix NULL or invalid created_at dates
$fixDatesQuery = "UPDATE orders SET created_at = NOW() WHERE created_at IS NULL OR created_at = '0000-00-00 00:00:00'";
executeQuery($fixDatesQuery);

// Total Sales
$totalSalesQuery = "SELECT COALESCE(SUM(total_price), 0) AS total_sales FROM orders";
$totalSalesStmt = executeQuery($totalSalesQuery);
$totalSales = $totalSalesStmt->fetch(PDO::FETCH_ASSOC)['total_sales'];

// Daily Sales
$dailySalesQuery = "SELECT DATE(created_at) AS sale_date, COALESCE(SUM(total_price), 0) AS daily_sales FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY sale_date ORDER BY sale_date";
$dailySalesStmt = executeQuery($dailySalesQuery);
$dailySalesData = $dailySalesStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total daily sales
$totalDailySales = array_sum(array_column($dailySalesData, 'daily_sales'));

// Total Products Sold
$totalProductsSoldQuery = "SELECT COALESCE(SUM(quantity), 0) AS total_quantity_sold FROM orders";
$totalProductsSoldStmt = executeQuery($totalProductsSoldQuery);
$totalProductsSold = $totalProductsSoldStmt->fetch(PDO::FETCH_ASSOC)['total_quantity_sold'];

// List of Selling Items with Product Name
$sellingItemsQuery = "
    SELECT 
        p.name, 
        COALESCE(SUM(o.quantity), 0) AS total_sold, 
        p.price, 
        COALESCE(SUM(o.quantity * o.total_price), 0) AS total_sales
    FROM 
        products p
    LEFT JOIN 
        orders o ON p.id = o.product_id AND o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY 
        p.id, p.name, p.price
    ORDER BY 
        total_sales DESC
    LIMIT 10
";
$sellingItemsStmt = executeQuery($sellingItemsQuery);
$sellingItemsData = $sellingItemsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch total sales per month
// Fetch total sales per month
$totalSalesQuery = "
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, 
           COALESCE(SUM(total_price), 0) AS total_sales
    FROM orders
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY month
    ORDER BY month ASC
";
$totalSalesStmt = executeQuery($totalSalesQuery);
$totalSalesData = $totalSalesStmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for monthly sales chart
$months = array_column($totalSalesData, 'month');
$salesData = array_column($totalSalesData, 'total_sales');

// Debug: Print the data
echo '<script>';
echo 'console.log("PHP Months:", ' . json_encode($months) . ');';
echo 'console.log("PHP Sales Data:", ' . json_encode($salesData) . ');';
echo '</script>';

// Handle potential NULL or invalid dates before rendering
foreach ($totalSalesData as &$data) {
    if (is_null($data['month']) || strtotime($data['month']) === false) {
        $data['month'] = 'Unknown Date';
    }
}

// Prepare data for monthly sales chart
$months = array_reverse(array_column($totalSalesData, 'month'));
$salesData = array_reverse(array_column($totalSalesData, 'total_sales'));

// Prepare data for daily sales chart
$days = array_column($dailySalesData, 'sale_date');
$dailySales = array_column($dailySalesData, 'daily_sales');

$pdo = null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault Admin Analytics</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/fonts/poppins.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/logosquaretransparent.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .sidebar {
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
        .search-bar {
        position: relative;
        }
        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 35px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 16px;
        }
        .search-bar .bi-search {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #6c757d;
        }
        #searchResults {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 20px 20px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-result-item {
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        .search-result-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .search-result-description {
            font-size: 14px;
            color: #6c757d;
        }
        .main-content {
            flex: 1 0 auto;
            padding-bottom: 20px;
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
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .table-responsive thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 1;
        }
        footer {
            flex-shrink: 0;
            background-color: #153448;
            color: white;
            padding: 20px 0;
            margin-top: auto;
        }
        footer p {
            color: white;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                position: static;
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
                        <img src="../assets/images/logowhitelctransparent.png" style="max-height: 80px; width: auto;">
                    </a>
                </div>
                <div class="col-md-6 col-lg-8">
                    <div class="search-bar">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        <div id="searchResults" class="search-results"></div>
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
                        <!--<li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-tags-fill me-2"></i>Categories
                            </a>
                        </li>-->
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
                
                <!-- Date display -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Today's Date</h5>
                                <p class="card-text" id="date"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overview cards -->
                <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body bg-primary text-white">
                                <h5 class="card-title" id="firstLayerCard">Total Sales</h5>
                                <p class="card-text display-6" style="color: #e9ecef;">₱<?php echo number_format($totalSales, 2); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body bg-success text-white">
                                <h5 class="card-title" id="firstLayerCard">Daily Sales</h5>
                                <p class="card-text display-6" style="color: #e9ecef;">₱<?php echo number_format($totalDailySales, 2); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body bg-dark text-white">
                                <h5 class="card-title" id="firstLayerCard">Total Products Sold</h5>
                                <p class="card-text display-6" style="color: #e9ecef;"><?php echo number_format($totalProductsSold); ?></p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Sales charts -->
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

                <!-- Top selling items -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Top Selling Items</h5>
                        <?php if (!empty($sellingItemsData)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
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
                            </div>
                        <?php else: ?>
                            <p>No data available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <img src="../assets/images/white-logo.png" alt="VogueVault Logo" style="max-height: 60px;">
                    <p>Copyright © <?php echo date("Y"); ?>. All Rights Reserved.<br>This website is for school project purposes only</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        const searchInput = $('#searchInput');
        const searchResults = $('#searchResults');

        searchInput.on('input', function() {
            const searchTerm = $(this).val().trim();
            if (searchTerm.length > 2) {
                $.ajax({
                    url: 'search.php',
                    method: 'GET',
                    data: { term: searchTerm },
                    dataType: 'json',
                    success: function(results) {
                        displaySearchResults(results);
                    },
                    error: function(xhr, status, error) {
                        console.error('Search error:', error);
                    }
                });
            } else {
                searchResults.hide().empty();
            }
        });

        function displaySearchResults(results) {
            searchResults.empty();
            if (results.length > 0) {
                results.forEach(function(result) {
                    const resultItem = $('<div class="search-result-item">')
                        .append($('<div class="search-result-title">').text(result.title))
                        .append($('<div class="search-result-description">').text(result.description));
                    
                    resultItem.on('click', function() {
                        window.location.href = result.link;
                    });

                    searchResults.append(resultItem);
                });
                searchResults.show();
            } else {
                searchResults.hide();
            }
        }

        $(document).on('click', function(event) {
            if (!$(event.target).closest('.search-bar').length) {
                searchResults.hide();
            }
        });

        // Add keyboard navigation
        searchInput.on('keydown', function(e) {
            const items = searchResults.find('.search-result-item');
            const current = searchResults.find('.search-result-item.active');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (current.length === 0 || current.is(':last-child')) {
                    items.first().addClass('active').siblings().removeClass('active');
                } else {
                    current.removeClass('active').next().addClass('active');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (current.length === 0 || current.is(':first-child')) {
                    items.last().addClass('active').siblings().removeClass('active');
                } else {
                    current.removeClass('active').prev().addClass('active');
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (current.length > 0) {
                    window.location.href = current.data('link');
                }
            }
        });
    });
    </script>
    <script>
        // JavaScript to get the current date and display it
        document.addEventListener('DOMContentLoaded', (event) => {
            const dateElement = document.getElementById('date');
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.textContent = today.toLocaleDateString(undefined, options);
        });
        

        document.getElementById('date').textContent = new Date().toLocaleDateString();

// Monthly Sales Chart
const ctxMonthlySales = document.getElementById('monthlySalesChart').getContext('2d');
const monthlySalesChart = new Chart(ctxMonthlySales, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Monthly Sales',
            data: <?php echo json_encode($salesData); ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Debug: Log the data to console
console.log('Months:', <?php echo json_encode($months); ?>);
console.log('Sales Data:', <?php echo json_encode($salesData); ?>);

        // Daily Sales Chart
        const ctxDailySales = document.getElementById('dailySalesChart').getContext('2d');
                const dailySalesChart = new Chart(ctxDailySales, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode(array_map(function($day) { return date('d M', strtotime($day)); }, $days)); ?>,
                        datasets: [{
                            label: 'Daily Sales',
                            data: <?php echo json_encode($dailySales); ?>,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
    </script>
</body>
</html>
