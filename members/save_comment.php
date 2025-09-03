<?php
session_start();
include '../includes/db_connect.php';
$conn = OpenCon();

if (!isset($_POST['post_id']) || !isset($_POST['comment_text'])) {
    $_SESSION['forum_status'] = 'error';
    $_SESSION['forum_message'] = 'Missing required data.';
    header("Location: discussion.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);
$comment_text = trim($_POST['comment_text']);

if (empty($comment_text)) {
    $_SESSION['forum_status'] = 'error';
    $_SESSION['forum_message'] = 'Comment cannot be empty.';
    header("Location: discussion.php");
    exit;
}

$check_post_sql = "SELECT post_id FROM forum_posts WHERE post_id = ?";
$check_stmt = $conn->prepare($check_post_sql);
$check_stmt->bind_param("i", $post_id);
$check_stmt->execute();
$post_result = $check_stmt->get_result();

if ($post_result->num_rows == 0) {
    $_SESSION['forum_status'] = 'error';
    $_SESSION['forum_message'] = 'Post not found.';
    header("Location: discussion.php");
    exit;
}

try {
    $insert_comment_sql = "INSERT INTO comments (post_id, user_id, comment_text, commented_at) VALUES (?, ?, ?, current_timestamp())";
    $insert_stmt = $conn->prepare($insert_comment_sql);
    $insert_stmt->bind_param("iis", $post_id, $user_id, $comment_text);
    
    if ($insert_stmt->execute()) {
        $_SESSION['forum_status'] = 'success';
        $_SESSION['forum_message'] = 'Comment added successfully!';
    } else {
        $_SESSION['forum_status'] = 'error';
        $_SESSION['forum_message'] = 'Failed to add comment.';
    }
    
} catch (Exception $e) {
    $_SESSION['forum_status'] = 'error';
    $_SESSION['forum_message'] = 'Database error occurred.';
}

$conn->close();

header("Location: discussion.php");
exit;
?>