<?php
include '../includes/db_connect.php';
$conn = OpenCon();

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $selected_ids = isset($_POST['selected_posts']) ? $_POST['selected_posts'] : [];

        if (!empty($selected_ids)) {
            if (!empty($selected_ids)){
            $selected_ids = array_map('intval', $selected_ids);
            $placeholders = str_repeat('?,', count($selected_ids) - 1) . '?';
            }

            if ($action === 'delete') {
                $sql = "DELETE FROM forum_posts WHERE post_id IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_ids));
                $stmt->bind_param($types, ...$selected_ids);
                if ($stmt->execute()) {
                    $success_message = "Posts deleted successfully.";
                } else {
                    $error_message = "Error deleting posts.";
                }
                $stmt->close();
            } else {
            $error_message = "Something went error.";
            }
        }
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$search_condition = '';
$search_params = [];
$search_types = '';
if (!empty($search)) {
    if (is_numeric($search)) {
        $search_condition = "WHERE post_id = ? OR title LIKE ?";
        $search_term = "%$search%";
        $search_params = [$search, $search_term];
        $search_types = 'is';
    } else {
        $search_condition = "WHERE title LIKE ?";
        $search_term = "%$search%";
        $search_params = [$search_term];
        $search_types = 's';
    }
}

$count_sql = "SELECT COUNT(*) as total FROM forum_posts $search_condition";
if (!empty($search_params)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($search_types, ...$search_params);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_posts = $count_result->fetch_assoc()['total'];
    $count_stmt->close();
} else {
    $count_result = mysqli_query($conn, $count_sql);
    $total_posts = mysqli_fetch_assoc($count_result)['total'];
}

$total_pages = ceil($total_posts / $per_page);

$posts_sql = "SELECT p.post_id, p.title, p.content, p.posted_at, p.user_id, u.User_Name FROM forum_posts p LEFT JOIN user u ON p.user_id = u.User_ID $search_condition ORDER BY post_id DESC LIMIT ? OFFSET ?";
if (!empty($search_params)) {
    $posts_stmt = $conn->prepare($posts_sql);
    $params = array_merge($search_params, [$per_page, $offset]);
    $types = $search_types . 'ii';
    $posts_stmt->bind_param($types, ...$params);
    $posts_stmt->execute();
    $posts_result = $posts_stmt->get_result();
} else {
    $posts_stmt = $conn->prepare($posts_sql);
    $posts_stmt->bind_param('ii', $per_page, $offset);
    $posts_stmt->execute();
    $posts_result = $posts_stmt->get_result();
}

$posts = [];
while ($row = $posts_result->fetch_assoc()) {
    $posts[] = $row;
}
$posts_stmt->close();

CloseCon($conn);
?>