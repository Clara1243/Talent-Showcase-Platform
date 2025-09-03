<?php
require_once '../includes/db_connect.php';
$conn = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $user_id = 1;

    $check = $conn->prepare("SELECT * FROM SHOPPING_CART WHERE Product_ID = ? AND UserID = ? AND Cart_Status = 'active'");
    $check->bind_param("ii", $product_id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Product is already in your cart.'); window.location.href='view_catalog.php';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO SHOPPING_CART (UserID, Product_ID, Added_Date, Cart_Status)
                                VALUES (?, ?, NOW(), 'active')");
        $stmt->bind_param("ii", $user_id, $product_id);

        if ($stmt->execute()) {
            echo "<script>alert('Product added to cart successfully.'); window.location.href='view_catalog.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
