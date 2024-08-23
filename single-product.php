<?php
session_start();
require_once 'includes/connection.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch product details based on the passed ID
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT *, stock FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo "Product not found!";
        exit();
    }
} else {
    echo "No product selected!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <title>VogueVault - Product Detail</title>
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl-carousel.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="icon" type="image/x-icon" href="assets/images/Logo_Transparent.png">
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
                            <li class="scroll-to-section"><a href="home.php">Home</a></li>
                            <li class="scroll-to-section"><a href="home.php">Men's</a></li>
                            <li class="scroll-to-section"><a href="home.php">Women's</a></li>
                            <li class="scroll-to-section"><a href="home.php">Kid's</a></li>
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
                            </li>-->
                            <li class="scroll-to-section"><a href="home.php">Explore</a></li>
                            <li class="profile-nav"><a href="javascript:void(0);" id="profileToggle">Profile</a></li>
                            <li class=""><a href="logout.php">Logout</a></li>
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
        </div>
    </div>
    <!-- ***** Main Banner Area Start ***** -->
    <div class="page-heading" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-content">
                        <h2>Single Product Page</h2>
                        <span>Detailed view of your selected item</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->
    <!-- ***** Product Area Starts ***** -->
    <section class="section" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="left-images">
                        <?php
                        $imagePath = !empty($product['image']) ? './uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
                        $imageUrl = file_exists($imagePath) ? $imagePath : 'assets/images/default-product-image.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="right-content">
                        <div class="product-info">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <span class="price">₱<?php echo number_format($product['price'], 2); ?></span>
                            <ul class="stars">
                                <?php 
                                $rating = isset($product['rating']) ? floatval($product['rating']) : 0;
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo '<li><i class="fa fa-star"></i></li>';
                                    } elseif ($i - 0.5 <= $rating) {
                                        echo '<li><i class="fa fa-star-half-o"></i></li>';
                                    } else {
                                        echo '<li><i class="fa fa-star-o"></i></li>';
                                    }
                                }
                                ?>
                            </ul>
                            </div>
                        <div class="description">
                            <span><?php echo htmlspecialchars($product['description']); ?></span>
                        </div>
                        <div class="quote">
                            <i class="fa fa-quote-left"></i><p><?php echo htmlspecialchars($product['quote'] ?? ''); ?></p>
                        </div>
                        <div class="quantity-content">
                            <div class="left-content">
                                <h6>No. of Orders</h6>
                            </div>
                            <div class="right-content">
                                <div class="quantity buttons_added">
                                    <input type="button" value="-" class="minus">
                                    <input type="number" step="1" min="1" max="<?php echo isset($product['stock']) ? htmlspecialchars($product['stock']) : ''; ?>" name="quantity" value="1" title="Qty" class="input-text qty text" size="4">
                                    <input type="button" value="+" class="plus">
                                </div>
                            </div>
                        </div>
                        <div class="total">
                            <h4>Total: ₱<span id="totalPrice"><?php echo number_format($product['price'], 2); ?></span></h4>
                        </div>
                        <div class="buttons-container">
                            <div class="main-border-button"><a href="#" id="addToCartBtn">Add To Cart</a></div>
                            <div class="main-border-button"><a href="#" id="buyNowBtn">Buy Now</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Product Area Ends ***** -->
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
                        <li><a href="#">Men’s Shopping</a></li>
                        <li><a href="#">Women’s Shopping</a></li>
                        <li><a href="#">Kid's Shopping</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="#">Homepage</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>Help &amp; Information</h4>
                    <ul>
                        <li><a href="#">Help</a></li>
                        <li><a href="#">FAQ's</a></li>
                        <li><a href="#">Shipping</a></li>
                        <li><a href="#">Tracking ID</a></li>
                    </ul>
                </div>
                <div class="col-lg-12">
                    <div class="under-footer">
                        <p>Copyright © <?php echo date("Y"); ?>. All Rights Reserved. <br> This website is for school project purposes only</p>
                        <ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#"><i class="fa fa-behance"></i></a></li>
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
            }
        });

        $("#editProfileBtn").click(function() {
            window.location.href = "edit_profile.php";
        });
    });
    </script>

    <script>
        $(document).ready(function() {
            let price = <?php echo $product['price']; ?>;
            let stock = <?php echo $product['stock']; ?>;

            // Update total when quantity changes
            $(".qty").on('input', function() {
                let quantity = $(this).val();
                let total = price * quantity;
                $("#totalPrice").text(total.toFixed(2));
            });

            // Increment quantity
            $(".plus").click(function() {
                let $input = $(this).prev('input.qty');
                let val = parseInt($input.val());
                if (val < stock) {
                    $input.val(val + 1).trigger('input');
                }
            });

            // Decrement quantity
            $(".minus").click(function() {
                let $input = $(this).next('input.qty');
                let val = parseInt($input.val());
                if (val > 1) {
                    $input.val(val - 1).trigger('input');
                }
            });

            // Add to cart
            $("#addToCartBtn").click(function(e) {
                e.preventDefault();
                let quantity = $(".qty").val();
                $.ajax({
                    url: 'add-to-cart.php',
                    type: 'POST',
                    data: {
                        product_id: <?php echo $product['id']; ?>,
                        quantity: quantity
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.success) {
                            alert(data.message);
                        } else {
                            alert('Failed to add product: ' + data.message);
                        }
                    },
                    error: function() {
                        alert('Error adding product to cart.');
                    }
                });
            });
            // Buy Now
            $("#buyNowBtn").click(function(e) {
                e.preventDefault();
                let quantity = $(".qty").val();
                $.ajax({
                    url: 'place-order.php', // Assume you create this file to handle the order
                    type: 'POST',
                    data: {
                        product_id: <?php echo $product['id']; ?>,
                        quantity: quantity
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.success) {
                            alert(data.message);
                            window.location.href = 'order-confirmation.php'; // Redirect to confirmation page
                        } else {
                            alert('Failed to place order: ' + data.message);
                        }
                    },
                    error: function() {
                        alert('Error placing order.');
                    }
                });
            });
        });
    </script>
</body>
</html>
