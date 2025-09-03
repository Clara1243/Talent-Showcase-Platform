<?php
include '../includes/header_admin.php';
include 'get_profile_admin.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <title>Profile</title>
</head>

<div class="profile-container">
    <div class="profile-title">
        <h1>Personal Info</h1>
    </div>
    <form id="profileForm" action="save_profile_admin.php" method="POST" enctype="multipart/form-data">
        <div class="profile-upload">
            <label for="profile" class="profile-label">
                <img class='profile-img' src="<?php echo ($admin['admin_image']) ?
                                                    '../uploads/admin_profile/' . htmlspecialchars($admin['admin_image']) :
                                                    '../images/profile.svg'; ?>" id="profile-preview">
            </label>
            <input type="file" id="profile" name="profile" accept=".png,.jpg,.jpeg, .svg" hidden>
        </div>

        <label for="name">ADMIN ID</label>
        <div><?php echo htmlspecialchars($admin['admin_id']); ?></div>

        <label for="name">NAME</label>
        <input type='text' id="name" name="name" placeholder="<?php echo htmlspecialchars($admin['admin_name']); ?> " />

        <label for="name">EMAIL</label>
        <input type='email' id="email" name="email" placeholder="<?php echo htmlspecialchars($admin['admin_email']); ?>" />

        <div class="form-buttons">
            <button type="reset" onclick="window.location.href='profile_admin.php'">Cancel</button>
            <button type="submit">Submit</button>
        </div>
    </form>
</div>


<?php include '../includes/footer-admin.php'; ?>
<script src="../js/registration.js"></script>

</html>