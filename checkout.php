<?php
include 'db_config.php'; // Database connection
session_start();

$user_id = $_SESSION['user_id']; // Ensure user is logged in
if (!$user_id) {
    die("Please log in to proceed to checkout");
}

// Fetch cart items
$query = "SELECT cart.id, products.name, products.price, products.image_url, cart.quantity 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_id = $user_id";
$result = mysqli_query($conn, $query);

// Calculate total price
$total_price = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total_price += $row['price'] * $row['quantity'];
}
mysqli_data_seek($result, 0); // Reset result pointer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Summary</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Order Summary</h1>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><img src="<?php echo $row['image_url']; ?>" width="50"> <?php echo $row['name']; ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>$<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                </tr>
            <?php } ?>
        </table>
        <h2>Total Price: <strong>$<?php echo number_format($total_price, 2); ?></strong></h2>
        <form action="place_order.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <button type="submit" class="btn">Place Order</button>
        </form>
    </div>
</body>
</html>
