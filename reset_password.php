<?php
session_start();
require_once 'includes/connection.php';

$message = '';
$message_type = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Use PDO instead of mysqli
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = :token");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        $user = $result;
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            if ($password === $confirm_password) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL WHERE id = :id");
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
                $stmt->execute();
                
                $message = "Password reset successfully. You can now Log in with your new password.";
                $message_type = 'success';
            } else {
                $message = "Passwords do not match.";
                $message_type = 'error';
            }
        }
    } else {
        $message = "Invalid reset token.";
        $message_type = 'error';
    }
} else {
    $message = "No token provided.";
    $message_type = 'error';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/Logo_Transparent.png">
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
        .reset-password-container {
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
        .password-field {
            display: flex;
            align-items: center;
            position: relative;
            width: 100%;
        }

        input[type="password"] {
            flex: 1;
            padding: 0.5rem;
            padding-right: 2.5rem; /* Space for the toggle icon */
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            box-sizing: border-box; /* Include padding in the width */
            transition: width 0.2s; /* Smooth transition for width */
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0.5rem; /* Position the icon within the input */
            transform: translateY(-50%);
            cursor: pointer;
            color: #153448;
            font-size: 1.1rem;
            width: 2rem; /* Set a fixed width */
            text-align: center; /* Center the icon */
            z-index: 10; /* Ensure it stays above the input field */
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
        }
        .message {
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }
        .error-message {
            color: crimson;
        }
        .success-message {
            color: #5AB2FF;
        }
        a {
            text-decoration: none;
            color: #153448;
        }
        a:hover {
            color: #FF8343;
        }
        @media (max-width: 480px) {
            .reset-password-container {
                padding: 1.5rem;
                width: 100%;
            }
            h2 {
                font-size: 1.2rem;
            }
            input[type="password"],
            .reset-button {
                font-size: 0.9rem;
            }
            .back-to-index {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-password-container">
        <h2>Reset Password</h2>
        <?php if ($message): ?>
            <div class="message <?php echo ($message_type === 'success') ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <?php if (!$message || $message == "Passwords do not match."): ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="password">New Password:</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" required>
                        <!--<i class="password-toggle fas fa-eye" id="togglePassword"></i>-->
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <div class="password-field">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <!--<i class="password-toggle fas fa-eye" id="toggleConfirmPassword"></i>-->
                    </div>
                </div>
                <button type="submit" class="reset-button">Reset Password</button>
            </form>
        <?php endif; ?>
        <a href="index.php" class="back-to-index">Back to Log in</a>
    </div>

    <script>
        function togglePassword(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);
            
            toggle.addEventListener('click', function () {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        }

        togglePassword('password', 'togglePassword');
        togglePassword('confirm_password', 'toggleConfirmPassword');

        document.querySelector('form').addEventListener('submit', function(e) {
            var password = document.getElementById('password').value;
            var confirm_password = document.getElementById('confirm_password').value;
            if (password != confirm_password) {
                alert('Passwords do not match');
                e.preventDefault();
            }
        });
    </script>

</body>
</html>
