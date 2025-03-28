<?php
include 'db_config.php'; // Database connection

// Check if product ID is provided
if (!isset($_GET['id'])) {
    die("Product not found");
}
$product_id = intval($_GET['id']);

// Fetch product details
$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);
if (!$product) {
    die("Product not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="product-details">
            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
            <div class="details">
                <h1><?php echo $product['name']; ?></h1>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo $product['description']; ?></p>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
