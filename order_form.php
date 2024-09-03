<?php
session_start();
require_once 'includes/connection.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$buy_all = isset($_GET['buy_all']) && $_GET['buy_all'] === 'true';

// Fetch cart items if buying all
$cart_items = [];
if ($buy_all) {
    $stmt = $pdo->prepare("SELECT c.id as cart_id, p.id as product_id, p.name, p.price, c.quantity 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch single product details
    if (isset($_GET['product_id'])) {
        // Check if the product is already in the cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $_GET['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart_item) {
            // If product is in the cart, use the cart's quantity
            $quantity = $cart_item['quantity'];
        } else {
            // If not in the cart, use the quantity from the URL or default to 1
            $quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;
        }

        // Fetch the product details
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $_GET['product_id'], PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Add product details to cart items array
        $cart_items[] = [
            'product_id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity
        ];
    }
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If user has an address, use it as default
$default_address = $user['address'] ?? '';

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Fetch the categories of the products in the cart
$cart_categories = [];
foreach ($cart_items as $item) {
    $stmt = $pdo->prepare("SELECT category FROM products WHERE id = :product_id");
    $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC)['category'];
    if (!in_array($category, $cart_categories)) {
        $cart_categories[] = $category;
    }
}

// Fetch related products based on the categories
$related_products = [];
if (!empty($cart_categories)) {
    $placeholders = implode(',', array_fill(0, count($cart_categories), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category IN ($placeholders) AND id NOT IN (SELECT product_id FROM cart WHERE user_id = ?) LIMIT 5");
    $stmt->execute(array_merge($cart_categories, [$_SESSION['user_id']]));
    $related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form - VogueVault</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/logosquaretransparent.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/get-product-style.css">
    <link rel="stylesheet" href="assets/css/owl-carousel.css">
    <link rel="stylesheet" href="assets/css/lightbox.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-md-7 col-lg-6"> <!-- Form Column -->
                <div class="card shadow-lg p-4">
                    <h2 class="h4 mb-4 text-center">Order Form</h2> <!-- Smaller Header -->

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <form action="process_order.php" method="POST">
                        <input type="hidden" name="buy_all" value="<?php echo $buy_all ? 'true' : 'false'; ?>">

                        <div class="form-group mb-3">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address">Shipping Address:</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($default_address); ?></textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label for="payment_method">Payment Method:</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="cash_on_delivery">Cash on Delivery</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <h4 class="h5 mb-3">Order Information:</h4> <!-- Adjusted size for Order Information header -->
                            <ul class="list-group mb-3">
                                <?php foreach ($cart_items as $item): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <?php
                                            // Fetch the product image
                                            $stmt = $pdo->prepare("SELECT image FROM products WHERE id = :product_id");
                                            $stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
                                            $stmt->execute();
                                            $product_image = $stmt->fetch(PDO::FETCH_ASSOC)['image'];
                                            $imagePath = !empty($product_image) ? './uploads/' . basename($product_image) : 'assets/images/default-product-image.jpg';
                                            $imageUrl = file_exists($imagePath) ? $imagePath : 'assets/images/default-product-image.jpg';
                                            ?>
                                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="order-product-img mr-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <span><?php echo htmlspecialchars($item['name']); ?> - Quantity: <?php echo htmlspecialchars($item['quantity']); ?></span>
                                        </div>
                                        <span class="text-end">₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                                        <input type="hidden" name="items[]" value="<?php echo htmlspecialchars(json_encode($item)); ?>">
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="h5 text-end"><strong>Total: ₱<?php echo number_format($total_price, 2); ?></strong></p>
                        </div>

                        <div class="d-grid d-md-flex justify-content-between">
                            <button type="submit" class="btn btn-primary btn-sm">Place Order</button>
                            <a href="home.php" class="btn btn-secondary btn-sm">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-md-5 col-lg-6 mt-4 mt-md-0">
                <div class="card shadow-lg p-4">
                    <h4 class="h5 mb-3">You Might Also Like</h4>
                    <div class="row">
                    <?php if (!empty($related_products)): ?>
                        <?php foreach ($related_products as $related): ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="thumb">
                                        <?php
                                        $imagePath = !empty($related['image']) ? './uploads/' . basename($related['image']) : 'assets/images/default-product-image.jpg';
                                        $imageUrl = file_exists($imagePath) ? $imagePath : 'assets/images/default-product-image.jpg';
                                        ?>
                                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($related['name']); ?>">
                                    </div>
                                    <div class="card-body">
                                        <p class="card-title"><strong><?php echo htmlspecialchars($related['name']); ?></strong></p>
                                        <p class="card-text">₱<?php echo number_format($related['price'], 2); ?></p>
                                        <button class="btn btn-primary btn-sm view-product" data-product-id="<?php echo $related['id']; ?>">View</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No related products found.</p>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.view-product').on('click', function() {
            var productId = $(this).data('product-id');
            $.ajax({
                url: 'get-product-details.php',
                method: 'GET',
                data: { id: productId },
                success: function(response) {
                    $('body').append(response);
                    $('.modal-overlay').fadeIn();
                },
                error: function() {
                    alert('Error loading product details.');
                }
            });
        });

        $(document).on('click', '.modal-overlay, .close-modal', function() {
            $('.modal-overlay').fadeOut(function() {
                $(this).remove();
            });
        });

        $(document).on('click', '.product-modal', function(e) {
            e.stopPropagation();
        });
    });
    </script>
</body>
</html>
