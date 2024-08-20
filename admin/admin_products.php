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

<<<<<<< HEAD
=======


>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
<<<<<<< HEAD
    $sql = "DELETE FROM products WHERE id = :deleteId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':deleteId', $deleteId, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Record deleted successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting record: " . $stmt->errorInfo()[2] . "</div>";
    }
}  
=======
    $sql = "DELETE FROM products WHERE alarm_id = $deleteId";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='centered-message success'>Record deleted successfully</div>";
    } else {
        echo "<div class='centered-message error'>Error deleting record: " . $conn->error . "</div>";
    }
    }  
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1

$pdo = null;
?>

<<<<<<< HEAD
=======

>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>VogueVault Admin - Products</title>
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
        .product-img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
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
                            <a class="nav-link active" href="./admin_products.php">
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
                    <h2>Products</h2>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus"></i> Add Product
                        </button>
                    </div>
                </div>

                <div class="row">
                    <?php 
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="col-md-6 col-lg-4 mb-4">';
                        echo '<div class="card h-100">';
                        echo '<img src="' . htmlspecialchars($row['image']) . '" class="card-img-top product-img" alt="Product Image">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
                        echo '<p class="card-text"><strong>Price:</strong> ₱' . htmlspecialchars($row['price']) . '</p>';
                        echo '<button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . htmlspecialchars($row['id']) . '"><i class="bi bi-pencil-square"></i> Edit</button>';
                        echo '<button class="btn btn-sm btn-outline-danger" data-id="' . htmlspecialchars($row['id']) . '"><i class="bi bi-trash-fill"></i> Delete</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_product.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="category" name="category" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add your edit form here -->
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script>
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
        
        h4 {
            padding-top: 30px;
            font-size: 15px;
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
        .product-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .product-item {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-item img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .product-details {
            flex: 1;
        }
        .product-details h5 {
            margin: 0 0 10px;
            font-size: 15px;
        }
        .product-details p {
            margin: 0 0 10px;
            font-size: 11px;
        }
        .product-details .price {
            font-weight: bold;
            font-size: 13px;
        }
        .product-details .quantity {
            margin-top: 5px;
            font-size: 11px;
        }
        .btn-custom {
            background-color: #007bff; /* Blue color */
            color: #fff;
            border: none;
        }
        .btn-custom:hover {
            background-color: #0056b3; /* Darker blue color */
            color: #fff;
        }

        label {
            font-size: 15px
        }
        .form-group textarea,input::placeholder {
            font-size: 10px;
            top: 0;
            color: #007bff;
        }
        .form-group input {
            font-size: 10px;
        }
        .modal-title {
            color: white;
            font-size: 15px;
            padding-left: 0;
        }
        .modal-header {
            background-color: #153448;
        }
        #modal-button {
            margin: 2px;
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
        <i class="bi bi-box"></i>
        <h3>Products</h3>
    </div>

        <div class="product-header">
            <div class="dropdown">
                <!-- Corrected data-toggle attribute for Bootstrap 4 -->
                <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Categories
                </button>
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="#">Category 1</a></li>
                    <li><a class="dropdown-item" href="#">Category 2</a></li>
                    <li><a class="dropdown-item" href="#">Category 3</a></li>
                </ul>
            </div>

            <!-- Corrected data-toggle attribute for Bootstrap 4 -->
            <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                <i class="bi bi-plus"></i> Add Product
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title" id="exampleModalLabel">Add Product</h1>
                            <!-- Corrected data-dismiss attribute for Bootstrap 4 -->
                            <button type="button" class="btn-close bi-x" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form action="add_product.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Product Description</label>
                                <textarea name="description" class="form-control" placeholder="Product Description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="text" name="category" class="form-control" placeholder="Category" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" name="price" class="form-control" placeholder="Price" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Product Image</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Add Product</button>
                        </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary btn-sm">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-list">
            <!-- Example Product Item -->
            <div class="product-item">
                <img src="https://via.placeholder.com/120" alt="Product Image">
                <div class="product-details">
                    <h5>Product Name</h5>
                    <p>Description of the product goes here. It can be a brief summary or feature list.</p>
                    <div class="price">$29.99</div>
                    <div class="quantity">Quantity: 10</div>
                </div>
            </div>

            <?php 
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="product-item">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="img" width="200">';
                echo '<div class="product-details">';
                echo '<h5>' . htmlspecialchars($row['name']) . '</h5>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<div class="price">';
                echo '<p>' . htmlspecialchars($row['price']) . '</p>';
                echo '</div>';

                // Add Edit and Delete buttons
                echo '<button id="modal-button" data-toggle="modal" data-target="#editModal" class="btn btn-outline-primary btn-sm" data-id="' . htmlspecialchars($row['id']) . '"><i class="bi bi-pencil-square"></i> </button>';
                echo '<button id="modal-button" class="btn btn-outline-danger btn-sm" data-id="' . htmlspecialchars($row['id']) . '"><i class="bi bi-trash-fill"></i> </button>';

                echo '</div>';
                echo '</div>';
            }
            ?>

            <!-- The Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title" id="editModalLabel">Update Product</h1>
                            <!-- Corrected data-dismiss attribute for Bootstrap 4 -->
                            <button type="button" class="btn-close bi-x" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form action="add_product.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Product Description</label>
                                <textarea name="description" class="form-control" placeholder="Product Description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="text" name="category" class="form-control" placeholder="Category" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" name="price" class="form-control" placeholder="Price" required>
                            </div>
                            <div class="form-group">
                                <label for="image">Product Image</label>
                                <input type="file" name="image" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Add Product</button>
                        </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary btn-sm">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

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
<<<<<<< HEAD
    </script>
</body>
</html>
=======

        
    </script>
</body>
</html>
>>>>>>> 90d4c79b0a080f779b2d0463cb429adb887e2bd1
