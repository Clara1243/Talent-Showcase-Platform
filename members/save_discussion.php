<?php
session_start();
require_once '../includes/db_connect.php';
$conn = OpenCon();

$user_id = $_SESSION['user_id'];
$post_title = $_POST['post_title'] ?? '';
$post_details = $_POST['post_details'] ?? '';
$status = $statusMsg = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql= "INSERT INTO forum_posts (user_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $user_id, $post_title, $post_details);
    if($stmt->execute()){
        $status = 'success';
        $statusMsg = 'Forum posted successfully!';
    } else {
        $statusMsg = 'Forum post failed: ' . $stmt->error;
    }
    $stmt->close();

    $_SESSION['forum_status'] = $status;
    $_SESSION['forum_message'] = $statusMsg;
    header("Location: discussion.php");
    exit();
}

$conn->close();
?>