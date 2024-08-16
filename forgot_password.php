<?php
session_start();
require_once 'includes/connection.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    
    $stmt = $conn->prepare("SELECT id, birthday FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($birthday == $user['birthday']) {
            $token = bin2hex(random_bytes(50));
            
            $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE id = ?");
            $stmt->bind_param("si", $token, $user['id']);
            $stmt->execute();
            
            header("Location: reset_password.php?token=$token");
            exit();
        } else {
            $message = "Incorrect birthday.";
        }
    } else {
        $message = "No account found with that email address.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .forgot-password-container {
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
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #153448;
            font-size: 0.9rem;
        }
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }
        .reset-button {
            width: 100%;
            padding: 0.75rem;
            background-color: #153448;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem;
        }
        .reset-button:hover {
            background-color: #0e2330;
        }
        .back-to-index {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #153448;
            font-size: 0.9rem;
            text-decoration: none;
        }
        .message {
            color: crimson;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #153448;
        }
        a:hover {
            color: #FF8343;
        }
        @media (max-width: 480px) {
            .forgot-password-container {
                padding: 1.5rem;
            }
            h2 {
                font-size: 1.2rem;
            }
            input[type="email"],
            input[type="date"],
            .reset-button {
                font-size: 0.9rem;
            }
            .message,
            .back-to-index {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="birthday">When is your birthday?</label>
                <input type="date" id="birthday" name="birthday" required>
            </div>
            <button type="submit" class="reset-button">Verify and Reset Password</button>
        </form>
        <a href="index.php" class="back-to-index">Back to Login</a>
    </div>
</body>
</html>
