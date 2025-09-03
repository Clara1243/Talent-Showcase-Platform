<?php
session_start();
include '../includes/header_user.php';
require_once '../includes/db_connect.php';
$conn = OpenCon();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = 1;
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $imageName = time() . "_" . basename($_FILES['product_image']['name']); 
    $uploadDir = '../uploads/';
    $uploadPath = $uploadDir . $imageName;
    $storePath = 'uploads/' . $imageName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
        $stmt = $conn->prepare("INSERT INTO PRODUCT (AdminID, Title, Category, Description, Product_Image, Upload_Date, Status)
                                VALUES (?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("isssss", $admin_id, $title, $category, $description, $storePath, $status);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Product uploaded successfully'); window.location.href='upload_product.php';</script>";
    } else {
        echo "Image upload failed. Error: " . $_FILES['product_image']['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Product</title>
    <link rel="stylesheet" href="../css/dashboard-admin.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/upload_product.css">
</head>
<body>
    <div class="form-card">
        <h2>Upload Product / Talent</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Title</label>
            <input type="text" name="title" required>

            <label>Category</label>
            <input type="text" name="category" required>

            <label>Description</label>
            <textarea name="description" rows="5" required></textarea>

            <label>Upload Image</label>
            <input type="file" name="product_image" accept="image/*" required>

            <label>Status</label>
            <select name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="sold">Sold</option>
            </select>

            <button type="submit">Upload Product</button>
        </form>
    </div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
