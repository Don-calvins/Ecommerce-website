<?php
include 'db_config.php'; // Database connection

// Fetch products from the database
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Our Products</h1>
        <input type="text" id="searchBar" placeholder="Search products...">
        <div class="product-grid">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="product-card">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="price">$<?php echo number_format($row['price'], 2); ?></p>
                    <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        document.getElementById("searchBar").addEventListener("keyup", function() {
            let filter = this.value.toLowerCase();
            let products = document.querySelectorAll(".product-card");
            products.forEach(product => {
                let name = product.querySelector("h3").textContent.toLowerCase();
                product.style.display = name.includes(filter) ? "block" : "none";
            });
        });
    </script>
</body>
</html>
