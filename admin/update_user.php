<?php
session_start();
require_once '../includes/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['username'], $_POST['role'])) {
    $userId = $_POST['user_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $role, $userId]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes made or user not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}