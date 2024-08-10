<?php
session_start();
require_once 'includes/connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];


    $stmt = $conn->prepare("INSERT INTO users (username, email, password, gender, birthday) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $gender, $birthday);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        .register-container {
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
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }
        .password-field {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #153448;
        }
        .terms {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        .terms input {
            margin-right: 0.5rem;
        }
        .terms label {
            font-size: 0.8rem;
        }
        .register-button {
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
        .register-button:hover {
            background-color: #0e2330;
        }
        .login-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #153448;
            font-size: 0.9rem;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 1.5rem;
            }
            h2 {
                font-size: 1.2rem;
            }
            input[type="text"],
            input[type="password"],
            input[type="email"],
            input[type="date"],
            select,
            .register-button {
                font-size: 0.9rem;
            }
            .terms label {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" required>
                    <i class="password-toggle fas fa-eye" id="togglePassword"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <div class="password-field">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <i class="password-toggle fas fa-eye" id="toggleConfirmPassword"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" id="birthday" name="birthday" required>
            </div>
            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the Terms and Conditions</label>
            </div>
            <button type="submit" class="register-button">Register</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
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
