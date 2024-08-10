<?php
session_start();
require_once 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please log in to view your profile.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT username, email, gender, birthday FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "<p><strong>Username:</strong> " . htmlspecialchars($row['username']) . "</p>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
    echo "<p><strong>Gender:</strong> " . htmlspecialchars($row['gender']) . "</p>";
    echo "<p><strong>Birthday:</strong> " . htmlspecialchars($row['birthday']) . "</p>";
} else {
    echo "<p>Error: User not found.</p>";
}

$stmt->close();
$conn->close();
?>
