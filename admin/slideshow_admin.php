<?php
include '../includes/header_admin.php';
include('../includes/db_connect.php');
$conn = OpenCon();

$result = mysqli_query($conn, "SELECT * FROM showcase_gallery ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Announcement Management</title>
</head>

<body>

    <div class="slideshow-container" style="max-width: 900px; margin: auto; padding: 80px 20px;">
        <?php while ($img = mysqli_fetch_assoc($result)) { ?>
            <div class="mySlides" style="display: none;">
                <img src="<?php echo $img['image_path']; ?>" alt="Showcase" style="width:100%; border-radius:12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <div class="caption" style="text-align:center; margin-top:10px; font-size:18px; color:#004aad;"><?php echo htmlspecialchars($img['title']); ?></div>
            </div>
        <?php } ?>
    </div>

    <a href="upload_showcase.php" style="display:block; margin:30px auto; padding:12px 24px; background-color:#004aad; color:white; border:none; border-radius:8px; font-size:16px; text-align:center; text-decoration:none; width:200px;">➕ Add New Showcase</a>

    <script>
        let slideIndex = 0;
        showSlides();
        function showSlides() {
            let slides = document.getElementsByClassName("mySlides");
            for (let i = 0; i < slides.length; i++) slides[i].style.display = "none";
            slideIndex = (slideIndex + 1 > slides.length) ? 1 : slideIndex + 1;
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 4000);
        }
    </script>

    <?php include '../includes/footer-admin.php'; ?>