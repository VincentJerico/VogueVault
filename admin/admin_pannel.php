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
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
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
        
        h3 {
            padding-top: 30px;
            margin-bottom: 5px;
            border-bottom: solid 1px gray;
        }

        .container {
            float: right;
            margin-left: 0;
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

        .container {
            max-width: 1140px;
            margin-top: 60px;
            padding: 20px;
            padding-left: 13%;
        }
        .card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card h5 {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .card-body {
            padding: 20px;
        }
        .display-4 {
            font-size: 2rem;
            font-weight: 700;
        }
        

        .display {
            font-size: 12px;
            color: #333;
            display: flex;
            padding-top: 30px;
            padding-bottom: 10px;
        }
        .display i {
            margin: 0 10px; /* Space around the calendar icon */
            color: #333; /* Icon color */
        }
        .display .text {
            margin-right: 10px; /* Space between text and icon */
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
    <h3>Overview dashboard</h3>
    <div class="col-md-4">
        <div class="display">
            <span class="text">Today's Date:</span>
            <i class="bi bi-calendar"></i>
            <div id="date"></div>
        </div>
    </div>
        <!-- Content goes here -->


        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Products</h5>
                        <p class="display-4"><?php echo htmlspecialchars($total_products); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Users</h5>
                        <p class="display-4"><?php echo htmlspecialchars($total_users); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Orders</h5>
                        <p class="display-4"><?php echo htmlspecialchars($total_orders); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
        <div class="display" id="display">
            <span class="text">Recent Activity</span>
            <i class="bi bi-bell"></i>
        </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($recentActivity as $activity): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><strong><?php echo htmlspecialchars($activity['type']); ?>:</strong> <?php echo htmlspecialchars($activity['detail']); ?></span>
                                    <span class="text-muted small"><?php echo date('d M Y H:i', strtotime($activity['created_at'])); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>




    <script>
        // JavaScript to get the current date and display it
        document.addEventListener('DOMContentLoaded', (event) => {
            const dateElement = document.getElementById('date');
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.textContent = today.toLocaleDateString(undefined, options);
        });
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
