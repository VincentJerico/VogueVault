<?php
require_once 'includes/connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <title>VogueVault - Product Listing</title>
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
                        <a href="index.php" class="logo">
                            <img src="assets/images/logo_landscape.png">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="index.php">Home</a></li>
                            <li class="scroll-to-section"><a href="index.php">Men's</a></li>
                            <li class="scroll-to-section"><a href="index.php">Women's</a></li>
                            <li class="scroll-to-section"><a href="index.php">Kid's</a></li>
                            <li class="submenu">
                                <a href="javascript:;">Pages</a>
                                <ul>
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="products.php">Products</a></li>
                                    <li><a href="single-product.php">Single Product</a></li>
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
                            <li class="scroll-to-section"><a href="index.php">Explore</a></li>
                            <li class="profile-nav"><a href="javascript:void(0);" id="profileToggle">Profile</a></li>
                            <li class=""><a href="login.php">Logout</a></li>
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
            <h2>User Profile</h2>
            <div id="profileInfo">
                <!-- Profile information will be loaded here -->
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area Start ***** -->
    <div class="page-heading" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-content">
                        <h2>Check Our Products</h2>
                        <span>Discover our latest fashion collections and trendy styles</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->
    <!-- ***** Products Area Starts ***** -->
    <section class="section" id="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Our Latest Products</h2>
                        <span>Check out all of our latest products.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/men-1modularjacket.png" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Carhartt Modular <br> Jacket</h4>
                            <span>₱6199.00</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/men-2pants.png" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Uniqlo Cargo Pants</h4>
                            <span>₱1990.00</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/men-3shoes.jpg" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Converse Chuck '70s</h4>
                            <span>₱4099.00</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/woman-1shirt.png" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Uniqlo Cap-Sleeve <br> Tshirt</h4>
                            <span>₱499.00</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/woman-2cardigan.png" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Uniqlo Oversized <br> Japanese Style Cardigan</h4>
                            <span>₱899.00</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/woman-3trouser.png" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Uniqlo Wide-Leg <br> Trouser</h4>
                            <span>₱799.00</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/kid-01.jpg" alt="">
                        </div>
                        <div class="down-content">
                            <h4>School Collection</h4>
                            <span>-----</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/kid-02.jpg" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Summer Collection</h4>
                            <span>-----</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="single-product.php"><i class="fa fa-eye"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-star"></i></a></li>
                                    <li><a href="single-product.php"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <img src="assets/images/kid-03.jpg" alt="">
                        </div>
                        <div class="down-content">
                            <h4>Casual Collection</h4>
                            <span>-----</span>
                            <ul class="stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="pagination">
                        <ul>
                            <li class="active">
                                <a href="#">1</a>
                            </li>
                            <li>
                                <a href="#">2</a>
                            </li>
                            <li>
                                <a href="#">3</a>
                            </li>
                            <li>
                                <a href="#">4</a>
                            </li>
                            <li>
                                <a href="#">></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Products Area Ends ***** -->
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
    });
    </script>
</body>
</html>
