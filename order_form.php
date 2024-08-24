<?php
session_start();
require_once 'includes/connection.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch product details if product_id is set
$product = null;
if (isset($_POST['product_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $_POST['product_id'], PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form - VogueVault</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Order Form</h2>
        <form action="process_order.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($_POST['product_id'] ?? ''); ?>">
            <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($_POST['quantity'] ?? ''); ?>">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Shipping Address:</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            
            <?php if ($product): ?>
            <div class="form-group">
                <h4>Product Information:</h4>
                <p>Name: <?php echo htmlspecialchars($product['name']); ?></p>
                <p>Price: ₱<?php echo number_format($product['price'], 2); ?></p>
                <p>Quantity: <?php echo htmlspecialchars($_POST['quantity'] ?? '1'); ?></p>
                <p>Total: ₱<?php echo number_format($product['price'] * ($_POST['quantity'] ?? 1), 2); ?></p>
            </div>
            <?php endif; ?>
            
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
</body>
</html>