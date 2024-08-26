<?php
session_start();
require_once 'includes/connection.php'; // This will include the PDO connection

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    
    try {
        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare("SELECT id, birthday FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if ($birthday == $user['birthday']) {
                $token = bin2hex(random_bytes(50));
                
                // Update the user's reset token
                $stmt = $pdo->prepare("UPDATE users SET reset_token = :token WHERE id = :id");
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
                $stmt->execute();
                
                header("Location: reset_password.php?token=$token");
                exit();
            } else {
                $message = "Incorrect birthday.";
            }
        } else {
            $message = "No account found with that email address.";
        }
    } catch (PDOException $e) {
        $message = "An error occurred: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/logosquaretransparent.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/images/bnwbg.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(5px) brightness(0.5); /* Adjust blur and brightness as needed */
            z-index: -1;
        }

        .forgot-password-container {
            background-color: rgba(255, 255, 255, 0.85); /* Slight transparency */
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            z-index: 1; /* Ensure the container is above the blurred background */
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
