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



$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $sql = "DELETE FROM products WHERE alarm_id = $deleteId";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='centered-message success'>Record deleted successfully</div>";
    } else {
        echo "<div class='centered-message error'>Error deleting record: " . $conn->error . "</div>";
    }
    }  

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
