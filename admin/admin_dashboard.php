<?php
include '../includes/header_admin.php';
include 'get_admin_dashboard.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard-admin.css">
    <title>Dashboard</title>
</head>

<body>
    <div class="container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
        </div>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="section">
            <div class="section-header">
                Users
            </div>
            <div class="section-content">
                <div class="search-bar">
                    <form method="GET" action="admin_dashboard.php" class="search-form">
                        <input type="text" name="search" placeholder="Search by ID or Name..." 
                            value="<?php echo htmlspecialchars($search); ?>" id="searchInput">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <form method="POST">
                    <div class="user-controls">
                        <div class="action-buttons">
                            <button type="submit" name="action" value="activate" class="btn btn-success" 
                                    onclick="return confirmAction('activate')">Activate</button>
                            <button type="submit" name="action" value="deactivate" class="btn btn-warning"
                                    onclick="return confirmAction('deactivate')">Deactivate</button>
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
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_users[]" 
                                                       value="<?php echo $user['User_ID']; ?>" 
                                                       class="user-checkbox">
                                            </td>
                                            <td><?php echo htmlspecialchars($user['User_ID']); ?></td>
                                            <td><?php echo htmlspecialchars($user['User_Name']); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $user['User_Status']; ?>">
                                                    <?php echo ucfirst($user['User_Status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                    <td colspan="4" class="empty-state">
                                        <?php echo $search ? "No users found matching \"$search\"" : "No users found"; ?>
                                    </td>
                                    </tr>
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

        <div class="section">
            <div class="section-header">
                Latest Feedback
            </div>
            <div class="section-content">
                <?php if (!empty($latest_feedback)): ?>
                    <?php foreach ($latest_feedback as $feedback): ?>
                        <div class="feedback-item">
                            <div class="feedback-header">
                                <div>
                                    <strong>ID:</strong> <?php echo htmlspecialchars($feedback['feedback_id']); ?>
                                    <span class="feedback-type"><?php echo htmlspecialchars($feedback['feedback_type']); ?></span>
                                </div>
                                <span class="feedback-status status-<?php echo $feedback['read_status'] ? 'read' : 'unread'; ?>">
                                    <?php echo $feedback['read_status'] ? 'Read' : 'Unread'; ?>
                                </span>
                            </div>
                            <div class="feedback-content">
                                <strong>Content:</strong> <?php echo htmlspecialchars(substr($feedback['feedback_description'], 0, 100)); ?>...
                            </div>
                            <div>
                                <strong>Status:</strong> 
                                <span class="feedback-status status-<?php echo $feedback['read_status'] ? 'read' : 'unread'; ?>">
                                    <?php echo $feedback['read_status'] ? 'Read' : 'Unread'; ?>
                                </span>
                            </div>
                            <a href="feedback_admin.php?selected=<?php echo $feedback['feedback_id']; ?>" 
                               class="feedback-link">View Details →</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">No feedback available</div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="section">
            <div class="section-header" id="Product">
                Product
            </div>
            <div class="section-content">
                <div class="search-bar">
                    <form method="GET" action="admin_dashboard.php" class="search-form">
                        <input type="text" name="searchProduct" placeholder="Search by ID or Product Name..." 
                            value="<?php echo htmlspecialchars($searchProduct); ?>" id="searchProductInput">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <form method="POST">
                    <div class="product-controls">
                        <div class="action-buttons">
                            <button type="submit" name="action" value="activate2" class="btn btn-success" 
                                    onclick="return confirmProductAction('activate')">Activate</button>
                            <button type="submit" name="action" value="deactivate2" class="btn btn-warning"
                                    onclick="return confirmProductAction('deactivate')">Deactivate</button>
                            <button type="submit" name="action" value="sold2" class="btn btn-warning"
                                    onclick="return confirmProductAction('sold')">Sold</button>
                            <button type="submit" name="action" value="delete2" class="btn btn-danger"
                                    onclick="return confirmProductAction('delete')">Delete</button>
                        </div>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="ProductSelectAll" onchange="toggleProductSelectAll()">
                                    </th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_products[]" 
                                                       value="<?php echo $product['Product_ID']; ?>" 
                                                       class="product-checkbox">
                                            </td>
                                            <td><?php echo htmlspecialchars($product['Product_ID']); ?></td>
                                            <td><?php echo htmlspecialchars($product['Title']); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $product['Status']; ?>">
                                                    <?php echo ucfirst($product['Status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                    <td colspan="4" class="empty-state">
                                        <?php echo $searchProduct ? "No products found matching \"$searchProduct\"" : "No products found"; ?>
                                    </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=1<?php echo $searchProduct ? '&searchProduct=' . urlencode($searchProduct) : ''; ?>">«</a>
                                <a href="?page=<?php echo $page - 1; ?><?php echo $searchProduct ? '&searchProduct=' . urlencode($searchProduct) : ''; ?>">‹</a>
                            <?php endif; ?>

                            <?php
                            $start = max(1, $page - 2);
                            $end = min($total_pages, $page + 2);
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                                <?php if ($i == $page): ?>
                                    <span class="current"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?><?php echo $searchProduct ? '&searchProduct=' . urlencode($searchProduct) : ''; ?>"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?><?php echo $searchProduct ? '&searchProduct=' . urlencode($searchProduct) : ''; ?>">›</a>
                                <a href="?page=<?php echo $total_pages; ?><?php echo $searchProduct ? '&searchProduct=' . urlencode($searchProduct) : ''; ?>">»</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

    </div>

    <script src="../js/dashboard.js"></script>

    <?php include '../includes/footer-admin.php'; ?>