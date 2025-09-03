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

<body>
    <div class="profile-container">
        <div class="profile-title">
            <h1>Personal Info</h1>
            <button type="submit" onclick="window.location.href='update_profile_admin.php'">Edit Profile</button>
        </div>

        <div class="profile-upload">
            <div class="profile-pic">
                <img class='profile-img' src="<?php echo ($admin['admin_image']) ?
                                                    '../uploads/admin_profile/' . htmlspecialchars($admin['admin_image']) :
                                                    '../images/profile.svg'; ?>">
            </div>
        </div>

        <div>
            <div class='profile-info-label'>ADMIN ID</div>
            <div><?php echo htmlspecialchars($admin['admin_id']); ?></div>
        </div>


        <div>
            <div class='profile-info-label'>NAME</div>
            <div>
                <?php
                echo !empty($admin['admin_name'])
                    ? htmlspecialchars($admin['admin_name'])
                    : '-';
                ?>
            </div>
        </div>


        <div>
            <div class='profile-info-label'>EMAIL</div>
            <div>
                <?php
                echo !empty($admin['admin_email'])
                    ? htmlspecialchars($admin['admin_email'])
                    : '-';
                ?>
            </div>
        </div>
    </div>
</body>


    <?php include '../includes/footer-admin.php'; ?>