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

        if ($product) {
            $price = $product['price'];
            $stock = $product['stock'];
            $imagePath = !empty($product['image']) ? 'uploads/' . basename($product['image']) : 'assets/images/default-product-image.jpg';
            $imageUrl = file_exists($imagePath) ? $imagePath : 'assets/images/default-product-image.jpg';

            // HTML for the product modal
            echo '<div class="modal-overlay">';
            echo '<div class="product-modal">';
            echo '<div class="product-image">';
            echo '<img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($product['name']) . '">';
            echo '</div>';
            echo '<div class="product-info">';
            echo '<h2>' . htmlspecialchars($product['name']) . '</h2>';
            echo '<p class="description">' . htmlspecialchars($product['description']) . '</p>';
            echo '<p class="price">Price: ₱<span id="unitPrice">' . number_format($product['price'], 2, '.', ',') . '</span></p>';
            echo '<div class="quantity-selector">';
            echo '<button class="minus">-</button>';
            echo '<input type="number" class="qty" value="1" min="1" max="' . $stock . '">';
            echo '<button class="plus">+</button>';
            echo '</div>';
            echo '<p class="total-price">Total: ₱<span id="totalPrice">' . number_format($product['price'], 2) . '</span></p>';
            echo '<div class="actions">';
            echo '<button id="addToCartBtn" data-product-id="' . $product['id'] . '">Add to Cart</button>';
            echo '<button id="buyNowBtn" data-product-id="' . $product['id'] . '">Buy Now</button>';
            echo '</div>';            
            echo '</div>';
            echo '<button class="close-modal">&times;</button>';
            echo '</div>';
            echo '</div>';

            // CSS styles
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
                    width: 650px;
                    max-height: 80vh;
                    overflow-y: auto;
                    display: flex;
                    flex-direction: column;
                    position: relative;
                }
                .product-image {
                    width: 100%;
                    max-height: 400px;
                    overflow: hidden;
                }
                .product-image img {
                    display: block;
                    margin: 0 auto;
                    width: 70%;
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
                .price, .total-price {
                    font-size: 18px;
                    font-weight: bold;
                    color: #153448;
                    margin-bottom: 20px;
                }
                .quantity-selector {
                    display: flex;
                    align-items: center;
                    margin-bottom: 20px;
                }
                .quantity-selector button {
                    background-color: #153448;
                    color: #fff;
                    border: none;
                    padding: 5px 10px;
                    font-size: 16px;
                    cursor: pointer;
                }
                .quantity-selector input {
                    width: 50px;
                    text-align: center;
                    margin: 0 10px;
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
                .close-modal:hover {
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
                    .description, .price, .total-price {
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

            // JavaScript for functionality
            echo '<script>
                $(document).ready(function() {
                    let price = parseFloat($("#unitPrice").text().replace(/,/g, ""));
                    let stock = parseInt($(".qty").attr("max"));
            
                    function updateTotal() {
                        let quantity = parseInt($(".qty").val());
                        let total = price * quantity;
                        $("#totalPrice").text(total.toLocaleString("en-PH", { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    }
            
                    $(".qty").on("input", updateTotal);
            
                    $(".plus").click(function() {
                        let $input = $(".qty");
                        let val = parseInt($input.val());
                        if (val < stock) {
                            $input.val(val + 1).trigger("input");
                        }
                    });
            
                    $(".minus").click(function() {
                        let $input = $(".qty");
                        let val = parseInt($input.val());
                        if (val > 1) {
                            $input.val(val - 1).trigger("input");
                        }
                    });
            
                    // Prevent modal from closing when clicking inside it
                    $(".product-modal").click(function(e) {
                        e.stopPropagation();
                    });
            
                    // Close modal when clicking outside or on close button
                    $(".modal-overlay, .close-modal").click(function() {
                        $(".modal-overlay").remove();
                    });
            
                    $("#addToCartBtn").click(function(e) {
                        e.preventDefault();
                        let quantity = $(".qty").val();
                        $.ajax({
                            url: "add-to-cart.php",
                            type: "POST",
                            data: {
                                product_id: ' . json_encode($product['id']) . ',
                                quantity: quantity
                            },
                            success: function(response) {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    alert(data.message);
                                } else {
                                    alert("Failed to add product: " + data.message);
                                }
                            },
                            error: function() {
                                alert("Error adding product to cart.");
                            }
                        });
                    });
            
                    $("#buyNowBtn").click(function(e) {
                        e.preventDefault();
                        let productId = $(this).data("product-id");
                        let quantity = $(".qty").val();
                        window.location.href = "order_form.php?product_id=" + productId + "&quantity=" + quantity;
                    });
                });
            </script>';
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