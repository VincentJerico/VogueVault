<?php
session_start();
require_once 'includes/connection.php'; // Include your PDO connection file

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check if the password is at least 8 characters long
    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // Check if username or email already exists
        $check_stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $check_stmt->bindParam(':username', $username);
        $check_stmt->bindParam(':email', $email);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            $error_message = "Username or email already exists.";
        } else {
            // Proceed with registration
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $error_message = "Error: " . $stmt->errorInfo()[2];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

        .register-container {
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
        .index-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #153448;
            font-size: 0.9rem;
        }

        body.modal-open {
            overflow: hidden;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            overflow-y: hidden;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto; /* Center horizontally */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative; /* Required for transform to work */
            top: 50%; /* Position from the top */
            transform: translateY(-50%); /* Move it up by 50% of its height */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: #153448;
        }
        a:hover {
            color: #FF8343;
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
                    
                    <!--<i class="password-toggle fas fa-eye" id="togglePassword"></i>-->
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <div class="password-field">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    
                    <!--<i class="password-toggle fas fa-eye" id="toggleConfirmPassword"></i>-->
                </div>
            </div>
            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="#" id="open-terms">Terms and Conditions</a></label>
            </div>
            <button type="submit" class="register-button">Register</button>
        </form>
        <?php
        if ($error_message) {
            echo "<p style='color:red;'>$error_message</p>";
        }
        ?>
        <p class="index-link">Already have an account? <a href="index.php">Log in</a></p>
    </div>

<div id="terms-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>VogueVault Terms and Conditions</h2>
        <div id="terms-text">
                <h3>1. Introduction</h3>
                <p>Welcome to VogueVault! These Terms and Conditions govern your use of our website, services, and products. By accessing or using VogueVault, you agree to comply with and be bound by these Terms.</p>

                <h3>2. Use of Services</h3>
                <p><strong>Account:</strong> To access certain features, you may need to create an account. You are responsible for maintaining the confidentiality of your account information and are liable for all activities under your account.</p>
                <p><strong>Prohibited Activities:</strong> You agree not to engage in activities that violate any applicable laws, infringe on the rights of others, or interfere with the operation of our Services. This includes, but is not limited to, fraudulent activities, distribution of malware, or attempts to gain unauthorized access to our systems.</p>

                <h3>3. Orders and Payments</h3>
                <p><strong>Product Availability:</strong> All products are subject to availability. We reserve the right to limit quantities, reject orders, or discontinue products without prior notice.</p>
                <p><strong>Pricing:</strong> Prices are subject to change without notice. The price applicable at the time of your order will be the price in effect.</p>
                <p><strong>Payment:</strong> Payment must be made at the time of purchase. We accept various payment methods, including credit cards, debit cards, and other payment gateways as specified on our website. You agree to provide accurate billing and contact information. Failure to do so may result in delays or cancellation of your order.</p>

                <h3>4. Shipping and Delivery</h3>
                <p><strong>Shipping:</strong> We offer various shipping options. Delivery times may vary based on your location and chosen shipping method. Shipping fees and estimated delivery times will be provided at checkout.</p>
                <p><strong>Risk of Loss:</strong> The risk of loss and title for products pass to you upon delivery of the products to the carrier. VogueVault is not responsible for any delays caused by the carrier or customs.</p>

                <h3>5. Returns and Refunds</h3>
                <p><strong>Return Policy:</strong> You may return eligible products within 14 days of receipt. The products must be in their original condition, unused, and in their original packaging with all tags attached.</p>
                <p><strong>Refunds:</strong> Refunds will be issued to the original payment method within 7-14 business days after we receive and inspect the returned products. Shipping fees are non-refundable unless the return is due to our error.</p>

                <h3>6. Intellectual Property</h3>
                <p><strong>Ownership:</strong> All content on VogueVault, including logos, images, text, and design, is our property or the property of our licensors and is protected by intellectual property laws.</p>
                <p><strong>License:</strong> You are granted a limited, non-exclusive, non-transferable license to access and use our Services for personal, non-commercial purposes. Any unauthorized use of our content may violate copyright, trademark, and other laws.</p>

                <h3>7. Privacy</h3>
                <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your information.</p>

                <h3>8. Limitation of Liability</h3>
                <p>To the maximum extent permitted by law, VogueVault shall not be liable for any indirect, incidental, special, or consequential damages arising out of or in connection with your use of our Services, including but not limited to loss of profits, data, or other intangible losses, even if we have been advised of the possibility of such damages.</p>

                <h3>9. Indemnification</h3>
                <p>You agree to indemnify, defend, and hold harmless VogueVault, its affiliates, and their respective officers, directors, employees, and agents from and against any claims, liabilities, damages, losses, or expenses arising out of your use of our Services or your violation of these Terms.</p>

                <h3>10. Governing Law</h3>
                <p>These Terms shall be governed by and construed in accordance with the laws of the Republic of the Philippines. Any disputes arising from these Terms shall be resolved exclusively in the courts of the Philippines.</p>

                <h3>11. Changes to Terms</h3>
                <p>We may update these Terms from time to time. The updated Terms will be posted on our website with the effective date. Your continued use of our Services after the posting of changes constitutes your acceptance of the new Terms.</p>

                <h3>12. Contact Us</h3>
                <p>If you have any questions about these Terms, please contact us at voguevault@gmail.com or through our contact page.</p>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Password validation
    document.querySelector('form').addEventListener('submit', function(e) {
        var password = document.getElementById('password').value;
        var confirm_password = document.getElementById('confirm_password').value;

        if (password.length < 8) {
            alert('Password must be at least 8 characters long.');
            e.preventDefault();
            return;
        }

        if (password != confirm_password) {
            alert('Passwords do not match.');
            e.preventDefault();
            return;
        }
    });

    // Terms and Conditions Modal
    var modal = document.getElementById("terms-modal");
    var btn = document.getElementById("open-terms");
    var span = document.getElementsByClassName("close")[0];

    function toggleBodyScroll(isModalOpen) {
        if (isModalOpen) {
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = getScrollbarWidth() + 'px';
        } else {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }

    function getScrollbarWidth() {
        return window.innerWidth - document.documentElement.clientWidth;
    }

    function openModal(e) {
        e.preventDefault();
        modal.style.display = "block";
        toggleBodyScroll(true);
    }

    function closeModal() {
        modal.style.display = "none";
        toggleBodyScroll(false);
    }

    btn.addEventListener('click', openModal);
    span.addEventListener('click', closeModal);

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            closeModal();
        }
    });


    // Prevent form submission if terms are not accepted
    document.querySelector('form').addEventListener('submit', function(e) {
        var termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            alert('You must accept the Terms and Conditions to register.');
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
