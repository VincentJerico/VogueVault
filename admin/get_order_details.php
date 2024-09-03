<?php
require_once '../includes/connection.php';

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "Invalid order ID";
    exit;
}

$order_id = intval($_GET['order_id']);

try {
    // Get order details
    $order_query = "SELECT * FROM orders WHERE id = :order_id";
    $order_stmt = $pdo->prepare($order_query);
    $order_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $order_stmt->execute();
    $order = $order_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "No order found with ID: " . $order_id;
        exit;
    }

    // Get order items
    $items_query = "SELECT oi.*, p.name, p.price
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = :order_id";
    $items_stmt = $pdo->prepare($items_query);
    $items_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $items_stmt->execute();
    $order_items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output order details
    echo "<h4>Order #" . $order['id'] . "</h4>";
    echo "<p><strong>User ID:</strong> " . $order['user_id'] . "</p>";
    echo "<p><strong>Status:</strong> " . $order['status'] . "</p>";
    echo "<p><strong>Date:</strong> " . $order['created_at'] . "</p>";

    if (empty($order_items)) {
        echo "<p>No items found for this order.</p>";
    } else {
        // Output items table
        echo "<table class='table table-striped'>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($order_items as $item) {
            echo "<tr>
                    <td>" . $item['product_id'] . "</td>
                    <td>" . htmlspecialchars($item['name']) . "</td>
                    <td>" . $item['quantity'] . "</td>
                    <td>₱" . number_format($item['price'], 2) . "</td>
                    <td>₱" . number_format($item['price'] * $item['quantity'], 2) . "</td>
                  </tr>";
        }
        echo "</tbody>
              <tfoot>
                <tr>
                    <th colspan='4' class='text-end'>Total:</th>
                    <th>₱" . number_format($order['total_price'], 2) . "</th>
                </tr>
              </tfoot>
            </table>";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>