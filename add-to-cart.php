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

// Fetch the product's stock
$stmt = $pdo->prepare("SELECT stock FROM products WHERE id = :product_id");
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

// Check if the product is already in the cart
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing_item) {
    // Update quantity if the product is already in the cart
    $new_quantity = $existing_item['quantity'] + $quantity;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
    $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
    $stmt->bindParam(':id', $existing_item['id'], PDO::PARAM_INT);
} else {
    // Add new item to the cart
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product added to cart successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding product to cart']);
}

?>