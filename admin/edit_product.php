<?php
session_start();
require_once '../includes/connection.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock']; // New line to get stock quantity
    $image = $_FILES['image']['name'];
    if ($image) {
        // Image upload directory
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image);
        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $sql = "UPDATE products SET name = :name, description = :description, category = :category, price = :price, stock = :stock, image = :image WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['name' => $name, 'description' => $description, 'category' => $category, 'price' => $price, 'stock' => $stock, 'image' => $target_file, 'id' => $id]);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        $sql = "UPDATE products SET name = :name, description = :description, category = :category, price = :price, stock = :stock WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'description' => $description, 'category' => $category, 'price' => $price, 'stock' => $stock, 'id' => $id]);
    }
    header("Location: admin_products.php");
}
$pdo = null;
?>