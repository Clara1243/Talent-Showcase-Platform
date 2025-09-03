<?php
session_start();
require_once '../includes/db_connect.php';
$conn = OpenCon(); // Open database connection


$id = $_POST['id'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT admin_id, admin_password FROM admin WHERE admin_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();

    if ($password === $admin['admin_password']) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['adminloggedin']=true;

        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid password.'); window.location.href = 'login_admin.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No account found with that admin ID.'); window.location.href = '../members/login.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>
