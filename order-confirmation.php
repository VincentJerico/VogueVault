<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_order'])) {
    header("Location: index.php");
    exit();
}

$order = $_SESSION['last_order'];
unset($_SESSION['last_order']); // Clear the order from session after displaying
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - VogueVault</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/logosquaretransparent.png">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f8f9fa;
            color: #333;
        }
        .receipt-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .receipt-header p {
            margin: 5px 0;
            color: #666;
        }
        .receipt-details {
            margin-bottom: 20px;
        }
        .receipt-details h4 {
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }
        .receipt-details p {
            margin: 0;
            color: #666;
        }
        .order-summary {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .order-summary th, .order-summary td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            color: #333;
        }
        .order-summary th {
            background-color: #f2f2f2;
        }
        .order-summary tfoot tr th, .order-summary tfoot tr td {
            border-top: 2px solid #ccc;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
        }
        .receipt-footer a {
            background-color: #153448;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .receipt-footer a:hover {
            background-color: #DFD0B8;
            color: #153448;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>VogueVault</h1>
            <p>Order Confirmation</p>
            <p>Date: <?php echo date('F j, Y'); ?></p>
        </div>

        <div class="receipt-details">
            <h4>Order Details</h4>
            <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
        </div>

        <table class="order-summary">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>₱<?php echo number_format($item['price'], 2); ?></td>
                        <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th>₱<?php echo number_format($order['total_price'], 2); ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="receipt-footer">
            <p>Thank you for your purchase!</p>
            <a href="home.php">Continue Shopping</a>
            <button onclick="window.print()" class="btn btn-secondary">Print Receipt</button>
        </div>
    </div>
</body>
</html>
