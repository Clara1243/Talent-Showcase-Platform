<?php
session_start();
require_once '../includes/db_connect.php';
$conn = OpenCon();

$user_id = $_SESSION['user_id'];
$feedback_type = $_POST['feedback_type'] ?? '';
$feedback_description = $_POST['feedback_details'] ?? '';
$status = $statusMsg = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if(isset($_FILES["supporting_image"])) {
        $status = 'error';
        if(!empty($_FILES["supporting_image"]["name"])){
            $fileName = basename($_FILES["supporting_image"]["name"]);
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileType = strtolower($fileExt);
            $allowTypes = array('jpg', 'jpeg', 'png');
            $imageType = $_FILES['supporting_image']['type'];
            $maxFileSize = 1 * 1024 * 1024; 
        
            if(in_array($fileType, $allowTypes)){
                if($_FILES['supporting_image']['size'] > $maxFileSize) {
                    $statusMsg = "File size exceeds the 1MB limit.";
                } else {
                    $image = $_FILES['supporting_image']['tmp_name'];
                    $imageContent = file_get_contents($image);

        
                    $sql= "INSERT INTO feedback (user_id, feedback_type, feedback_description, supporting_image, image_type)
                    VALUES (?, ?, ?, ?, ?)";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('issbs', $user_id, $feedback_type, $feedback_description, $null, $imageType);
                    $stmt->send_long_data(3, $imageContent); 
        
                    if($stmt->execute()){
                        $status = 'success';
                        $statusMsg = 'Feedback submitted successfully!';
                    } else {
                        $statusMsg = 'Feedback submission failed: ' . $stmt->error;
                    }
                    $stmt->close();
                }
            } else {
                $statusMsg = 'Sorry, only JPG, JPEG, and PNG files are allowed to upload.';
            }
        } else {
            $statusMsg = 'Please select a file to upload.';
        }
    } else {
        $statusMsg = 'Please complete the Feedback Form.';
    }

    $_SESSION['feedback_status'] = $status;
    $_SESSION['feedback_message'] = $statusMsg;
    header("Location: feedback.php");
    exit();
}

$conn->close();
?>