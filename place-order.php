<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];
$quantity = (int)$_POST['quantity'];

if ($quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit();
}

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :product_id");
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit();
}

if ($quantity > $product['stock']) {
    echo json_encode(['success' => false, 'message' => 'Quantity exceeds available stock']);
    exit();
}

// Place order
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (:user_id, :product_id, :quantity, :total_price)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $total_price = $quantity * $product['price'];
    $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
    $stmt->execute();

    // Reduce stock
    $stmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id");
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error placing order: ' . $e->getMessage()]);
}

?>