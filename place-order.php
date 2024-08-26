<?php
session_start();
require_once 'includes/connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
    exit();
}

$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :product_id");
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('Product not found');
    }

    if ($quantity > $product['stock']) {
        throw new Exception('Quantity exceeds available stock');
    }

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (:user_id, :product_id, :quantity, :total_price)");
    $total_price = $quantity * $product['price'];
    $stmt->execute([
        ':user_id' => $user_id,
        ':product_id' => $product_id,
        ':quantity' => $quantity,
        ':total_price' => $total_price
    ]);

    $stmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id");
    $stmt->execute([':quantity' => $quantity, ':product_id' => $product_id]);

    if (isset($_POST['cart_id'])) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :cart_id AND user_id = :user_id");
        $stmt->execute([':cart_id' => (int)$_POST['cart_id'], ':user_id' => $user_id]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error placing order: ' . $e->getMessage()]);
}