<?php
include '../includes/header_admin.php';
include('../includes/db_connect.php');
$conn = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $uploadDir = 'uploads/';
    $uploadPath = $uploadDir . basename($imageName);

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (move_uploaded_file($imageTmp, $uploadPath)) {
        $sql = "INSERT INTO showcase_gallery (title, image_path) VALUES ('$title', '$uploadPath')";
        mysqli_query($conn, $sql);
        echo "<script>alert('Image uploaded successfully!'); window.location.href='slideshow_admin.php';</script>";
    } else {
        echo "<script>alert('Upload failed!');</script>";
    }
}
?>

<div style="padding: 60px 100px;">
    <div style="background: #f9f9f9; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto;">
        <h2>Upload Talent Showcase Image</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label><br>
            <input type="text" name="title" required style="width:100%; padding:10px; margin-bottom:20px;"><br>
            <label>Select Image:</label><br>
            <input type="file" name="image" accept="image/*" required style="margin-bottom:20px;"><br>
            <button type="submit" style="padding: 10px 30px; background-color: #004aad; color: white; border: none; border-radius: 6px;">Upload</button>
        </form>
    </div>
</div>

    <?php include '../includes/footer-admin.php'; ?>