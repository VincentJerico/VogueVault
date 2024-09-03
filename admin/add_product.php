<?php
session_start();
require_once '../includes/connection.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Other form data processing (e.g., product name, description, etc.)
    // Handle file upload
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // 0755 is a permission code
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // File is uploaded successfully, you can now save the file path in the database along with other product details.
        $name = $_POST['name'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock']; // New line to get stock quantity
        $image = $target_file;
        $sql = "INSERT INTO products (name, description, category, price, image, stock) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $category, $price, $image, $stock]);
        header("Location: admin_products.php");
        exit();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>