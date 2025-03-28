<?php
include 'db_config.php'; // Database connection
session_start();

$user_id = $_SESSION['user_id']; // Ensure user is logged in
if (!$user_id) {
    die("Please log in to place an order");
}

// Fetch cart items
$query = "SELECT product_id, quantity FROM cart WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Your cart is empty");
}

// Insert order
$order_query = "INSERT INTO orders (user_id, total_price, status, created_at) VALUES ($user_id, 0, 'Pending', NOW())";
mysqli_query($conn, $order_query);
$order_id = mysqli_insert_id($conn);

total_price = 0;

// Insert order items and calculate total price
while ($row = mysqli_fetch_assoc($result)) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    
    $product_query = "SELECT price FROM products WHERE id = $product_id";
    $product_result = mysqli_query($conn, $product_query);
    $product = mysqli_fetch_assoc($product_result);
    $price = $product['price'];
    $subtotal = $price * $quantity;
    $total_price += $subtotal;
    
    $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)";
    mysqli_query($conn, $order_item_query);
}

// Update order total price
mysqli_query($conn, "UPDATE orders SET total_price = $total_price WHERE id = $order_id");

// Clear cart after order placement
mysqli_query($conn, "DELETE FROM cart WHERE user_id = $user_id");

// Redirect to confirmation page
echo "<script>alert('Order placed successfully!'); window.location.href='order_confirmation.php?order_id=$order_id';</script>";
?>
