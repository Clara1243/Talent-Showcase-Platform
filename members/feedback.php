<?php
session_start();
include '../includes/header_user.php';

$status = $_SESSION['feedback_status'] ?? '';
$statusMsg = $_SESSION['feedback_message'] ?? '';

unset($_SESSION['feedback_status']);
unset($_SESSION['feedback_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/feedback.css">
    <title>Feedback</title>
</head>

<body>
<div class="feedback-container">
    <h1>Feedback</h1>
    <?php if(!empty($statusMsg)){ ?>
        <p class="status <?= $status; ?>">
            <?= $statusMsg; ?>
        </p>
    <?php } ?>
    <form id="feedbackForm" action="save_feedback.php" method="POST" enctype="multipart/form-data">
        <?php
        $type = ['Reporting Errors', 'Reporting Users', 'Improve Suggestion'];
        $typeClass = '';
        $typeMessage = '';
        ?>
        <div class="form-group <?php echo $typeClass ?>">
            <label for="feedback_type">FEEDBACK TYPE</label>
            <select id="feedback_type" name="feedback_type" class="form-control" required>
                <option value="">Select your feedback type …</option>
                <?php
                foreach ($type as $option) {
                    echo '<option value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</option>';
                }
                ?>
            </select>
            <p class="help-block"><?php echo $typeMessage; ?></p>
        </div>

        <label for="feedback_details">FEEDBACK DETAILS</label>
        <textarea id="feedback_details" name="feedback_details" placeholder="Feedback details..." required></textarea>

        <label for="support_doc">UPLOAD DOCUMENT</label>
        <input type="file" id="supporting_image" name="supporting_image" required>

        <div class="form-buttons">
            <button type="reset">Reset</button>
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

<?php
include '../includes/footer.php';
?>
