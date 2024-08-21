<?php
session_start();
require_once '../includes/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    // Image upload directory
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image);

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO products (name, description, category, price, image) VALUES (:name, :description, :category, :price, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'description' => $description, 'category' => $category, 'price' => $price, 'image' => $target_file]);
        header("Location: admin_products.php");
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$pdo = null;
?>