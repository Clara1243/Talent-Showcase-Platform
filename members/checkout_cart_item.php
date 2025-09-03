<?php
require_once '../includes/db_connect.php';
$conn = OpenCon();

$user_id = 1;

$stmt = $conn->prepare("UPDATE SHOPPING_CART SET Cart_Status = 'checked_out' WHERE UserID = ? AND Cart_Status = 'active'");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Checkout successful. Thank you!'); window.location.href='view_cart.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}
?>
