<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if we're buying from cart or directly
if (isset($_POST['cart_id'])) {
    // Buying from cart
    $cart_id = (int)$_POST['cart_id'];
    
    // Fetch cart item
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE id = :cart_id AND user_id = :user_id");
    $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cart_item) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit();
    }
    
    $product_id = $cart_item['product_id'];
    $quantity = $cart_item['quantity'];
} elseif (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    // Buying directly
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
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

    // If buying from cart, remove the item from cart
    if (isset($cart_id)) {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :cart_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error placing order: ' . $e->getMessage()]);
}
?>