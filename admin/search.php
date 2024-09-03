<?php
session_start();
require_once '../includes/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];
    
    // Perform the search across various tables
    $query = "
        SELECT 'users' as type, id, username as title, email as description, CONCAT('admin_users.php?id=', id) as link FROM users WHERE username LIKE :term
        UNION ALL
        SELECT 'products' as type, id, name as title, description, CONCAT('admin_products.php?id=', id) as link FROM products WHERE name LIKE :term
        UNION ALL
        SELECT 'orders' as type, id, CONCAT('Order #', id) as title, CONCAT('Total: $', total_amount) as description, CONCAT('admin_orders.php?id=', id) as link FROM orders WHERE id LIKE :term
        LIMIT 10
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute(['term' => "%$searchTerm%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    header('HTTP/1.1 400 Bad Request');
    exit('Search term not provided');
}
?>