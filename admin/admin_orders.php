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

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault Admin - Orders</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/fonts/poppins.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/logosquaretransparent.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrap {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
            background-color: #153448; /* Match your design */
            color: white;
            padding: 20px 0;
        }

        .footer-content {
            text-align: center;
        }

        .footer-content .logo img {
            max-height: 60px;
        }

        .footer-content p {
            color: white;
            font-size: 0.9rem;
        }

        /* Adjust the main content area to account for the footer */
        main {
            padding-bottom: 20px; /* Add some space above the footer */
        }
        .sidebar {
            height: auto;
            max-height: calc(100vh - 100px); /* Adjust based on your top bar height */
            overflow-y: auto;
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
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            max-height: 400px; /* Adjust this value as needed */
            overflow-y: auto;
        }

        .table-container table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-container thead th {
            position: sticky;
            top: 0;
            background-color: #f2f2f2;
            z-index: 1;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
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
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="content-wrap">
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

                        <div class="table-container">
                            <table id="ordersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>User #</th>
                                        <th>Total Price</th>
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
                                            echo "<td>₱" . htmlspecialchars($row["total_price"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                            echo "<td><button class='btn btn-sm btn-outline-primary view-order' data-order-id='" . htmlspecialchars($row["id"]) . "'>View Items</button></td>";
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
        </div>
        <!-- ***** Footer Start ***** -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-content">
                            <div class="logo">
                                <img src="../assets/images/white-logo.png" alt="VogueVault Logo">
                            </div>
                            <p>Copyright © <?php echo date("Y"); ?>. All Rights Reserved. <br> This website is for school project purposes only</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Order details will be loaded here -->
            </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
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

        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("ordersTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }

        $(document).ready(function() {
            $('.view-order').on('click', function() {
                var orderId = $(this).data('order-id');
                
                $.ajax({
                    url: 'get_order_details.php',
                    method: 'GET',
                    data: { order_id: orderId },
                    success: function(response) {
                        $('#orderDetailsContent').html(response);
                        $('#orderDetailsModal').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX error: " + textStatus + ' : ' + errorThrown);
                        alert('Error fetching order details');
                    }
                });
            });
        });
    </script>
</body>
</html>
