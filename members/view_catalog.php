<?php
session_start();
include '../includes/header_user.php';
require_once '../includes/db_connect.php';
$conn = OpenCon();

$sql = "SELECT * FROM PRODUCT WHERE Status = 'active' ORDER BY Upload_Date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Catalog</title>
    <link rel="stylesheet" href="../css/dashboard-admin.css">
    <link rel="stylesheet" href="../css/view_catalog.css">
</head>
<body>
    <div class="catalog-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="catalog-card">
                <img src="../<?= $row['Product_Image'] ?>" alt="Product Image" style="width:100%; max-height:200px;">
                <div class="card-body">
                    <h4><?= htmlspecialchars($row['Title']) ?></h4>
                    <div class="category"><?= htmlspecialchars($row['Category']) ?></div>
                    <div class="description"><?= htmlspecialchars($row['Description']) ?></div>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $row['Product_ID'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
