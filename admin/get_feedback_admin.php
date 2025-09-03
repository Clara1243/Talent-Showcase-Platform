<?php
include '../includes/db_connect.php';
$conn = OpenCon();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $selected_ids = isset($_POST['selected_feedback']) ? $_POST['selected_feedback'] : [];
        
        if (!empty($selected_ids)) {
            $selected_ids = array_map('intval', $selected_ids);
            $placeholders = str_repeat('?,', count($selected_ids) - 1) . '?';
            
            if ($action === 'mark_read') {
                $sql = "UPDATE feedback SET read_status = 1 WHERE feedback_id IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_ids));
                $stmt->bind_param($types, ...$selected_ids);
                $stmt->execute();
                $stmt->close();
                $success_message = "Feedback marked as read successfully.";
            } elseif ($action === 'delete') {
                $sql = "DELETE FROM feedback WHERE feedback_id IN ($placeholders)";
                $stmt = $conn->prepare($sql);
                $types = str_repeat('i', count($selected_ids));
                $stmt->bind_param($types, ...$selected_ids);
                $stmt->execute();
                $stmt->close();
                $success_message = "Feedback deleted successfully.";
            }
        } else {
            $error_message = "Please select feedback items first.";
        }
    }
}

$filter_type = isset($_GET['type']) ? $_GET['type'] : '';
$where_clause = '';
if (!empty($filter_type)) {
    $where_clause = "WHERE feedback_type = '" . mysqli_real_escape_string($conn, $filter_type) . "'";
}

$sql = "SELECT f.feedback_id, f.user_id, f.feedback_type, f.feedback_description, 
               f.supporting_image, f.image_type, f.read_status, f.submitted_at, u.User_Name
        FROM feedback f 
        LEFT JOIN user u ON f.user_id = u.User_ID 
        $where_clause
        ORDER BY f.submitted_at DESC";

$result = mysqli_query($conn, $sql);
$feedbacks = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $feedbacks[] = $row;
    }
}

$selected_feedback_id = isset($_GET['selected']) ? intval($_GET['selected']) : (isset($feedbacks[0]) ? $feedbacks[0]['feedback_id'] : null);
$current_feedback = null;
if ($selected_feedback_id) {
    foreach ($feedbacks as $feedback) {
        if ($feedback['feedback_id'] == $selected_feedback_id) {
            $current_feedback = $feedback;
            if ($feedback['read_status'] == 0) {
                $update_sql = "UPDATE feedback SET read_status = 1 WHERE feedback_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param('i', $selected_feedback_id);
                $update_stmt->execute();
                $update_stmt->close();
                $current_feedback['read_status'] = 1;
            }
            break;
        }
    }
}

CloseCon($conn);