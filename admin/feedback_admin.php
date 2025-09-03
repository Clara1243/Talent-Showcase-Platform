<?php
include '../includes/header_admin.php';
include 'get_feedback_admin.php';

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . 'm ago';
    if ($time < 86400) return floor($time/3600) . 'h ago';
    if ($time < 2592000) return floor($time/86400) . 'd ago';
    if ($time < 31536000) return floor($time/2592000) . ' months ago';
    return floor($time/31536000) . ' years ago';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/feedback-admin.css">
    <title>Feedback Management</title>
</head>

<body>
    <div class="heading">
        <h1>Feedback</h1>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <form method="POST" id="feedbackForm">
                    <div class="sidebar-header">
                        <div class="checkbox" id="selectAllCheckbox" onclick="toggleSelectAll()"></div>
                        <select class="feedback_filter" id="feedback_filter" onchange="filterFeedback()">
                            <option value="">Select Type</option>
                            <option value="Reporting Errors" <?php echo ($filter_type == 'Reporting Errors') ? 'selected' : ''; ?>>Reporting Errors</option>
                            <option value="Reporting Users" <?php echo ($filter_type == 'Reporting Users') ? 'selected' : ''; ?>>Reporting Users</option>
                            <option value="Improve Suggestion" <?php echo ($filter_type == 'Improve Suggestion') ? 'selected' : ''; ?>>Improve Suggestion</option>
                        </select>
                        <div class="action-buttons">
                            <button type="submit" name="action" value="mark_read" class="btn btn-secondary" onclick="return confirmAction('mark as read')">Mark as Read</button>
                            <button type="submit" name="action" value="delete" class="btn btn-danger" onclick="return confirmAction('delete')">Delete</button>
                        </div>
                    </div>
                    
                    <ul class="feedback-list" id="feedbackList">
                        <?php if (!empty($feedbacks)): ?>
                            <?php foreach ($feedbacks as $feedback): ?>
                                <li class="feedback-item <?php echo ($feedback['feedback_id'] == $selected_feedback_id) ? 'active' : ''; ?>" 
                                    onclick="selectFeedback(<?php echo $feedback['feedback_id']; ?>)"
                                    data-feedback-id="<?php echo $feedback['feedback_id']; ?>">
                                    
                                    <div class="feedback-item-header">
                                        <input type="checkbox" name="selected_feedback[]" value="<?php echo $feedback['feedback_id']; ?>" 
                                               class="feedback-checkbox" onclick="event.stopPropagation(); updateSelectAll();">
                                        <div class="feedback-title">
                                            <?php echo htmlspecialchars($feedback['User_Name'] ?: 'Anonymous User'); ?>
                                            <?php if ($feedback['read_status'] == 0): ?>
                                                <span class="unread-indicator">●</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="feedback-preview">
                                        <?php echo htmlspecialchars(substr($feedback['feedback_description'], 0, 50)) . '...'; ?>
                                    </div>
                                    <div class="feedback-time">
                                        <?php echo timeAgo($feedback['submitted_at']); ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="no-feedback">No feedback found</li>
                        <?php endif; ?>
                    </ul>
                </form>
            </div>
            
            <div class="content-area">
                <?php if ($current_feedback): ?>
                    <div class="content-header">
                        <div>
                            <div class="content-title">
                                From: <?php echo htmlspecialchars($current_feedback['User_Name'] ?: 'Anonymous User'); ?>
                            </div>
                            <div class="content-time">
                                <?php echo date('d/m/Y, g:i:s a', strtotime($current_feedback['submitted_at'])); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tag"><?php echo htmlspecialchars($current_feedback['feedback_type']); ?></div>
                    
                    <div class="content-body">
                        <h4>Feedback Description:</h4>
                        <p><?php echo nl2br(htmlspecialchars($current_feedback['feedback_description'])); ?></p>
                        
                        <?php if (!empty($current_feedback['supporting_image'])): ?>
                            <div style="margin-top: 20px;">
                                <h4 class="supporting_image">Supporting Image:</h4>
                                <?php 
                                $imageData = $current_feedback['supporting_image'];
                                ?>
                                <div style="margin-top: 10px;">
                                    <img src="data:<?php echo htmlspecialchars($current_feedback['image_type']); ?>;base64,<?php echo base64_encode($imageData); ?>"
                                    alt="Supporting Image"
                                    class="feedback-image"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">

                                    <div style="display: none; padding: 20px; background: #f8f9fa; border-radius: 4px; text-align: center; color: #666;">
                                        <p>Unable to display image. The image format may not be supported or the data may be corrupted.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="content-header">
                        <div class="content-title">No feedback selected</div>
                    </div>
                    <div class="content-body">
                        <p>Select a feedback item from the list to view its details.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../js/feedback_admin.js"></script>
    

    <?php include '../includes/footer-admin.php'; ?>