<?php
require_once 'includes/connection.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    try {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Inside the PHP block after $product is fetched
        if ($product) {
            $price = $product['price'];
            $stock = $product['stock'];
        } else {
            $price = 0;
            $stock = 0;
        }

        if ($product) {
            $imagePath = !empty($product['image']) ? 'uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
            $imageUrl = file_exists($imagePath) ? $imagePath : 'assets/images/default-product-image.jpg';

            // Return HTML for the product modal
            echo '<div class="modal-overlay">';
            echo '<div class="product-modal">';
            echo '<div class="product-image">';
            if (file_exists($imagePath)) {
                echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($product['name']) . '">';
            } else {
                echo '<p>Image not found: ' . htmlspecialchars($imagePath) . '</p>';
            }
            echo '</div>';
            echo '<div class="product-info">';
            echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';
            echo '<p class="description">' . htmlspecialchars($product['description']) . '</p>';
            echo '<p class="price">Price: ₱' . number_format($product['price'], 2) . '</p>';
            echo '<div class="actions">';
            echo '<button id="addToCartBtn" data-product-id="' . $product['id'] . '">Add to Cart</button>';
            echo '<button id="buyNowBtn" data-product-id="' . $product['id'] . '">Buy Now</button>';
            echo '</div>';            
            echo '</div>';
            echo '<button class="close-modal">&times;</button>';
            echo '</div>';
            echo '</div>';

            // Add CSS styles
            echo '<style>
                .modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.7);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                }
                .product-modal {
                    background-color: #fff;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0,0,0,0.2);
                    max-width: 90%;
                    width: 800px;
                    max-height: 90vh;
                    overflow-y: auto;
                    display: flex;
                    flex-direction: column;
                    position: relative;
                }
                .product-image {
                    width: 70%;
                    max-height: 700px;
                    overflow: hidden;
                }
                .product-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                }
                .product-info {
                    padding: 20px;
                }
                h2 {
                    color: #153448;
                    margin-top: 0;
                    font-size: 24px;
                }
                .description {
                    color: #666;
                    margin-bottom: 15px;
                    font-size: 14px;
                }
                .price {
                    font-size: 18px;
                    font-weight: bold;
                    color: #153448;
                    margin-bottom: 20px;
                }
                .actions {
                    display: flex;
                    gap: 10px;
                }
                .actions button {
                    flex: 1;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-size: 14px;
                    font-weight: bold;
                }
                #addToCartBtn {
                    background-color: #153448;
                    color: #fff;
                }
                #buyNowBtn {
                    background-color: #DFD0B8;
                    color: #153448;
                }
                #addToCartBtn:hover, #buyNowBtn:hover {
                    opacity: 0.9;
                    transform: translateY(-2px);
                }
                .close-modal {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: none;
                    border: none;
                    font-size: 28px;
                    cursor: pointer;
                    color: #153448;
                    text-shadow: 0 0 3px rgba(0,0,0,0.5);
                    z-index: 10;
                }
                    ,close-modal:hover {
                    opacity: 0.9;
                    }
                @media (max-width: 600px) {
                    .product-modal {
                        width: 95%;
                        max-height: 95vh;
                    }
                    .product-image {
                        max-height: 300px;
                    }
                    .product-info {
                        padding: 15px;
                    }
                    h2 {
                        font-size: 20px;
                    }
                    .description, .price {
                        font-size: 14px;
                    }
                    .actions {
                        flex-direction: column;
                    }
                    .actions button {
                        width: 100%;
                    }
                }
            </style>';
        } else {
            echo 'Product not found';
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request';
}
?>

<script>
    $(document).ready(function() {
        let price = <?php echo json_encode($price); ?>;
        let stock = <?php echo json_encode($stock); ?>;
        $(".qty").on('input', function() {
            let quantity = $(this).val();
            let total = price * quantity;
            $("#totalPrice").text(total.toFixed(2));
        });

        $(".plus").click(function() {
            let $input = $(this).prev('input.qty');
            let val = parseInt($input.val());
            if (val < stock) {
                $input.val(val + 1).trigger('input');
            }
        });

        $(".minus").click(function() {
            let $input = $(this).next('input.qty');
            let val = parseInt($input.val());
            if (val > 1) {
                $input.val(val - 1).trigger('input');
            }
        });

        $("#addToCartBtn").click(function(e) {
            e.preventDefault();
            let quantity = $(".qty").val();
            $.ajax({
                url: 'add-to-cart.php',
                type: 'POST',
                data: {
                    product_id: <?php echo json_encode($product['id']); ?>,
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

        $("#buyNowBtn").click(function(e) {
            e.preventDefault();
            let quantity = $(".qty").val();
            $.ajax({
                url: 'place-order.php',
                type: 'POST',
                data: {
                    product_id: <?php echo json_encode($product['id']); ?>,
                    quantity: quantity
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        alert(data.message);
                        window.location.href = 'order-confirmation.php';
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
