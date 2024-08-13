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
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>User Profile</title>
        <link rel='stylesheet' type='text/css' href='assets/css/font-awesome.css'>
        <link rel='stylesheet' href='assets/css/style.css'>
        <link rel='stylesheet' href='assets/css/owl-carousel.css'>
        <link rel='stylesheet' href='assets/css/lightbox.css'>
        <link rel='icon' type='image/x-icon' href='assets/images/Logo_Transparent.png'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f9fa;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                box-sizing: border-box;
            }
            .profile-container {
                background-color: #fff;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 500px;
            }
            h2 {
                color: #153448;
                margin-bottom: 1.5rem;
                text-align: center;
                font-size: 1.5rem;
            }
            .profile-info p {
                margin-bottom: 1rem;
                color: #153448;
                font-size: 1rem;
            }
            .go-back-button {
                background-color: #e9ecef;
                color: #153448;
                border: none;
                padding: 0.75rem;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s;
                font-size: 1rem;
                display: block;
                width: 100%;
                text-align: center;
            }
            .go-back-button:hover {
                background-color: #d3d6d8;
            }
            @media (max-width: 480px) {
                .profile-container {
                    padding: 1.5rem;
                }
                h2 {
                    font-size: 1.2rem;
                }
                .go-back-button {
                    font-size: 0.9rem;
                }
            }
        </style>
    </head>
    <body>
        <div class='profile-container'>
            <h2>User Profile</h2>
            <div class='profile-info'>
                <p><strong>Username:</strong> " . htmlspecialchars($row['username']) . "</p>
                <p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>
                <p><strong>Gender:</strong> " . htmlspecialchars($row['gender']) . "</p>
                <p><strong>Birthday:</strong> " . htmlspecialchars($row['birthday']) . "</p>
            </div>
            <button class='go-back-button' onclick='window.history.back();'>Logout</button>
        </div>
        <!-- jQuery -->
        <script src='assets/js/jquery-2.1.0.min.js'></script>
        <!-- Bootstrap -->
        <script src='assets/js/popper.js'></script>
        <script src='assets/js/bootstrap.min.js'></script>
        <!-- Plugins -->
        <script src='assets/js/owl-carousel.js'></script>
        <script src='assets/js/accordions.js'></script>
        <script src='assets/js/datepicker.js'></script>
        <script src='assets/js/scrollreveal.min.js'></script>
        <script src='assets/js/waypoints.min.js'></script>
        <script src='assets/js/jquery.counterup.min.js'></script>
        <script src='assets/js/imgfix.min.js'></script> 
        <script src='assets/js/slick.js'></script> 
        <script src='assets/js/lightbox.js'></script> 
        <script src='assets/js/isotope.js'></script> 
        <!-- Global Init -->
        <script src='assets/js/custom.js'></script>
    </body>
    </html>";
} else {
    echo "<p>Error: User not found.</p>";
}

$stmt->close();
$conn->close();
?>
