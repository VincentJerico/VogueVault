<?php
session_start();
require_once 'includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id']) && isset($_POST['rating'])) {
    $product_id = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);

    // Fetch current rating and rating count
    $stmt = $pdo->prepare("SELECT rating, rating_count FROM products WHERE id = :id");
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $current_rating = $product['rating'];
        $rating_count = $product['rating_count'];

        // Calculate new rating
        $new_rating = (($current_rating * $rating_count) + $rating) / ($rating_count + 1);
        $new_rating_count = $rating_count + 1;

        // Update product rating
        $update_stmt = $pdo->prepare("UPDATE products SET rating = :rating, rating_count = :rating_count WHERE id = :id");
        $update_stmt->execute([
            ':rating' => $new_rating,
            ':rating_count' => $new_rating_count,
            ':id' => $product_id
        ]);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
