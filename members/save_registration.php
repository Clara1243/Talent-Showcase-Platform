<?php
session_start();
require_once '../includes/db_connect.php';
$conn = OpenCon(); 

$email = $_POST['email'];

$check_sql = "SELECT user_id FROM user WHERE user_email = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo "<script>alert('This email is already registered. Please use another email.'); window.history.back();</script>";
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

$name = $_POST['name'];
$bio = !empty(trim($_POST['bio'])) ? $_POST['bio'] : 'Hi, nice to meet you!';
$password = $_POST['password'];
$talents = isset($_POST['talent']) ? implode(', ', $_POST['talent']) : '';
$document_name = null;
$user_status = 'active';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO user (user_name, user_email, user_password, user_bio, user_talent, user_status)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $email, $hashed_password , $bio, $talents, $user_status);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id; 
    $stmt->close();

    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
        $upload_dir = "../uploads/user_profile/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_tmp = $_FILES['profile']['tmp_name'];
        $file_ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
        $new_filename = $user_id . "." . strtolower($file_ext);
        $target_path = $upload_dir . $new_filename;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $update_sql = "UPDATE user SET user_image = ? WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $new_filename, $user_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    }

    echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
} else {
    echo "Database error: " . $stmt->error;
}

$conn->close();
?>
