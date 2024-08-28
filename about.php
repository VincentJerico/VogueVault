<?php
session_start();
require_once 'includes/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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
    <link href="/assets/fonts/poppins.css" rel="stylesheet">
    <title>VogueVault - About</title>
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl-carousel.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
    <link rel="icon" type="image/x-icon" href="assets/images/logosquaretransparent.png">
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
                            <img src="assets/images/logolandscapetransparent.png" style="max-height: 100px; width: auto;">
                        </a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="home.php #top">Home</a></li>
                            <li class="scroll-to-section"><a href="home.php #men">Men's</a></li>
                            <li class="scroll-to-section"><a href="home.php #women">Women's</a></li>
                            <li class="scroll-to-section"><a href="home.php #kids">Kid's</a></li>
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
                            <li class="scroll-to-section"><a href="home.php #explore">Explore</a></li>
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
    <div class="page-heading about-page-heading" id="top">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-content">
                        <h2>About Our Company</h2>
                        <span>Know more about us!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->
    <!-- ***** About Area Starts ***** -->
    <div class="about-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="left-image">
                        <img src="assets/images/about-left-image.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-content">
                        <h4>About Us &amp; Our Skills</h4>
                        <span>At VogueVault, we believe that fashion is a reflection of the soul—a way to express your innermost self to the world. Our team of dedicated designers and fashion enthusiasts work tirelessly to bring you collections that resonate with your unique style and personality.</span>
                        <div class="quote">
                            <i class="fa fa-quote-left"></i><p>"Fashion is about something that comes from within you." — Ralph Lauren</p>
                        </div>
                        <p>With a deep understanding of fashion's transformative power, we combine creativity, innovation, and craftsmanship to deliver pieces that not only look good but feel good too. From fabric selection to final stitch, every detail is meticulously crafted to ensure you get the best.

Our skills are rooted in a passion for design, an eye for trends, and a commitment to quality. Whether it's tailoring the perfect fit or staying ahead of the latest fashion movements, VogueVault is your trusted partner in style.</p>
                        <!--<ul>
                            <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        </ul>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** About Area Ends ***** -->
    <!-- ***** Our Team Area Starts ***** -->
    <section class="our-team">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Our Amazing Team</h2>
                        <span>We are a group of passionate Information Technology students from Cavite State University Trece Martires City Campus. As part of our academic journey, we collaborated to design and build the VogueVault website. Our diverse skills in web development, design, and technology have come together to create an online platform that seamlessly blends fashion and functionality. We're proud to have contributed to this project, bringing VogueVault's vision to life and enhancing your online shopping experience.</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="team-item">
                        <div class="thumb">
                            <div class="hover-effect">
                                <div class="inner-content">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <img src="assets/images/team-1vijey.png">
                        </div>
                        <div class="down-content">
                            <h4>Vincent Jerico Alcuran</h4>
                            <span>Front-end Developer & Back-end Developer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="team-item">
                        <div class="thumb">
                            <div class="hover-effect">
                                <div class="inner-content">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <img src="assets/images/team-2jp.jpg">
                        </div>
                        <div class="down-content">
                            <h4>John Paul Comediero</h4>
                            <span>Front-end Developer & Back-end Developer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="team-item">
                        <div class="thumb">
                            <div class="hover-effect">
                                <div class="inner-content">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <img src="assets/images/team-3jhames.jpg">
                        </div>
                        <div class="down-content">
                            <h4>Jhames Andrew Lacuña</h4>
                            <span>Front-end Developer & Back-end Developer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 offset-2">
                    <div class="team-item">
                        <div class="thumb">
                            <div class="hover-effect">
                                <div class="inner-content">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <img src="assets/images/team-4kevin.jpg">
                        </div>
                        <div class="down-content">
                            <h4>Kevin Olegario</h4>
                            <span>Front-end Developer & Back-end Developer</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4">
                    <div class="team-item">
                        <div class="thumb">
                            <div class="hover-effect">
                                <div class="inner-content">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <img src="assets/images/team-5ace.jpg">
                        </div>
                        <div class="down-content">
                            <h4>Ace John Rotairo</h4>
                            <span>Front-end Developer & Back-end Developer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Our Team Area Ends ***** -->
    <!-- ***** Services Area Starts ***** -->
    <section class="our-services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>Our Services</h2>
                        <span>At VogueVault, we offer a range of services designed to enhance your style and elevate your wardrobe:</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-item">
                        <h4>Styling sessions</h4>
                        <p>Let our expert stylists help you discover your unique look. We'll guide you in selecting the perfect pieces that complement your style and personality.</p>
                        <img src="assets/images/service-1.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-item">
                        <h4>Wardrobe consultation</h4>
                        <p>Need a closet refresh? Our wardrobe consultation service helps you organize and update your wardrobe, ensuring you always have the perfect outfit for any occasion.</p>
                        <img src="assets/images/service-2.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-item">
                        <h4>Custom clothing design</h4>
                        <p>Looking for something truly unique? Our custom clothing design service allows you to work with our designers to create one-of-a-kind pieces tailored just for you.</p>
                        <img src="assets/images/service-3.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-4">
                    <div class="service-item">
                        <h4>Repair services for shoes or accessories</h4>
                        <p>Keep your favorite items in top shape with our repair services. From shoes to accessories, we'll restore your beloved pieces to their former glory.</p>
                        <img src="assets/images/service-04.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-item">
                        <h4>Fashion workshops or classes</h4>
                        <p>Expand your fashion knowledge with our workshops and classes. Whether you're a budding designer or a fashion enthusiast, there's always something new to learn.</p>
                        <img src="assets/images/service-05.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-item">
                        <h4>VIP shopping experiences</h4>
                        <p>Enjoy the ultimate shopping experience with our VIP services. Get personalized attention, exclusive access to new collections, and more.</p>
                        <img src="assets/images/service-06.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Services Area Ends ***** -->
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
                        <li><a href="home.php #men">Men’s Shopping</a></li>
                        <li><a href="home.php #women">Women’s Shopping</a></li>
                        <li><a href="home.php #kids">Kid's Shopping</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><a href="home.php #top">Homepage</a></li>
                        <li><a href="#">About Us</a></li>
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
                    loadProfileInfo();
                    loadCartItems();
                }
            });

            $("#editProfileBtn").click(function() {
                window.location.href = "edit_profile.php";
            });

            $(document).click(function(event) {
                if (!$(event.target).closest("#profileSlider, #profileToggle").length) {
                    $("#profileSlider").removeClass("active");
                }
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
    </script>
</body>
</html>