<?php
session_start();
require_once '../includes/db_connect.php';
$conn = OpenCon();

$admin_id = $_SESSION['admin_id'];

$sql = "SELECT admin_name, admin_email, admin_image FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$current = $result->fetch_assoc();

// Collect submitted values
$name = trim($_POST['name']);
$email = trim($_POST['email']);

// Determine changes
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

verifyChanges($changes, $params, $types, $name, $current['admin_name'], 'admin_name');
verifyChanges($changes, $params, $types, $email, $current['admin_email'], 'admin_email');

$profile_img = null;
if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/admin_profile/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
    $newFileName = $admin_id . '.' . strtolower($ext);
    $uploadFile = $uploadDir . $newFileName;

    if (move_uploaded_file($_FILES['profile']['tmp_name'], $uploadFile)) {
        $oldPath = $uploadDir . $current['admin_image'];
        if (file_exists($oldPath) && $current['admin_image'] !== $newFileName) {
            unlink($oldPath); 
        }

        $profile_img = $newFileName;
        $changes[] = "admin_image = ?";
        $params[] = $profile_img;
        $types .= "s";
    }
}


if (empty($changes)) {
    echo "<script>alert('No changes detected.'); window.location.href='profile_admin.php';</script>";
    exit;
}

$setClause = implode(", ", $changes);
$sql = "UPDATE admin SET $setClause WHERE admin_id = ?";
$params[] = $admin_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    header("Location: profile_admin.php?update=success");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$conn->close();
?>