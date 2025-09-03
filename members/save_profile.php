<?php
session_start();
include '../includes/db_connect.php'; 
$conn = OpenCon();
$user_id = $_SESSION['user_id'];

$sql = "SELECT user_name, user_bio, user_email, user_talent, user_image FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$current = $result->fetch_assoc();

$name = trim($_POST['name']);
$bio = trim($_POST['bio']);
$email = trim($_POST['email']);
$talents = isset($_POST['talent']) ? implode(', ', array_map('trim', $_POST['talent'])) : null;

$changes = [];
$params = [];
$types = "";

function verifyChanges(&$changes, &$params, &$types, $newValue, $currentValue, $columnName)
{
    if ($newValue !== "" && $newValue !== $currentValue) {
        $changes[] = "$columnName = ?";
        $params[] = $newValue;
        $types .= "s";
    }
}

verifyChanges($changes, $params, $types, $name, $current['user_name'], 'user_name');
verifyChanges($changes, $params, $types, $bio, $current['user_bio'], 'user_bio');
verifyChanges($changes, $params, $types, $email, $current['user_email'], 'user_email');
verifyChanges($changes, $params, $types, $talents, $current['user_talent'], 'user_talent');


$profile_img = null;
if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/user_profile/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION));
    $newFileName = $user_id . '.' . $ext;
    $uploadFile = $uploadDir . $newFileName;

    // Only delete old image if it's different
    if (!empty($current['user_image']) && $current['user_image'] !== $newFileName) {
        $oldPath = $uploadDir . $current['user_image'];
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }
    }

    if (move_uploaded_file($_FILES['profile']['tmp_name'], $uploadFile)) {
        $changes[] = "user_image = ?";
        $params[] = $newFileName;
        $types .= "s";
    }
}

// If no changes, show alert
if (empty($changes)) {
    echo "<script>alert('No changes detected.'); window.location.href='profile.php';</script>";
    exit;
}

$setClause = implode(", ", $changes);
$sql = "UPDATE user SET $setClause WHERE user_id = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: profile.php?update=success");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
