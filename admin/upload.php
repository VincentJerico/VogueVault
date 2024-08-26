<?php

// php code for uploading image but not finished

// Include the database configuration file
require_once '../includes/connection.php';

if(isset($_POST['submit'])){
    // Check if the file was uploaded without errors
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
        // Get image info
        $imageName = $_FILES["image"]["name"];
        $imageType = $_FILES["image"]["type"];
        $imageData = file_get_contents($_FILES["image"]["tmp_name"]);

        // Prepare the SQL query
        $sql = "INSERT INTO images (image_name, image_data) VALUES (:image_name, :image_data)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':image_name', $imageName);
        $stmt->bindParam(':image_data', $imageData, PDO::PARAM_LOB);

        // Execute the query
        if($stmt->execute()){
            echo "Image uploaded successfully.";
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Error: " . $_FILES["image"]["error"];
    }
}
?>


