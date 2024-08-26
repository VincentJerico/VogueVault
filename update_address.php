<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['address'])) {
    echo 'error';
    exit;
}

$user_id = $_SESSION['user_id'];
$new_address = $_POST['address'];

try {
    $stmt = $pdo->prepare("UPDATE users SET address = :address WHERE id = :user_id");
    $stmt->execute([
        ':address' => $new_address,
        ':user_id' => $user_id
    ]);
    echo 'success';
} catch (PDOException $e) {
    echo 'error';
}

$pdo = null;
?>