<?php
require_once '../includes/db_connect.php';
$conn = OpenCon(); 
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in first.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT user_name, user_bio, user_email, user_talent, user_image FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
