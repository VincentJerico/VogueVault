<?php
session_start();
require_once 'includes/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if the user came directly to this page
if (!isset($_SESSION['login_redirect'])) {
    $alert_message = "You've accessed this page directly. For the best experience, please use the login page.";
    $_SESSION['alert_message'] = $alert_message;
}

// Set the login redirect flag
$_SESSION['login_redirect'] = true;

// Check login status (but don't redirect)
$is_logged_in = isset($_SESSION['user_id']);

// Fetch products for each category
function getProductsByCategory($pdo, $category, $limit = 10) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = :category ORDER BY created_at DESC LIMIT :limit");
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$menProducts = getProductsByCategory($pdo, "Men's");
$womenProducts = getProductsByCategory($pdo, "Women's");
$kidsProducts = getProductsByCategory($pdo, "Kid's");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="/assets/fonts/poppins.css" rel="stylesheet">
    <title>VogueVault</title>
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl-carousel.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="stylesheet" href="assets/css/get-product-style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/logosquaretransparent.png">

    <style>
        /* FAQ Modal Styles */
        .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        }

        .modal-content {
        background-color: #ffffff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 500px; /* Reduced from 800px */
        max-height: 80vh;
        overflow-y: auto;
        }

        .close {
        color: #aaa;
        float: right;
        font-size: 24px;
        font-weight: bold;
        transition: color 0.3s ease;
        }

        .close:hover,
        .close:focus {
        color: #333;
        text-decoration: none;
        cursor: pointer;
        }

        #faq-content h2 {
        color: #153448;
        margin-bottom: 20px;
        font-size: 22px;
        text-align: center;
        }

        #faq-content h3 {
        color: #153448;
        margin-top: 20px;
        margin-bottom: 10px;
        font-size: 16px;
        }

        #faq-content p {
        color: #333;
        line-height: 1.5;
        margin-bottom: 15px;
        font-size: 14px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
        .modal-content {
            margin: 10% auto;
            padding: 15px;
            width: 95%;
        }

        #faq-content h2 {
            font-size: 20px;
        }

        #faq-content h3 {
            font-size: 15px;
        }

        #faq-content p {
            font-size: 13px;
        }
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['alert_message'])): ?>
    <script>
        alert("<?php echo addslashes($_SESSION['alert_message']); ?>");
    </script>
    <?php
        // Clear the alert message after displaying it
        unset($_SESSION['alert_message']);
    endif;
    ?>
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="home.php" class="logo">
                            <img src="assets/images/logolandscapetransparent.png" style="max-height: 100px; width: auto;">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top">Home</a></li>
                            <li class="scroll-to-section"><a href="#men">Men's</a></li>
                            <li class="scroll-to-section"><a href="#women">Women's</a></li>
                            <li class="scroll-to-section"><a href="#kids">Kid's</a></li>
                            <li class="submenu">
                                <a href="javascript:;">Pages</a>
                                <ul>
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="products.php">Products</a></li>
                                    <li><a href="contact.php">Contact Us</a></li>
                                </ul>
                            </li>
                            <!--
                            <li class="submenu">
                                <a href="javascript:;">Features</a>
                                <ul>
                                    <li><a href="#">Features Page 1</a></li>
                                    <li><a href="#">Features Page 2</a></li>
                                    <li><a href="#">Features Page 3</a></li>
                                </ul>
                            </li>
                            -->
                            <li class="scroll-to-section"><a href="#explore">Explore</a></li>
                            <?php if ($is_logged_in): ?>
                                <li class="profile-nav"><a href="javascript:void(0);" id="profileToggle">Profile</a></li>
                                <li class=""><a href="logout.php">Logout</a></li>
                            <?php else: ?>
                                <li class=""><a href="index.php">Login</a></li>
                            <?php endif; ?>
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->
    <!-- Profile Slider -->
    <div id="profileSlider" class="profile-slider">
        <div class="profile-content">
            <div id="profileInfo">
                <!-- Profile information will be loaded here -->
            </div>
            <div class="col mt-2"></div>
            <button id="editProfileBtn">Edit Profile</button>
            <div id="cartItems">
                <!-- Cart items will be loaded here -->
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="left-content">
                        <div class="thumb">
                            <div class="inner-content">
                                <h4>Welcome to VogueVault</h4>
                                <span>"Unveiling Timeless Style"</span>
                                <div class="main-border-button">
                                    <a href="products.php">Purchase Now!</a>
                                </div>
                            </div>
                            <img src="assets/images/left-banner.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="right-first-image">
                                    <div class="thumb">
                                        <div class="inner-content">
                                            <h4>Women</h4>
                                            <span>Best Clothes For Women</span>
                                        </div>
                                        <div class="hover-content">
                                            <div class="inner">
                                                <h4>Women</h4>
                                                <p>Check out our latest women's products</p>
                                                <div class="main-border-button">
                                                    <a href="#women" class="scroll-to-section">Discover More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <img src="assets/images/baner-right-image-01.jpg">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="right-first-image">
                                    <div class="thumb">
                                        <div class="inner-content">
                                            <h4>Men</h4>
                                            <span>Best Clothes For Men</span>
                                        </div>
                                        <div class="hover-content">
                                            <div class="inner">
                                                <h4>Men</h4>
                                                <p>Check out our latest men's products</p>
                                                <div class="main-border-button">
                                                    <a href="#men" class="scroll-to-section">Discover More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <img src="assets/images/baner-right-image-02.jpg">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="right-first-image">
                                    <div class="thumb">
                                        <div class="inner-content">
                                            <h4>Kids</h4>
                                            <span>Best Clothes For Kids</span>
                                        </div>
                                        <div class="hover-content">
                                            <div class="inner">
                                                <h4>Kids</h4>
                                                <p>Check out our latest kid's products</p>
                                                <div class="main-border-button">
                                                    <a href="#kids" class="scroll-to-section">Discover More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <img src="assets/images/baner-right-image-03.jpg">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="right-first-image">
                                    <div class="thumb">
                                        <div class="inner-content">
                                            <h4>More</h4>
                                            <span>Best Trend Products</span>
                                        </div>
                                        <div class="hover-content">
                                            <div class="inner">
                                                <h4>More</h4>
                                                <p>Check out our latest Products.</p>
                                                <div class="main-border-button">
                                                    <a href="#explore">Discover More</a>
                                                </div>
                                            </div>
                                        </div>
                                        <img src="assets/images/baner-right-image-04.jpg">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->
    <!-- ***** Men's Area Starts ***** -->
    <section class="section" id="men">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Men's Latest</h2>
                        <span>Check out our latest men's products</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="men-item-carousel">
                        <div class="owl-men-item owl-carousel">
                            <?php foreach ($menProducts as $product): ?>
                            <div class="item">
                                <div class="thumb">
                                <div class="hover-content">
                                    <ul>
                                        <li><a href="#men" class="view-product-btn" data-product-id="<?php echo htmlspecialchars($product['id']); ?>"><i class="fa fa-eye"></i></a></li>
                                    </ul>
                                </div>
                                    <?php
                                    $imagePath = !empty($product['image']) ? './uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
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
            </div>
        </div>
    </section>
    <!-- ***** Men's Area Ends ***** -->
    <!-- ***** Women Area Starts ***** -->
    <section class="section" id="women">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Women's Latest</h2>
                        <span>Check out our latest women's product</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="women-item-carousel">
                        <div class="owl-women-item owl-carousel">
                            <?php foreach ($womenProducts as $product): ?>
                            <div class="item">
                                <div class="thumb">
                                <div class="hover-content">
                                    <ul>
                                        <li><a href="#women" class="view-product-btn" data-product-id="<?php echo htmlspecialchars($product['id']); ?>"><i class="fa fa-eye"></i></a></li>
                                    </ul>
                                </div>
                                    <?php
                                    $imagePath = !empty($product['image']) ? './uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
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
            </div>
        </div>
    </section>
    <!-- ***** Women Area Ends ***** -->
    <!-- ***** Kids Area Starts ***** -->
    <section class="section" id="kids">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Kid's Latest</h2>
                        <span>Check out our latest kid's product</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="kid-item-carousel">
                        <div class="owl-kid-item owl-carousel">
                        <?php foreach ($kidsProducts as $product): ?>
                            <div class="item">
                                <div class="thumb">
                                <div class="hover-content">
                                    <ul>
                                        <li><a href="#kids" class="view-product-btn" data-product-id="<?php echo htmlspecialchars($product['id']); ?>"><i class="fa fa-eye"></i></a></li>
                                    </ul>
                                </div>
                                    <?php
                                    $imagePath = !empty($product['image']) ? './uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
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
            </div>
        </div>
    </section>
    <!-- ***** Kids Area Ends ***** -->
    <!-- ***** Explore Area Starts ***** -->
    <section class="section" id="explore">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="left-content">
                        <h2>Explore Our Products</h2>
                        <span>Discover the latest in fashion and style at VogueVault. From timeless classics to cutting-edge trends, our curated collection ensures you find the perfect piece to express your individuality. Whether you're dressing up for a special occasion or adding flair to your everyday wardrobe, we've got you covered.</span>
                        <div class="quote">
                            <i class="fa fa-quote-left"></i><p>Fashion is the armor to survive the reality of everyday life." — Bill Cunningham</p>
                        </div>
                        <p>At VogueVault, we believe that fashion is more than just clothing; it's a statement, a way to express who you are and how you feel. Our carefully selected pieces are designed to empower you, helping you navigate life with confidence and style.</p>
                        <p>Love what you see? You can support us by shopping your favorite styles or by contributing a little via <a rel="nofollow" href="https://paypal.me/templatemo" target="_blank">PayPal. </a>Every bit helps us bring you the best in fashion and continue curating a collection that speaks to your unique taste.</p>
                        <div class="main-border-button">
                            <a href="products.php">Discover More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="leather">
                                    <h4>Leather Bags</h4>
                                    <span>Latest Collection</span>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="first-image">
                                    <img src="assets/images/explore-image-01.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="second-image">
                                    <img src="assets/images/explore-image-02.jpg" alt="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="types">
                                    <h4>Different Types</h4>
                                    <span>Over 100+ Products</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Explore Area Ends ***** -->
    <!-- ***** Social Area Starts ***** -->
    <section class="section" id="social">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Social Media</h2>
                        <span>Stay connected with VogueVault on all your favorite platforms. Follow us for the latest trends, exclusive offers, and behind-the-scenes looks at our newest collections. Join our community and be a part of the conversation in fashion.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row images">
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Fashion</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-01.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>New</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-02.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Brand</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-03.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Makeup</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-04.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Leather</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-05.jpg" alt="">
                    </div>
                </div>
                <div class="col-2">
                    <div class="thumb">
                        <div class="icon">
                            <a href="http://instagram.com">
                                <h6>Bag</h6>
                                <i class="fa fa-instagram"></i>
                            </a>
                        </div>
                        <img src="assets/images/instagram-06.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Social Area Ends ***** -->
    <!-- ***** Subscribe Area Starts ***** -->
    <div class="subscribe">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li>Store Location:<br><span>Barangay Saksakan, Swerte Makailag St. Philippines</span></li>
                                <li>Phone:<br><span>0998-765-4321</span></li>
                                <li>Office Location:<br><span>CvSU TMCC Campus</span></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                                <li>Work Hours:<br><span>07:30 AM - 9:30 PM Daily</span></li>
                                <li>Email:<br><span>voguevault@gmail.com</span></li>
                                <li>Social Media:<br><span><a href="#">Facebook</a>, <a href="#">Instagram</a>, <a href="#">Linkedin</a></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Subscribe Area Ends ***** -->
    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="first-item">
                        <div class="logo">
                            <img src="assets/images/white-logo.png" alt="">
                        </div>
                        <ul>
                            <li><a href="#">143 Barangay Saksakan, Swerte Makailag St. Philippines</a></li>
                            <li><a href="#">voguevault@gmail.com</a></li>
                            <li><a href="#">0998-765-4321</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h4>Shopping &amp; Categories</h4>
                    <ul>
                        <li class="scroll-to-section"><a href="#men">Men’s Shopping</a></li>
                        <li class="scroll-to-section"><a href="#women">Women’s Shopping</a></li>
                        <li class="scroll-to-section"><a href="#kids">Kid's Shopping</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>Useful Links</h4>
                    <ul>
                        <li class="scroll-to-section"><a href="#top">Homepage</a></li>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>Help &amp; Information</h4>
                    <ul>
                        <li><a href="contact.php">Help</a></li>
                        <li><a href="#" onclick="return false;">FAQ's</a></li>
                    </ul>
                </div>
                <div class="col-lg-12">
                    <div class="under-footer">
                        <p>Copyright © <?php echo date("Y"); ?>. All Rights Reserved. <br> This website is for school project purposes only</p>
                        <ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="faq-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>FAQs</h2>
            <div id="faq-content">
                <h3>1. What is VogueVault?</h3>
                <p>VogueVault is a premier fashion e-commerce website inspired by the renowned "Vogue" brand. We specialize in delivering a curated collection of timeless fashion pieces for men, women, and kids. Our mission is to offer a seamless and superior shopping experience by blending high-quality, classic styles with the latest trends.</p>

                <h3>2. What products does VogueVault offer?</h3>
                <p>We offer a wide range of clothing and accessories for men, women, and kids. Our product categories include everyday wear, formal attire, seasonal collections, and exclusive designer collaborations, all selected for their quality and style.</p>

                <h3>3. How do I place an order?</h3>
                <p>To place an order, simply browse our website, select the items you wish to purchase, and add them to your cart. Once you're ready, proceed to checkout, where you can review your order, enter your shipping information, and complete your purchase.</p>

                <h3>4. What payment methods are accepted?</h3>
                <p>We accept various payment methods, including major credit/debit cards, PayPal, and other secure payment gateways. All transactions are encrypted to ensure your payment information is safe.</p>

                <h3>5. How can I track my order?</h3>
                <p>Once your order is shipped, you will receive a tracking number via email. You can use this number on our website to track the status of your delivery.</p>

                <h3>6. What is VogueVault's return policy?</h3>
                <p>We offer a hassle-free return policy. If you're not completely satisfied with your purchase, you can return it within 30 days of delivery for a full refund or exchange. The item must be in its original condition, with tags still attached.</p>

                <h3>7. How do I contact customer support?</h3>
                <p>You can reach our customer support team via email at voguevault@gmail.com, or by phone at 0998-765-4321 during our work hours from 07:30 AM to 09:30 PM. We're also available on our social media channels: Facebook, Instagram, and LinkedIn.</p>

                <h3>8. Does VogueVault offer international shipping?</h3>
                <p>Currently, VogueVault only ships within the Philippines. However, we are working on expanding our shipping options to include international destinations in the near future.</p>

                <h3>9. How do I stay updated on new arrivals and promotions?</h3>
                <p>You can stay updated by subscribing to our newsletter or following us on social media. We regularly share news about new arrivals, special promotions, and exclusive offers with our community.</p>

                <h3>10. Is it safe to shop on VogueVault?</h3>
                <p>Yes, shopping on VogueVault is completely safe. We prioritize your security by using advanced encryption technology to protect your personal and payment information.</p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Plugins -->
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/accordions.js"></script>
    <script src="assets/js/datepicker.js"></script>
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script> 
    <script src="assets/js/slick.js"></script> 
    <script src="assets/js/lightbox.js"></script> 
    <script src="assets/js/isotope.js"></script> 
    <!-- Global Init -->
    <script src="assets/js/custom.js"></script>

    <script>
        $(function() {
            var selectedClass = "";
            $("p").click(function(){
            selectedClass = $(this).attr("data-rel");
            $("#portfolio").fadeTo(50, 0.1);
                $("#portfolio div").not("."+selectedClass).fadeOut();
            setTimeout(function() {
                $("."+selectedClass).fadeIn();
                $("#portfolio").fadeTo(50, 1);
            }, 500);
                
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById("faq-modal");
            var btn = document.querySelector("a[href='#'][onclick='return false;']");
            var span = modal.querySelector(".close");

            if (!modal) {
                console.error("Modal element not found. Make sure you have an element with id 'faq-modal'");
                return;
            }

            if (!btn) {
                console.error("FAQ button not found. Make sure you have an <a> element with href='#' and onclick='return false;'");
                return;
            }

            function toggleBodyScroll(isModalOpen) {
                document.body.style.overflow = isModalOpen ? 'hidden' : '';
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

            if (span) {
                span.addEventListener('click', closeModal);
            } else {
                console.warn("Close button not found in modal. Users may not be able to close the modal easily.");
            }

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $("#profileToggle").click(function() {
                $("#profileSlider").toggleClass("active");
                if ($("#profileSlider").hasClass("active")) {
                    loadProfileInfo();
                    loadCartItems();
                }
            });

            $("#editProfileBtn").click(function() {
                window.location.href = "edit_profile.php";
            });

            $("#logoutBtn").click(function() {
                window.location.href = "logout.php";
            });

            $("#loginBtn").click(function() {
                window.location.href = "index.php";
            });

            $(document).click(function(event) {
                if (!$(event.target).closest("#profileSlider, #profileToggle").length) {
                    $("#profileSlider").removeClass("active");
                }
            });

            $('.view-product-btn').on('click', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                $.ajax({
                    url: 'get-product-details.php',
                    method: 'GET',
                    data: { id: productId },
                    success: showProductModal,
                    error: function() {
                        alert('Error fetching product details');
                    }
                });
            });

            $(document).on('click', '.buy-now-btn', function() {
                var productId = $(this).data('product-id');
                var quantity = $('#quantity').val() || 1;

                window.location.href = 'order_form.php?product_id=' + productId + '&quantity=' + quantity;
            });
        });

        function loadProfileInfo() {
            $.ajax({
                url: 'get_profile.php',
                type: 'GET',
                success: function(response) {
                    $("#profileInfo").html(response);
                },
                error: function() {
                    $("#profileInfo").html("<p>Error loading profile information.</p>");
                }
            });
        }

        function loadCartItems() {
            $.ajax({
                url: 'get_cart.php',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let cartHtml = '<h3>Your Cart</h3>';
                        if (response.cart_items.length > 0) {
                            response.cart_items.forEach(item => {
                                cartHtml += `
                                    <div class="cart-item">
                                        <p>${item.name} - Quantity: ${item.quantity} - Price: ₱${(item.price * item.quantity).toFixed(2)}</p>
                                        <button class="buy-now-btn" data-product-id="${item.product_id}" data-cart-id="${item.cart_id}">Buy Now</button>
                                    </div>
                                `;
                            });
                            cartHtml += `
                                <button id="buyAllBtn" style="
                                    background-color: #153448;
                                    color: #fff;
                                    border: none;
                                    padding: 10px;
                                    cursor: pointer;
                                    font-size: 16px;
                                    border-radius: 3px;
                                    transition: background-color 0.3s ease;
                                ">Buy All</button>
                            `;
                        } else {
                            cartHtml += '<p>Your cart is empty.</p>';
                        }
                        $("#cartItems").html(cartHtml);

                        // Add click event and hover effect for Buy All button
                        $("#buyAllBtn").click(function() {
                            window.location.href = 'order_form.php?buy_all=true';
                        }).hover(
                            function() {
                                $(this).css("background-color", "#0d2a3a");
                            },
                            function() {
                                $(this).css("background-color", "#153448");
                            }
                        );
                    } else {
                        $("#cartItems").html("<p>Error loading cart items.</p>");
                    }
                },
                error: function() {
                    $("#cartItems").html("<p>Error loading cart items.</p>");
                }
            });
        }

        function clearCartDisplay() {
            $("#cartItems").html('<h3>Your Cart</h3><p>Your cart is empty.</p>');
        }

        function editAddress() {
            $('#addressDisplay').hide();
            $('#addressEditForm').show();
        }

        function cancelEditAddress() {
            $('#addressDisplay').show();
            $('#addressEditForm').hide();
        }

        function saveAddress() {
            var newAddress = $('#newAddress').val();
            $.ajax({
                url: 'update_address.php',
                type: 'POST',
                data: {address: newAddress},
                success: function(response) {
                    if(response === 'success') {
                        $('#addressDisplay').text(newAddress);
                        cancelEditAddress();
                        loadProfileInfo();
                    } else {
                        alert('Failed to update address');
                    }
                },
                error: function() {
                    alert('Error updating address');
                }
            });
        }


        function showProductModal(productDetails) {
            var modal = $('<div class="product-modal"></div>').html(productDetails).appendTo('body').show();
            
            var quantityHtml = `
                <div class="quantity-container">
                    <button class="quantity-control decrement">-</button>
                    <input type="number" id="quantity" value="1" min="1">
                    <button class="quantity-control increment">+</button>
                </div>
            `;
            modal.find('.product-details').append(quantityHtml);
            
            $('.close-modal').on('click', function() {
                modal.remove();
            });
            
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.product-details').length && !$(event.target).is('.product-details')) {
                    modal.remove();
                }
            });
        }

            // Rate product
            $('.rate-star').on('click', function() {
                var rating = $(this).data('rate');
                var productId = $(this).data('product-id');
                ajaxRequest('rate-product.php', { product_id: productId, rating: rating }, 'Submitting rating', true);
            });

            // Star rating hover effect
            $('.rate-product .rate-star').hover(
                function() {
                    var rating = $(this).data('rate');
                    $(this).parent().find('.rate-star').each(function() {
                        $(this).toggleClass('hovered', $(this).data('rate') <= rating);
                    });
                },
                function() {
                    $(this).parent().find('.rate-star').removeClass('hovered');
                }
            );

        function ajaxRequest(url, data, action, reload = false) {
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        if (reload) {
                            if (url === 'place-order.php') {
                                window.location.href = 'order-confirmation.php';
                            } else {
                                location.reload();
                            }
                        }
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert(`Error ${action.toLowerCase()}. Please try again.`);
                }
            });
        }
    </script>
    
</body>
</html>