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

// Handle Edit User
if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $role, $user_id]);
}

// Handle Delete User
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
}

$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$pdo = null;
?>

<?php
// (Keep the existing PHP code at the top)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault Admin - Users</title>
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
            flex: 1;
            overflow-y: auto;
            padding-bottom: 60px; /* Adjust based on your footer height */
        }
        .table-responsive {
            max-height: calc(100vh - 250px); /* Adjust based on your header and footer heights */
            overflow-y: auto;
        }
        footer {
            background-color: #153448;
            color: white;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
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
            .table-responsive {
                max-height: calc(100vh - 350px); /* Adjust for mobile */
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
                            <a class="nav-link active" href="./admin_users.php">
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
                    <h2><i class="bi bi-people-fill me-2"></i>Users</h2>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if ($stmt) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr data-user-id='" . htmlspecialchars($row["id"]) . "'>";
                                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                    echo "<td>";
                                    echo "<button class='btn btn-sm btn-outline-primary me-2' data-bs-toggle='modal' data-bs-target='#editUserModal' data-user-id='" . htmlspecialchars($row["id"]) . "'><i class='bi bi-pencil'></i></button>";
                                    echo "<button class='btn btn-sm btn-outline-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-user-id='" . htmlspecialchars($row["id"]) . "'><i class='bi bi-trash'></i></button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="admin_users.php">
                        <input type="hidden" id="editUserId" name="user_id">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" name="edit_user" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="admin_users.php">
                        <input type="hidden" id="deleteUserId" name="user_id">
                        <p>Are you sure you want to delete this user?</p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
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


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Edit User Modal
            var editUserModal = document.getElementById('editUserModal');
            editUserModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var userId = button.getAttribute('data-user-id');
                var username = button.closest('tr').querySelector('td:nth-child(2)').textContent;
                var role = button.closest('tr').querySelector('td:nth-child(3)').textContent;

                document.getElementById('editUserId').value = userId;
                document.getElementById('editUsername').value = username.trim();
                document.getElementById('editRole').value = role.trim();
            });

            // Delete User Modal
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var userId = button.getAttribute('data-user-id');
                document.getElementById('deleteUserId').value = userId;
            });
        });



    </script>
</body>
</html>