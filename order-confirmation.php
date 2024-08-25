<?php
session_start();
require_once 'includes/connection.php';

// Fetch the last order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC LIMIT 1");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "No recent order found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - VogueVault</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #153448;
        }
        .order-confirmation {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
            transition: transform 0.3s ease;
        }
        .order-confirmation:hover {
            transform: translateY(-5px);
        }
        h2 {
            color: #153448;
            margin-bottom: 20px;
            transition: color 0.3s ease;
        }
        h2:hover {
            color: #DFD0B8;
        }
        .order-details {
            list-style-type: none;
            padding: 0;
        }
        .order-details li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .order-details li:hover {
            background-color: #e9ecef;
        }
        .btn-continue {
            background-color: #153448;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .btn-continue:hover {
            background-color: #DFD0B8;
            color: #153448;
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .order-confirmation {
                margin-top: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-confirmation">
            <h2>Order Confirmation</h2>
            <p>Thank you for your purchase! Here are your order details:</p>
            <ul class="order-details">
                <li><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></li>
                <li><strong>Product ID:</strong> <?php echo htmlspecialchars($order['product_id']); ?></li>
                <li><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></li>
                <li><strong>Shipping Address:</strong> <?php echo isset($order['shipping_address']) ? htmlspecialchars($order['shipping_address']) : 'Not provided'; ?></li>
                <li><strong>Payment Method:</strong> <?php echo isset($order['payment_method']) ? htmlspecialchars($order['payment_method']) : 'Not provided'; ?></li>
                <li><strong>Total Price:</strong> ₱<?php echo number_format($order['total_price'], 2); ?></li>
            </ul>
            <a href="products.php" class="btn-continue">Continue Shopping</a>
        </div>
    </div>
</body>
</html>