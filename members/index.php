<?php
require_once '../includes/db_connect.php';
$conn = OpenCon();

session_start();
    if (isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
    include '../includes/header_user.php';
} else {
    include '../includes/header.php';
}


$gallery_sql = "SELECT image_path FROM showcase_gallery ORDER BY id DESC";
$gallery_result = mysqli_query($conn, $gallery_sql);
$showcase_images = [];
while ($row = mysqli_fetch_assoc($gallery_result)) {
    $correctedPath = '../admin/' . $row['image_path'];
    $showcase_images[] = $correctedPath;
}
?>

<div style="padding: 60px 100px;">
    <div id="image-carousel" style="background: #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 10px; padding: 40px; text-align: center; position: relative;">
        <div id="image-carousel-content">
        </div>
        <div style="margin-top: 20px;">
            <button onclick="prevImage()" style="padding: 8px 16px;">&lt;</button>
            <button onclick="nextImage()" style="padding: 8px 16px;">&gt;</button>
        </div>
    </div>
</div>

<script>
    const showcaseImages = <?php echo json_encode($showcase_images); ?>;
    let currentImageIndex = 0;

    function renderImageCarousel() {
        const container = document.getElementById("image-carousel-content");
        if (showcaseImages.length === 0) {
            container.innerHTML = "<p>No images available.</p>";
        } else {
            const imagePath = showcaseImages[currentImageIndex];
            container.innerHTML = `
                <img src="${imagePath}" alt="Showcase Image" style="max-width: 100%; max-height: 500px; border-radius: 10px;">
            `;
        }
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % showcaseImages.length;
        renderImageCarousel();
    }

    function prevImage() {
        currentImageIndex = (currentImageIndex - 1 + showcaseImages.length) % showcaseImages.length;
        renderImageCarousel();
    }

    document.addEventListener("DOMContentLoaded", renderImageCarousel);
</script>

<?php
include('../includes/footer.php');
?>
