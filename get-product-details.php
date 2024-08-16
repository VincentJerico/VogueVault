<?php
require_once 'includes/connection.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Return HTML for the product modal
        echo '<div class="product-details">';
        echo '<img src="assets/images/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '">';
        echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';
        echo '<p class="description">' . htmlspecialchars($product['description']) . '</p>';
        echo '<p class="price">Price: ₱' . number_format($product['price'], 2) . '</p>';
        echo '<div class="actions">';
        echo '<button class="add-to-cart" data-product-id="' . $product['id'] . '">Add to Cart</button>';
        echo '<button class="add-to-wishlist" data-product-id="' . $product['id'] . '">Add to Wishlist</button>';
        echo '</div>';
        echo '<button class="close-modal">Close</button>';
        echo '</div>';
    } else {
        echo 'Product not found';
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request';
}
?>