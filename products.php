<?php
session_start();
require_once 'includes/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 9;
$start = ($page - 1) * $perPage;

// Search and category filtering
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Map search terms to categories
$category_mapping = [
    'men' => "men's",
    'mens' => "men's",
    "men\'s" => "men's",
    'women' => "women's",
    'womens' => "women's",
    "women\'s" => "women's",
];

// Check if the search term matches a mapped category
if (array_key_exists($search, $category_mapping)) {
    $category = $category_mapping[$search];
}

// Fetch categories for filtering
$categories_query = "SELECT DISTINCT category FROM products";
$categories_result = $pdo->query($categories_query);
$categories = $categories_result->fetchAll(PDO::FETCH_ASSOC);

// Modify the base query to include search and category filtering
$query = "SELECT * FROM products WHERE name LIKE :search";
$params = [':search' => "%$search%"];

if ($category) {
    $query .= " AND category = :category";
    $params[':category'] = $category;
}

// Append the LIMIT clause directly
$query .= " ORDER BY created_at DESC LIMIT $start, $perPage";

// Prepare and execute statement
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total products for pagination
$total_query = "SELECT COUNT(*) as count FROM products WHERE name LIKE :search";
$total_params = [':search' => "%$search%"];

if ($category) {
    $total_query .= " AND category = :category";
    $total_params[':category'] = $category;
}

$total_stmt = $pdo->prepare($total_query);
$total_stmt->execute($total_params);
$total_products = $total_stmt->fetch(PDO::FETCH_ASSOC)['count'];
$total_pages = ceil($total_products / $perPage);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/get-product-style.css">
    <link rel="stylesheet" href="assets/css/owl-carousel.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
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
            
            <!-- Add search and category filter form -->
            <form action="" method="GET" class="search-form">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search products" value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category == $cat['category'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <div class="col-lg-4">
                    <div class="item">
                        <div class="thumb">
                            <div class="hover-content">
                                <ul>
                                    <li><a href="#" class="view-product-btn" data-product-id="<?php echo htmlspecialchars($product['id']); ?>"><i class="fa fa-eye"></i></a></li>
                                </ul>
                            </div>
                            <?php
                            // Ensure the correct relative path to the uploads directory
                            $imagePath = !empty($product['image']) ? './uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
                            $imageUrl = file_exists($imagePath) ? $imagePath : 'assets/images/default-product-image.jpg';
                            ?>
                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img">
                        </div>
                        <div class="down-content">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <span><?php echo '₱' . number_format($product['price'], 2); ?></span>
                            <div class="stars">
                                <?php
                                $rating = isset($product['rating']) ? floatval($product['rating']) : 0;
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $rating):
                                        echo '<i class="fa fa-star"></i>';
                                    elseif ($i - 0.5 <= $rating):
                                        echo '<i class="fa fa-star-half-o"></i>';
                                    else:
                                        echo '<i class="fa fa-star-o"></i>';
                                    endif;
                                endfor;
                                ?>
                                <span class="rating-value">(<?php echo number_format($rating, 1); ?>)</span>
                            </div>
                            <!-- Rating Form -->
                            <div class="rate-product">
                                <span>Rate this product: </span>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa fa-star-o rate-star" data-rate="<?php echo $i; ?>" data-product-id="<?php echo $product['id']; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="col-lg-12">
                <p>No products found matching your criteria.</p>
            </div>
            <?php endif; ?>
                <div class="col-lg-12">
                    <div class="pagination">
                        <ul>
                            <?php if ($page > 1): ?>
                                <li><a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Prev</a></li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="<?php echo $i == $page ? 'active' : ''; ?>">
                                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($page < $total_pages): ?>
                                <li><a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">Next</a></li>
                            <?php endif; ?>
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

    <script>
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

</body>
</html>
