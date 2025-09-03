<?php
session_start();
require_once '../includes/db_connect.php';
$conn = OpenCon();

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT user_id, user_email, user_password, user_status FROM user WHERE user_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if ($user['user_status'] === 'active') {
        if (password_verify($password, $user['user_password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['user_email'];
            $_SESSION['loggedin'] = true;

            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href = 'login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Login failed: this user account has been deactivated.'); window.location.href = 'login.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('No account found with that email.'); window.location.href = 'login.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
