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

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 12; // Number of products per page
$start = ($page - 1) * $perPage;

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalPages = ceil($totalProducts / $perPage);

$sql = "SELECT * FROM products LIMIT $start, $perPage";
$stmt = $pdo->query($sql);

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault Admin - Products</title>
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
            padding-bottom: 20px; /* Add some padding at the bottom */
        }
        .product-img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .pagination {
            display: inline-flex;
            padding-left: 0;
            list-style: none;
        }

        .page-item:not(:first-child) .page-link {
            margin-left: -1px;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
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

    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
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
                        echo '<div class="col-md-4 col-lg-3 mb-4">';
                        echo '<div class="card h-100">';
                        echo '<img src="' . htmlspecialchars($row['image']) . '" class="card-img-top product-img mt-2 ml-4" alt="Product Image">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                        echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
                        echo '<p class="card-text"><strong>Price:</strong> ₱' . htmlspecialchars($row['price']) . '</p>';
                        echo '<button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . htmlspecialchars($row['id']) . '"><i class="bi bi-pencil-square"></i> Edit</button>';
                        echo "<button class='btn btn-sm btn-outline-danger' onclick=\"deleteRecord(event, " . htmlspecialchars($row['id']) . ")\"><i class='bi bi-trash-fill'></i> Delete</button>";
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <nav aria-label="Product pagination" class="mt-4">
                    <div class="d-flex justify-content-center">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </nav>
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
                    <form id="editProductForm" action="edit_product.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="editProductId" name="id"> <!-- Hidden field for product ID -->
                        <div class="mb-3">
                            <label for="editName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="editCategory" name="category" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="editPrice" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="editImage" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="editImage" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Footer Start ***** -->
    <footer class="mt-auto">
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
    <script src="./alerts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        function deleteRecord(event, id) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this product?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_product.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        if (xhr.responseText.trim() === 'Record deleted successfully') {
                            event.target.closest('.col-md-6').remove(); // Adjust this selector to remove the product card
                        } else {
                            alert('Error deleting product: ' + xhr.responseText);
                        }
                    }
                };
                xhr.send('id=' + id);
            }
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            const editModal = document.getElementById('editModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-id');

                // Fetch product details via AJAX
                fetch(`get_product.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the form with the product's current data
                        document.getElementById('editProductId').value = data.id;
                        document.getElementById('editName').value = data.name;
                        document.getElementById('editDescription').value = data.description;
                        document.getElementById('editCategory').value = data.category;
                        document.getElementById('editPrice').value = data.price;
                    });
            });
        });


    </script>
</body>
</html>
