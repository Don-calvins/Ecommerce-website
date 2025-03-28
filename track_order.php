<?php
include 'db_config.php'; // Database connection
session_start();

// Check if order ID is provided
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
$order = null;
$order_items = [];
$order_status = ['Pending', 'Processing', 'Shipped', 'Delivered']; // Order progress stages
$status_index = 0;

if ($order_id) {
    // Fetch order details
    $order_query = "SELECT * FROM orders WHERE id = $order_id";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);
    
    if ($order) {
        $status_index = array_search($order['status'], $order_status);
        // Fetch order items
        $order_items_query = "SELECT products.name, order_items.quantity, order_items.price 
                              FROM order_items 
                              JOIN products ON order_items.product_id = products.id 
                              WHERE order_items.order_id = $order_id";
        $order_items_result = mysqli_query($conn, $order_items_query);
        while ($row = mysqli_fetch_assoc($order_items_result)) {
            $order_items[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Track Your Order</h1>
        <form method="GET">
            <input type="text" name="order_id" placeholder="Enter Order ID" required>
            <button type="submit" class="btn">Track</button>
        </form>

        <?php if ($order): ?>
            <h2>Order Details</h2>
            <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
            <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
            <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>

            <h2>Order Progress</h2>
            <div class="progress-bar">
                <?php foreach ($order_status as $index => $status): ?>
                    <span class="step <?php echo ($index <= $status_index) ? 'completed' : ''; ?>"> <?php echo $status; ?> </span>
                <?php endforeach; ?>
            </div>

            <h2>Items Ordered</h2>
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($order_id): ?>
            <p class="error">Order not found. Please check your Order ID.</p>
        <?php endif; ?>
    </div>
</body>
</html>
