<?php
include '../includes/header_admin.php';
include 'get_admin_discussion.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/discussion-admin.css">
    <title>Discussion Management</title>
</head>

<body>
    <div class="container">
            <h1>Discussion</h1>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="section">
            <div class="search-bar">
                <form method="GET" action="discussion_admin.php" class="search-form">
                    <input type="text" name="search" placeholder="Search by ID or Title..." 
                        value="<?php echo htmlspecialchars($search); ?>" id="searchInput">
                    <button type="submit">Search</button>
                </form>
            </div>
            <form method="POST">
                <div class="user-controls">
                    <div class="action-buttons">
                        <button type="submit" name="action" value="delete" class="btn btn-danger"
                                onclick="return confirmAction('delete')">Delete</button>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                </th>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Posted By</th>
                                <th>Posted Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($posts)): ?>
                                <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_posts[]" 
                                                   value="<?php echo $post['post_id']; ?>" 
                                                   class="post-checkbox">
                                        </td>
                                        <td><?php echo htmlspecialchars($post['post_id']); ?></td>
                                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                                        <td><?php echo htmlspecialchars($post['content']); ?></td>
                                        <td><?php echo htmlspecialchars($post['User_Name']); ?></td>
                                        <td><?php echo htmlspecialchars($post['posted_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="empty-state">
                                            <?php echo $search ? "No posts found matching \"$search\"" : "No posts found"; ?>
                                        </td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=1<?php echo $search ? '&search=' . urlencode($search) : ''; ?>">«</a>
                            <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">‹</a>
                        <?php endif; ?>
                        <?php
                            $start = max(1, $page - 2);
                            $end = min($total_pages, $page + 2);
                            for ($i = $start; $i <= $end; $i++):
                        ?>
                            <?php if ($i == $page): ?>
                                <span class="current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">›</a>
                            <a href="?page=<?php echo $total_pages; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">»</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script src="../js/discussion-admin.js"></script>

    <?php include '../includes/footer-admin.php'; ?>