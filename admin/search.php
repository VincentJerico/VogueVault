<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../includes/connection.php';

file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Search request received\n", FILE_APPEND);

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Access denied\n", FILE_APPEND);
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];
    file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Search term: $searchTerm\n", FILE_APPEND);
    
    // Updated query without 'total_amount'
    $query = "
        SELECT 'users' as type, id, username as title, email as description, CONCAT('admin_users.php?id=', id) as link FROM users WHERE username LIKE :term
        UNION ALL
        SELECT 'products' as type, id, name as title, description, CONCAT('admin_products.php?id=', id) as link FROM products WHERE name LIKE :term
        UNION ALL
        SELECT 'orders' as type, id, CONCAT('Order #', id) as title, CONCAT('Order ID: ', id) as description, CONCAT('admin_orders.php?id=', id) as link FROM orders WHERE id LIKE :term
        LIMIT 10
    ";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute(['term' => "%$searchTerm%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Query executed successfully\n", FILE_APPEND);
        file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Results: " . print_r($results, true) . "\n", FILE_APPEND);
        
        header('Content-Type: application/json');
        echo json_encode($results);
    } catch (PDOException $e) {
        file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - Database error: " . $e->getMessage() . "\n", FILE_APPEND);
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    file_put_contents('search_log.txt', date('Y-m-d H:i:s') . " - No search term provided\n", FILE_APPEND);
    header('HTTP/1.1 400 Bad Request');
    exit('Search term not provided');
}
?>