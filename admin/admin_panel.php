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
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent messages
$recentMessages = executeQuery("
    SELECT * FROM contact_messages
    ORDER BY created_at DESC
    LIMIT 10
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
        .top-bar h1 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 0;
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
            flex: 1;
            overflow-y: auto;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 20px;
        }
        .recent-activity {
            max-height: 300px;
            overflow-y: auto;
        }
        .messages-section {
            margin-top: 20px;
        }
        .message-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .message-content {
            margin-top: 10px;
        }
        footer {
            background-color: #153448;
            color: white;
            padding: 20px 0;
            margin-top: auto;
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

    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
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
                            <a href="admin_products.php">
                                <div class="card-body">
                                    <h5 class="card-title">Total Products</h5>
                                    <p class="card-text display-4"><?php echo htmlspecialchars($total_products); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-center h-100">
                            <a href="admin_users.php">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <p class="card-text display-4"><?php echo htmlspecialchars($total_users); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-center h-100">
                            <a href="admin_orders.php">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text display-4"><?php echo htmlspecialchars($total_orders); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body recent-activity">
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
                <div class="card messages-section">
                    <div class="card-header">
                        <h5 class="mb-0">Inbox</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentMessages)): ?>
                            <p>No recent messages.</p>
                        <?php else: ?>
                            <?php foreach ($recentMessages as $message): ?>
                                <div class="message-item">
                                    <div class="message-header">
                                        <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                        <span class="text-muted"><?php echo date('d M Y H:i', strtotime($message['created_at'])); ?></span>
                                    </div>
                                    <div><?php echo htmlspecialchars($message['email']); ?></div>
                                    <div class="message-content"><?php echo nl2br(htmlspecialchars($message['message'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
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

    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
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
</body>
</html>