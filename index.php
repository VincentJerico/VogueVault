<?php
session_start();
require_once 'includes/connection.php'; // Include your database connection file

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the statement to get the user ID and hashed password
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $id = $user['id'];
        $hashed_password = $user['password'];
        
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;

            // Fetch the user role
            $role_stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            $role_stmt->execute([$id]);
            $role = $role_stmt->fetchColumn();

            // Store the user role in session
            $_SESSION['user_role'] = $role;

            // Redirect based on user role
            if ($role == 'admin') {
                $_SESSION['login_redirect'] = true;
                header("Location: admin/admin_panel.php");
            } else {
                $_SESSION['login_redirect'] = true;
                header("Location: home.php");
            }
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that username.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/Logo_Transparent.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
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
            background-image: url('assets/images/bnwbg2.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(5px) brightness(0.5); /* Adjust the blur value as needed */
            z-index: -1;
        }

        .container {
            display: flex;
            background-color: rgba(255, 255, 255, 0.85); /* Slight transparency */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            z-index: 1; /* Ensure the container is above the blurred background */
        }
        .welcome-section {
            flex: 1;
            padding: 2rem;
            background-color: #153448;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        .welcome-section h2 {
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .welcome-section p {
            font-size: 1rem;
            line-height: 1.6;
        }
        .login-section {
            flex: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
        input[type="password"] {
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
        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .remember-me input {
            margin-right: 0.5rem;
        }
        .forgot-password {
            display: block;
            text-align: right;
            color: #153448;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .login-button {
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
        .login-button:hover {
            background-color: #0e2330;
        }
        .register-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #153448;
            font-size: 0.9rem;
        }
        .error-message {
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

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .welcome-section {
                text-align: center;
                padding: 1.5rem;
            }
            .login-section {
                padding: 1.5rem;
            }
            .welcome-section h1 {
                font-size: 2rem;
            }
            .welcome-section h2 {
                font-size: 1.5rem;
            }
            .welcome-section p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<!-- ***** Preloader Start ***** -->
<div id="preloader">
    <div class="jumper">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>  
    <!-- ***** Preloader End ***** -->
    <div class="container">
        <div class="welcome-section">
            <h1>Welcome to VogueVault</h1>
            <i><h3>Unveiling Timeless Style</h3></i>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Rem maiores totam illum iste optio animi rerum est earum incidunt odio, quo mollitia non beatae doloribus odit id, pariatur provident delectus.
            </p>
        </div>
        <div class="login-section">
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username or Email:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" required>
                        <!--<i class="password-toggle fas fa-eye" id="togglePassword"></i>-->
                    </div>
                </div>
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                <button type="submit" class="login-button">Log in</button>
            </form>
            <p class="register-link">Don't have an account yet? <a href="register.php">Register</a></p>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        }); 
    </script>
</body>
</html>
