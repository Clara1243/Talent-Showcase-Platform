<?php
session_start();
include '../includes/header_user.php';
require_once '../includes/db_connect.php';
$conn = OpenCon();

$user_id = 1;

$sql = "SELECT sc.Cart_ID, p.Title, p.Category, p.Product_Image, p.Description 
        FROM SHOPPING_CART sc
        JOIN PRODUCT p ON sc.Product_ID = p.Product_ID
        WHERE sc.UserID = ? AND sc.Cart_Status = 'active'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link rel="stylesheet" href="../css/dashboard-admin.css">
    <link rel="stylesheet" href="../css/view_cart.css">
</head>
<body>
    <div class="cart-container">
        <h2>Your Shopping Cart</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="cart-item">
                   <img src="../<?= $row['Product_Image'] ?>" alt="Product" style="width:100px; height:auto;">
                    <div class="cart-info">
                        <h4><?= htmlspecialchars($row['Title']) ?></h4>
                        <div class="category"><?= htmlspecialchars($row['Category']) ?></div>
                        <div class="description"><?= htmlspecialchars($row['Description']) ?></div>
                        <div class="cart-actions">
                            <form method="POST" action="remove_cart_item.php">
                                <input type="hidden" name="cart_id" value="<?= $row['Cart_ID'] ?>">
                                <button type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <form method="POST" action="checkout_cart_item.php" style="text-align: center;">
                <button type="submit">Checkout All</button>
            </form>
        <?php else: ?>
            <div class="empty-cart">Your cart is empty.</div>
        <?php endif; ?>
    </div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
