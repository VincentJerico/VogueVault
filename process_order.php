<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    header("Location: index.php");
    exit();
}

try {
    $pdo->beginTransaction();

    // Insert order into database
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, shipping_address, payment_method) VALUES (:user_id, :product_id, :quantity, :total_price, :shipping_address, :payment_method)");
    
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $_POST['product_id'], PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $_POST['quantity'], PDO::PARAM_INT);
    $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
    $stmt->bindParam(':shipping_address', $_POST['address'], PDO::PARAM_STR);
    $stmt->bindParam(':payment_method', $_POST['payment_method'], PDO::PARAM_STR);

    // Fetch product price
    $productStmt = $pdo->prepare("SELECT price FROM products WHERE id = :id");
    $productStmt->execute(['id' => $_POST['product_id']]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);
    
    $total_price = $product['price'] * $_POST['quantity'];
    
    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'product_id' => $_POST['product_id'],
        'quantity' => $_POST['quantity'],
        'total_price' => $total_price,
        'shipping_address' => $_POST['address'],
        'payment_method' => $_POST['payment_method']
    ]);

    $pdo->commit();

    // Redirect to order confirmation page
    header("Location: order-confirmation.php");
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error processing order: " . $e->getMessage();
}