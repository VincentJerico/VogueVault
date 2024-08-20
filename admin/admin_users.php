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

$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);

$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../assets/fonts/poppins.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo_Transparent.png">

    <style>
    body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
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
                            <a class="nav-link active" href="./admin_users.php">
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
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-person-plus-fill me-2"></i>Add User
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add user form here -->
                </div>
            </div>
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
                    <form id="editUserForm">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
                    Are you sure you want to delete this user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

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

        document.addEventListener('DOMContentLoaded', function() {
            // Delete Modal
            var deleteModal = document.getElementById('deleteModal');
            var confirmDeleteButton = document.getElementById('confirmDelete');
            var userIdToDelete;

            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                userIdToDelete = button.getAttribute('data-user-id');
            });

            confirmDeleteButton.addEventListener('click', function() {
                $.ajax({
                    url: 'delete_user.php',
                    method: 'POST',
                    data: { user_id: userIdToDelete },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            $(`tr[data-user-id="${userIdToDelete}"]`).remove();
                            var modal = bootstrap.Modal.getInstance(deleteModal);
                            modal.hide();
                        } else {
                            alert('Error deleting user: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting user:', error);
                        alert('An error occurred while deleting the user.');
                    }
                });
            });

            // Edit User Modal
            var editUserModal = document.getElementById('editUserModal');
            var editUserForm = document.getElementById('editUserForm');
            var editUsername = document.getElementById('editUsername');
            var editRole = document.getElementById('editRole');
            var userIdToEdit;

            editUserModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                userIdToEdit = button.getAttribute('data-user-id');

                $.ajax({
                    url: 'get_user.php',
                    method: 'GET',
                    data: { user_id: userIdToEdit },
                    success: function(response) {
                        var userData = JSON.parse(response);
                        editUsername.value = userData.username;
                        editRole.value = userData.role;
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching user data:', error);
                        alert('An error occurred while fetching user data.');
                    }
                });
            });

            editUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                var updatedData = {
                    user_id: userIdToEdit,
                    username: editUsername.value,
                    role: editRole.value
                };

                $.ajax({
                    url: 'update_user.php',
                    method: 'POST',
                    data: updatedData,
                    success: function(response) {
                        console.log(response); // Check the response
                        var result = JSON.parse(response);
                        if (result.success) {
                            // Update the table row with the new data
                            var row = $(`tr[data-user-id="${userIdToEdit}"]`);
                            row.find('td:eq(1)').text(updatedData.username);
                            row.find('td:eq(2)').text(updatedData.role);
                            
                            // Hide the modal
                            var modal = bootstrap.Modal.getInstance(editUserModal);
                            modal.hide();
                        } else {
                            alert('Error updating user: ' + result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating user:', error);
                        alert('An error occurred while updating the user.');
                    }
                });

            });
        });

    </script>
</body>
</html>
