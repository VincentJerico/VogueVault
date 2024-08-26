<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to view your profile.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Prepare and execute the statement to get user profile information
    $stmt = $pdo->prepare("SELECT username, email, gender, birthday, address FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo "<h2>User Profile</h2>";
        echo "<div class='profile-info'>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($row['username']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
        echo "<p><strong>Gender:</strong> " . htmlspecialchars($row['gender']) . "</p>";
        echo "<p><strong>Birthday:</strong> " . htmlspecialchars($row['birthday']) . "</p>";
        echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address'] ?? 'Not set') . "</p>";
        echo "</div>";
    } else {
        echo "<p>Error: User not found.</p>";
    }
} catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

$pdo = null; // Close the connection
?>