<?php
require_once '../includes/db_connect.php';
$conn = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);

    $stmt = $conn->prepare("UPDATE SHOPPING_CART SET Cart_Status = 'removed' WHERE Cart_ID = ?");
    $stmt->bind_param("i", $cart_id);
    if ($stmt->execute()) {
        echo "<script>alert('Item removed from cart.'); window.location.href='view_cart.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
