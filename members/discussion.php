<?php
session_start();
include_once ('../includes/db_connect.php');
$conn = OpenCon();
include '../includes/header_user.php';

$status = $_SESSION['forum_status'] ?? '';
$statusMsg = $_SESSION['forum_message'] ?? '';

unset($_SESSION['forum_status']);
unset($_SESSION['forum_message']);

function getPostsWithLikes($conn, $user_id) {
    $sql = "SELECT 
                p.post_id,
                p.user_id,
                p.title,
                p.content,
                p.posted_at,
                COUNT(l.like_id) as like_count,
                MAX(CASE WHEN l.user_id = ? THEN 1 ELSE 0 END) as user_liked
            FROM forum_posts p
            LEFT JOIN likes l ON p.post_id = l.post_id
            GROUP BY p.post_id, p.user_id, p.title, p.content, p.posted_at
            ORDER BY p.posted_at DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getUsername($conn, $user_id) {
    $sql = "SELECT User_Name FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['User_Name'] ?? 'Unknown User';
}

if (isset($_POST['like_action']) && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $current_user_id = $_SESSION['user_id'] ?? 0;
    
    if ($current_user_id > 0) {
        if ($_POST['like_action'] === 'like') {
            // Check if already liked
            $check_sql = "SELECT like_id FROM likes WHERE user_id = ? AND post_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ii", $current_user_id, $post_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows == 0) {
                $insert_sql = "INSERT INTO likes (user_id, post_id, liked_at) VALUES (?, ?, current_timestamp())";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ii", $current_user_id, $post_id);
                $insert_stmt->execute();
            }
        } elseif ($_POST['like_action'] === 'unlike') {
            $delete_sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("ii", $current_user_id, $post_id);
            $delete_stmt->execute();
        }
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$current_user_id = $_SESSION['user_id'] ?? 0;
$posts = getPostsWithLikes($conn, $current_user_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/discussion.css">
    <title>Disucssion</title>
</head>

<body>
<div class="discussion-container">
    <h1>Discussion</h1>
    <div class="status-msg">
        <?php if(!empty($statusMsg)){ ?>
            <p class="status <?= $status; ?>">
                <?= $statusMsg; ?>
            </p>
        <?php } ?>
    </div>
    <div class="post-new-forum">
        <form id="postForm" action="save_discussion.php" method="POST" enctype="multipart/form-data">
            <label for="title">Post New Forum</label>
            <input type="text" id="post_title" name="post_title" placeholder="Title here..." required />
            <textarea id="post_details" name="post_details" placeholder="Content here..." required></textarea>
            <div class="form-buttons">
                <button type="reset">Reset</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <div class="discussion">
        <?php if ($posts->num_rows > 0): ?>
            <?php while($post = $posts->fetch_assoc()): ?>
                <div class="post-item">
                    <div class="post-header">
                        <h3 class="post-title"><?= htmlspecialchars($post['title']); ?></h3>
                        <div class="post-meta">
                            By: <?= htmlspecialchars(getUsername($conn, $post['user_id'])); ?> | 
                            <?= date('M j, Y g:i A', strtotime($post['posted_at'])); ?>
                        </div>
                    </div>
                    
                    <div class="post-content">
                        <?= nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                    
                    <div class="post-actions">
                        <form class="like-form" method="POST" action="">
                            <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
                            <input type="hidden" name="like_action" value="<?= $post['user_liked'] ? 'unlike' : 'like'; ?>">
                            <button type="submit" class="like-button <?= $post['user_liked'] ? 'liked' : ''; ?>">
                                <span class="like-icon"><?= $post['user_liked'] ? '❤️' : '🤍'; ?></span>
                                <span class="like-count"><?= $post['like_count']; ?></span>
                                <span>Like<?= $post['like_count'] != 1 ? 's' : ''; ?></span>
                            </button>
                        </form>
                    </div>

                    <div class="comment-section">
                        <form class="comment-form" method="POST" action="save_comment.php">
                            <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
                            <textarea class="comment-input" name="comment_text" placeholder="Write a comment..." required></textarea>
                            <button type="submit" class="comment-submit">Comment</button>
                        </form>

                        <?php
                        $comment_sql = "SELECT c.comment_id, c.user_id, c.comment_text, c.commented_at 
                                       FROM comments c 
                                       WHERE c.post_id = ? 
                                       ORDER BY c.commented_at ASC";
                        $comment_stmt = $conn->prepare($comment_sql);
                        $comment_stmt->bind_param("i", $post['post_id']);
                        $comment_stmt->execute();
                        $comments = $comment_stmt->get_result();
                        
                        if ($comments->num_rows > 0):
                        ?>
                            <?php while($comment = $comments->fetch_assoc()): ?>
                                <div class="comment-item">
                                    <div>
                                        <span class="comment-author"><?= htmlspecialchars(getUsername($conn, $comment['user_id'])); ?></span>
                                        <span class="comment-date"><?= date('M j, Y g:i A', strtotime($comment['commented_at'])); ?></span>
                                    </div>
                                    <div class="comment-text"><?= nl2br(htmlspecialchars($comment['comment_text'])); ?></div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-posts">
                <p>No posts yet. Be the first to start a discussion!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include '../includes/footer.php';
?>