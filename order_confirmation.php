<?php
include 'db_config.php'; // Database connection
session_start();

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    die("Order not found");
}
$order_id = intval($_GET['order_id']);

// Fetch order details
$order_query = "SELECT * FROM orders WHERE id = $order_id";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);
if (!$order) {
    die("Order not found");
}

// Fetch order items
$order_items_query = "SELECT products.name, order_items.quantity, order_items.price 
                      FROM order_items 
                      JOIN products ON order_items.product_id = products.id 
                      WHERE order_items.order_id = $order_id";
$order_items_result = mysqli_query($conn, $order_items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        <p>Thank you for your purchase! Your order has been placed successfully.</p>
        <h2>Order Details</h2>
        <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
        <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
        <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>
        <h2>Items Ordered</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            <?php while ($item = mysqli_fetch_assoc($order_items_result)) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php } ?>
        </table>
        <button onclick="window.print()" class="btn">Print Receipt</button>
        <a href="index.php" class="btn">Return to Home</a>
    </div>
</body>
</html>
