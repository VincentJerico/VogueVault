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
    <title>VogueVault - Contact Page</title>
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
                        <h2>Contact Us</h2>
                        <span>We're here to help with any questions or concerns</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->
    <!-- ***** Contact Area Starts ***** -->
    <div class="contact-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div id="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3866.4835979496365!2d120.87347677591548!3d14.283311284764414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd80655363e331%3A0xa4247d4e2b9cbce8!2sCavite%20State%20University%20-%20Trece%20Martires%20Campus!5e0!3m2!1sen!2sph!4v1722907139690!5m2!1sen!2sph" width="100%" height="400px" frameborder="0" style="border:0" allowfullscreen></iframe>
                        <!-- You can simply copy and paste "Embed a map" code from Google Maps for any location. -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Say Hello. Don't Be Shy!</h2>
                        <span>We're all ears and ready to chat about your style!</span>
                    </div>
                    <form id="contactForm" action="" method="post">
                        <div class="row">
                            <div class="col-lg-6">
                            <fieldset>
                                <input name="name" type="text" id="name" placeholder="Your name" required="">
                            </fieldset>
                            </div>
                            <div class="col-lg-6">
                            <fieldset>
                                <input name="email" type="text" id="email" placeholder="Your email" required="">
                            </fieldset>
                            </div>
                            <div class="col-lg-12">
                            <fieldset>
                                <textarea name="message" rows="6" id="message" placeholder="Your message" required=""></textarea>
                            </fieldset>
                            </div>
                            <div class="col-lg-12">
                            <fieldset>
                                <button type="submit" id="form-submit" class="main-dark-button"><i class="fa fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Contact Area Ends ***** -->
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
                                <li>Work Hours:<br><span>07:30 AM - 9:30 PM</span></li>
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
                        <li><a href="#">Contact Us</a></li>
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
        $('#contactForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                type: 'POST',
                url: 'process_contact.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#contactForm')[0].reset();
                    } else {
                        alert('Error: ' + response.message);
                        console.error('Form submission error:', response.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    console.log('Response:', jqXHR.responseText);
                    alert('An error occurred. Please check the console for more information.');
                }
            });
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
