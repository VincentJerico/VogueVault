<?php
session_start();
require_once 'includes/connection.php'; // Include your database connection file

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the statement to get the user ID and hashed password
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username_or_email, $username_or_email]);
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
        $error_message = "No user found with that username or email.";
    }
}

// Fetch preview products (adjust the query as needed)
$preview_stmt = $pdo->query("SELECT * FROM products LIMIT 30");
$preview_products = $preview_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VogueVault - Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/logosquaretransparent.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .logo img {
            width: 150px;
            height: auto;
        }

        .main-section {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .main-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.1) 70%, rgba(0, 0, 0, 0) 100%), url('assets/images/bnwbg2.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(5px) brightness(0.5);
            z-index: -1;
        }

        .container {
            display: flex;
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: 50%;
            max-width: 75%;
            width: 100%;
            z-index: 1;
        }

        .welcome-section {
            flex: 1;
            padding: 0;
            background-color: #153448;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            aspect-ratio: 1200 / 628;
        }

        .slider-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
        }

        .slide {
            min-width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            z-index: 10;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .dots {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
        }

        .dot {
            width: 10px;
            height: 10px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer;
        }

        .dot.active {
            background-color: white;
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

        .preview-section {
            min-height: 100vh;
            width: 100%;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
        }

        .preview-container {
            width: 100%;
            max-width: 1200px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .preview-title {
            font-size: 32px;
            color: #153448;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .preview-item {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .preview-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .preview-item .thumb {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1 / 1;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .preview-item:hover img {
            transform: scale(1.05);
        }

        .preview-item .hover-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(21, 52, 72, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .preview-item:hover .hover-content {
            opacity: 1;
        }

        .preview-item .hover-content ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .preview-item .hover-content ul li a {
            color: white;
            font-size: 24px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 50%;
            transition: background-color 0.3s ease;
        }

        .preview-item .hover-content ul li a:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .preview-item .down-content {
            padding: 20px;
        }

        .preview-item h4 {
            margin: 0 0 10px;
            color: #153448;
            font-size: 18px;
            font-weight: 600;
        }

        .preview-item .price {
            color: #666;
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }

        .preview-item .stars {
            color: red;
            list-style-type: none;
            padding: 0;
            margin: 10px 0 0;
            display: flex;
            justify-content: center;
        }

        .preview-item .stars li {
            margin: 0 2px;
        }

        .no-image {
            background-color: #f0f0f0;
            height: 250px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #666;
            border-radius: 8px 8px 0 0;
        }

        @media (max-width: 1200px) {
            .preview-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .preview-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .preview-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .preview-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
            }
            .welcome-section {
                width: 100%;
                aspect-ratio: 1200 / 628;
            }
            .login-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="assets/images/logosquaretransparent.png" alt="VogueVault Logo">
    </div>


    <div class="main-section" id="top">
        <div class="container">
            <div class="welcome-section">
                <div class="slider-container">
                    <div class="slider">
                        <img src="assets/images/ad1.png" alt="Ad 1" class="slide">
                        <img src="assets/images/ad2.png" alt="Ad 2" class="slide">
                        <img src="assets/images/ad3.png" alt="Ad 3" class="slide">
                        <img src="assets/images/ad4.png" alt="Ad 4" class="slide">
                        <img src="assets/images/ad5.png" alt="Ad 5" class="slide">
                    </div>
                    <button class="prev">&lt;</button>
                    <button class="next">&gt;</button>
                    <div class="dots"></div>
                </div>
            </div>
            <div class="login-section">
                <?php if ($error_message): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <center><h1 style="color:#153448;">Log in</h1></center>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username or Email:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" required>
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
    </div>

    <div class="preview-section" id="preview">
        <div class="preview-container">
            <h2 class="preview-title">Featured Products</h2>
            <div class="preview-grid">
                <?php foreach ($preview_products as $product): ?>
                    <div class="preview-item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="#" class="view-product-btn" data-product-id="<?php echo htmlspecialchars($product['id']); ?>"><i class="fa fa-eye"></i></a></li>
                                </ul>
                            </div>
                            <?php
                            $imagePath = !empty($product['image']) ? 'uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
                            $imageUrl = file_exists($imagePath) ? $imagePath : 'assets/images/default-product-image.jpg';
                            ?>
                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="down-content">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <span>₱<?php echo number_format($product['price'], 2); ?></span>
                            <ul class="stars">
                                <?php
                                $rating = isset($product['rating']) ? floatval($product['rating']) : 0;
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $rating):
                                        echo '<li><i class="fa fa-star"></i></li>';
                                    elseif ($i - 0.5 <= $rating):
                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                    else:
                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                    endif;
                                endfor;
                                ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        const slider = document.querySelector('.slider');
        const slides = document.querySelectorAll('.slide');
        const prevButton = document.querySelector('.prev');
        const nextButton = document.querySelector('.next');
        const dotsContainer = document.querySelector('.dots');

        let currentSlide = 0;
        let slideInterval;

        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function goToSlide(n) {
            currentSlide = (n + slides.length) % slides.length;
            slider.style.transform = `translateX(${-currentSlide * 100}%)`;
            updateDots();
        }

        function updateDots() {
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        function startSlideshow() {
            slideInterval = setInterval(nextSlide, 3000);
        }

        function stopSlideshow() {
            clearInterval(slideInterval);
        }

        prevButton.addEventListener('click', () => {
            prevSlide();
            stopSlideshow();
            startSlideshow();
        });

        nextButton.addEventListener('click', () => {
            nextSlide();
            stopSlideshow();
            startSlideshow();
        });

        slider.addEventListener('mouseenter', stopSlideshow);
        slider.addEventListener('mouseleave', startSlideshow);

        startSlideshow();

        // Handle product clicks
        document.addEventListener('DOMContentLoaded', function() {
            const previewItems = document.querySelectorAll('.preview-item');
            previewItems.forEach(item => {
                item.addEventListener('click', () => {
                    window.location.href = '#top';
                });
            });
        });
    </script>
</body>
</html>