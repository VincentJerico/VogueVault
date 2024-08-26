<?php
session_start();
require_once 'includes/connection.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$buy_all = isset($_GET['buy_all']) && $_GET['buy_all'] === 'true';

// Fetch cart items if buying all
$cart_items = [];
if ($buy_all) {
    $stmt = $pdo->prepare("SELECT c.id as cart_id, p.id as product_id, p.name, p.price, c.quantity 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch single product details
    if (isset($_GET['product_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $_GET['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
        $cart_items[] = [
            'product_id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If user has an address, use it as default
$default_address = $user['address'] ?? '';

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form - VogueVault</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/logosquaretransparent.png">
</head>
<body>
    <div class="container mt-5">
        <h2>Order Form</h2>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="process_order.php" method="POST">
            <input type="hidden" name="buy_all" value="<?php echo $buy_all ? 'true' : 'false'; ?>">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Shipping Address:</label>
                <textarea class="form-control" id="address" name="address" required><?php echo htmlspecialchars($default_address); ?></textarea>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="cash_on_delivery">Cash on Delivery</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            
            <div class="form-group">
                <h4>Order Information:</h4>
                <?php foreach ($cart_items as $item): ?>
                    <p><?php echo htmlspecialchars($item['name']); ?> - Quantity: <?php echo htmlspecialchars($item['quantity']); ?> - Price: ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                    <input type="hidden" name="items[]" value="<?php echo htmlspecialchars(json_encode($item)); ?>">
                <?php endforeach; ?>
                <p><strong>Total: ₱<?php echo number_format($total_price, 2); ?></strong></p>
            </div>
            
            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </div>
</body>
</html>