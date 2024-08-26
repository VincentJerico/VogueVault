<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['items'])) {
    header("Location: index.php");
    exit();
}

try {
    $pdo->beginTransaction();

    $total_price = 0;
    $items = [];
    foreach ($_POST['items'] as $item_json) {
        $item = json_decode($item_json, true);
        $items[] = $item;

        // Fetch product price and stock
        $productStmt = $pdo->prepare("SELECT price, stock FROM products WHERE id = :id");
        $productStmt->execute(['id' => $item['product_id']]);
        $product = $productStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            throw new Exception("Product not found: " . $item['product_id']);
        }

        if ($product['stock'] < $item['quantity']) {
            throw new Exception("Not enough stock available for product: " . $item['name']);
        }

        $item_total = $product['price'] * $item['quantity'];
        $total_price += $item_total;

        // Insert order into database
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, shipping_address, payment_method) VALUES (:user_id, :product_id, :quantity, :total_price, :shipping_address, :payment_method)");
        
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'total_price' => $item_total,
            'shipping_address' => $_POST['address'],
            'payment_method' => $_POST['payment_method']
        ]);

        // Update product stock
        $updateStmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :id");
        $updateStmt->execute([
            'quantity' => $item['quantity'],
            'id' => $item['product_id']
        ]);
    }

    // Clear the cart items that were purchased
    $clearCartStmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id AND product_id IN (" . implode(',', array_column($items, 'product_id')) . ")");
    $clearCartStmt->execute(['user_id' => $_SESSION['user_id']]);

    $pdo->commit();

    // Update user's address if it's different from the current one
    $new_address = $_POST['address'];
    if ($new_address !== $user['address']) {
        $stmt = $pdo->prepare("UPDATE users SET address = :address WHERE id = :user_id");
        $stmt->execute([
            ':address' => $new_address,
            ':user_id' => $_SESSION['user_id']
        ]);
    }
    
    // Store order information in session for the confirmation page
    $_SESSION['last_order'] = [
        'items' => $items,
        'total_price' => $total_price,
        'shipping_address' => $_POST['address'],
        'payment_method' => $_POST['payment_method']
    ];

    // Redirect to order confirmation page
    header("Location: order-confirmation.php");
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error_message'] = "Error processing order: " . $e->getMessage();
    header("Location: order_form.php" . ($_POST['buy_all'] === 'true' ? '?buy_all=true' : ''));
    exit();
}