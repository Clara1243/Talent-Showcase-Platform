<?php

require_once '../includes/db_connect.php';
$conn = OpenCon();

$admin_id = $_SESSION['admin_id'];

$sql = "SELECT admin_id, admin_name, admin_email, admin_image FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Admin not found.");
}

$admin = $result->fetch_assoc();

$stmt->close();
$conn->close();

?>