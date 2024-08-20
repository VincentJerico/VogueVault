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

$sql = "SELECT * FROM orders";
$stmt = $pdo->query($sql);

if ($stmt === false) {
    die("Error executing the query.");
}

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
    <title>VogueVault Admin - Orders</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/fonts/poppins.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo_Transparent.png">
=======
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
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
            margin-top: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
=======
            margin: 0;
            padding: 0;
        }

        .icon-and-text {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            border-bottom: solid 1px gray;
        }

        .icon-and-text i {
            margin-right: 10px;
        }

        .icon-and-text h3 {
            margin: 0; 
            font-size: 1.5em;
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

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }


        #search {
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.1);
        }

>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
<<<<<<< HEAD
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
=======

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
        th {
            background-color: #f2f2f2;
            color: #333;
        }
<<<<<<< HEAD
        tr:hover {
            background-color: #f5f5f5;
        }
        #search {
            margin-bottom: 20px;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                height: auto;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
=======

        td {
            color: #555;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
        }
    </style>
</head>
<body>
<<<<<<< HEAD
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
                            <a class="nav-link active" href="./admin_orders.php">
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
                    <h2>Orders</h2>
                </div>

                <input type="text" id="search" class="form-control" placeholder="Search Orders..." onkeyup="searchTable()">

                <div class="table-responsive">
                    <table id="ordersTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>User #</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($stmt) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["user_id"]) . "</td>";
                                    echo "<td>₱" . htmlspecialchars($row["total_amount"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                    echo "<td><a href='order_details.php?order_id=" . htmlspecialchars($row["id"]) . "' class='btn btn-sm btn-outline-primary'>View Items</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No orders found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
=======

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
        <i class="bi bi-cart-fill"></i>
        <h3>Orders</h3>
    </div>

        <!-- Search Bar -->
        <input type="text" id="search" placeholder="Search Orders..." onkeyup="searchTable()">
        <table id="ordersTable">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>User #</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt) {
                    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // Output data of each row
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["user_id"] . "</td>";
                        echo "<td>$" . $row["total_amount"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>" . $row["created_at"] . "</td>";
                        echo "<td><a href='order_details.php?order_id=" . $row["id"] . "'>View Items</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found</td></tr>";
                }
            
                ?>
            </tbody>
        </table>



            

    <!-- Bootstrap JS and jQuery for Bootstrap 4 -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // search bar
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
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

        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("ordersTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
<<<<<<< HEAD
                tr[i].style.display = "none";
=======
                tr[i].style.display = "none"; // Hide the row initially
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
<<<<<<< HEAD
                            tr[i].style.display = "";
=======
                            tr[i].style.display = ""; // Show the row if a match is found
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
                            break;
                        }
                    }
                }
            }
        }
<<<<<<< HEAD
    </script>
</body>
</html>
=======
        
    </script>
</body>
</html>
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
