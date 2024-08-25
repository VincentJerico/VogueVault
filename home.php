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
function getProductsByCategory($pdo, $category, $limit = 4) {
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
    <link rel="icon" type="image/x-icon" href="assets/images/Logo_Transparent.png">
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
                            <img src="assets/images/logo_landscape.png">
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
                                        <li><a href="single-product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="view-product-btn"><i class="fa fa-shopping-cart"></i></a></li>
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
                                        <li><a href="single-product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="view-product-btn"><i class="fa fa-shopping-cart"></i></a></li>
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
                                        <li><a href="single-product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="view-product-btn"><i class="fa fa-shopping-cart"></i></a></li>
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
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat dicta deserunt enim expedita animi impedit praesentium cupiditate est quia similique totam sequi aliquid hic, unde dignissimos explicabo et itaque! Nostrum.</span>
                        <div class="quote">
                            <i class="fa fa-quote-left"></i><p>Fashion is the armor to survive the reality of everyday life." — Bill Cunningham</p>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam, modi nam est dolor, qui illo a saepe quisquam eveniet suscipit facilis quas enim nesciunt.</p>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. <a rel="nofollow" href="https://paypal.me/templatemo" target="_blank">support us</a> a little via PayPal. Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
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
                                    <span>Over 304 Products</span>
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
                        <span>Lorem ipsum, dolor sit amet consectetur adipisicing elit.</span>
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
                        <li><a href="#">Help</a></li>
                        <li><a href="#">FAQ's</a></li>
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
    $(document).ready(function() {
        $("#profileToggle").click(function() {
            $("#profileSlider").toggleClass("active");
            
            if ($("#profileSlider").hasClass("active")) {
                // Load profile information
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

                // Load cart items
                $.ajax({
                    url: 'get_cart.php',
                    type: 'GET',
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.success) {
                            let cartHtml = '<h3>Your Cart</h3>';
                            if (data.cart_items.length > 0) {
                                data.cart_items.forEach(item => {
                                    cartHtml += `
                                        <div class="cart-item">
                                            <p>${item.name} - Quantity: ${item.quantity} - Price: ₱${(item.price * item.quantity).toFixed(2)}</p>
                                            <button class="buy-now-btn" data-cart-id="${item.cart_id}">Buy Now</button>
                                        </div>
                                    `;
                                });
                            } else {
                                cartHtml += '<p>Your cart is empty.</p>';
                            }
                            $("#cartItems").html(cartHtml);

                            // Add event listener for Buy Now buttons
                            $(".buy-now-btn").click(function() {
                                const cartId = $(this).data('cart-id');
                                $.ajax({
                                    url: 'place-order.php',
                                    type: 'POST',
                                    data: { cart_id: cartId },
                                    success: function(response) {
                                        const data = JSON.parse(response);
                                        if (data.success) {
                                            alert(data.message);
                                            // Reload cart items
                                            $("#profileToggle").click().click();
                                        } else {
                                            alert('Failed to place order: ' + data.message);
                                        }
                                    },
                                    error: function() {
                                        alert('Error placing order.');
                                    }
                                });
                            });
                        } else {
                            $("#cartItems").html("<p>Error loading cart items.</p>");
                        }
                    },
                    error: function() {
                        $("#cartItems").html("<p>Error loading cart items.</p>");
                    }
                });
            }
        });

            $("#editProfileBtn").click(function() {
                window.location.href = "edit_profile.php";
            });

            // Logout functionality
            $("#logoutBtn").click(function() {
                window.location.href = "logout.php";
            });

            // Login functionality
            $("#loginBtn").click(function() {
                window.location.href = "index.php";
            });

            // Close profile slider when clicking outside of it
            $(document).click(function(event) {
                if (!$(event.target).closest("#profileSlider, #profileToggle").length) {
                    $("#profileSlider").removeClass("active");
                }
            });
        });
    </script>

    <script>
            $(document).ready(function() {
                $('.view-product-btn').on('click', function(e) {
                    e.preventDefault(); // Prevent the default anchor behavior
                    var href = $(this).attr('href');
                    window.location.href = href; // Redirect manually
                });
            });

            // ... (include the rest of your existing JavaScript for product viewing and rating)
            $(document).ready(function() {
                $('.rate-star').on('click', function() {
                    var rating = $(this).data('rate');
                    var productId = $(this).data('product-id');

                    $.ajax({
                        url: 'rate-product.php',
                        method: 'POST',
                        data: {
                            product_id: productId,
                            rating: rating
                        },
                        success: function(response) {
                            alert('Rating submitted successfully!');
                            location.reload(); // Reload to update the rating
                        },
                        error: function() {
                            alert('Error submitting rating.');
                        }
                    });
                });
            });

            $(document).ready(function() {
                $('.rate-product .rate-star').on('mouseenter', function() {
                    var rating = $(this).data('rate');
                    $(this).parent().find('.rate-star').each(function() {
                        if ($(this).data('rate') <= rating) {
                            $(this).addClass('hovered');
                        }
                    });
                }).on('mouseleave', function() {
                    $(this).parent().find('.rate-star').removeClass('hovered');
                });

                $('.rate-product .rate-star').on('click', function() {
                    var rating = $(this).data('rate');
                    var productId = $(this).data('product-id');

                    $(this).parent().find('.rate-star').removeClass('selected');
                    $(this).parent().find('.rate-star').each(function() {
                        if ($(this).data('rate') <= rating) {
                            $(this).addClass('selected');
                        }
                    });

                    // Your existing AJAX call to submit the rating
                    $.ajax({
                        url: 'rate-product.php',
                        method: 'POST',
                        data: {
                            product_id: productId,
                            rating: rating
                        },
                        success: function(response) {
                            alert('Rating submitted successfully!');
                            location.reload(); // Reload to update the rating
                        },
                        error: function() {
                            alert('Error submitting rating.');
                        }
                    });
                });
            });
    </script>

    <script>
        $(document).ready(function() {
            $('.view-product-btn').on('click', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                $.ajax({
                    url: 'get-product-details.php',
                    method: 'GET',
                    data: { id: productId },
                    success: function(response) {
                        showProductModal(response);
                    },
                    error: function() {
                        alert('Error fetching product details');
                    }
                });
            });
        });

            // Function to handle Add to Cart
            function addToCart(productId, quantity) {
                $.ajax({
                    url: 'add-to-cart.php',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error adding product to cart');
                    }
                });
            }

            // Function to handle Buy Now
            function buyNow(productId, quantity) {
                $.ajax({
                    url: 'place-order.php',
                    method: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Optionally redirect to a confirmation page
                            // window.location.href = 'order-confirmation.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error placing order');
                    }
                });
            }

            // Event delegation for dynamically added elements
            $(document).on('click', '.add-to-cart-btn', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                var quantity = 1; // You might want to add a quantity input field in your modal
                addToCart(productId, quantity);
            });

            $(document).on('click', '.buy-now-btn', function(e) {
                e.preventDefault();
                var productId = $(this).data('product-id');
                var quantity = 1; // You might want to add a quantity input field in your modal
                buyNow(productId, quantity);
            });


        function showProductModal(productDetails) {
            var modal = $('<div class="product-modal"></div>');
            modal.html(productDetails);
            $('body').append(modal);
            modal.show();

            $('.close-modal').on('click', function() {
                modal.remove();
            });

            $(document).on('click', function(event) {
                if (!$(event.target).closest('.product-details').length && !$(event.target).is('.product-details')) {
                    modal.remove();
                }
            });
        }
    </script>
    
</body>
</html>