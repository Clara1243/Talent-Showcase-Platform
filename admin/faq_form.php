<?php
include '../includes/header_admin.php';
include('../includes/db_connect.php');
$conn = OpenCon();
?>

<div style="padding: 80px 100px; font-family: Arial, sans-serif;">
    <h2 style="margin-bottom: 30px;">Admin/FAQ Form</h2>

    <div style="background: #f9f9f9; padding: 40px; border-radius: 10px; max-width: 700px; margin: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <form method="POST" action="">
            <label for="name" style="font-weight: bold;">QUESTION:</label><br>
            <input type="text" id="name" name="name" required style="width: 100%; padding: 12px; margin: 12px 0 25px 0; border: 1px solid #ccc; border-radius: 6px;"><br>

            <label for="message" style="font-weight: bold;">ANSWER:</label><br>
            <textarea id="message" name="message" required style="width: 100%; height: 120px; padding: 12px; border: 1px solid #ccc; border-radius: 6px;"></textarea><br>

            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" style="padding: 10px 30px; background-color: #004aad; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["name"]));
    $message = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["message"]));

    $sql = "INSERT INTO faq_admin (question, answer) VALUES ('$name', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('FAQ added successfully!'); window.location.href='faq_admin_with_add.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Could not add FAQ. Please try again.');</script>";
    }
}

    <?php include '../includes/footer-admin.php'; ?>
?>
