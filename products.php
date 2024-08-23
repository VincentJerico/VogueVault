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
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

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
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/get-product-style.css">
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
                            </li>
                            -->
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
                                    <li><a href="single-product.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="view-product-btn"><i class="fa fa-shopping-cart"></i></a></li>
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
                        <li><a href="#">Help</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
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
