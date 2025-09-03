<?php
session_start();
include '../includes/header_user.php';
include 'get_profile.php';
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
            <button type="submit" onclick="window.location.href='update_profile.php'">Edit Profile</button>
        </div>

        <div class="profile-upload">
            <div class="profile-pic">
                <img class='profile-img' src="<?php echo ($user['user_image']) ?
                                                    '../uploads/user_profile/' . htmlspecialchars($user['user_image']) :
                                                    '../images/profile.svg'; ?>">
            </div>
        </div>

        <div>
            <div class='profile-info-label'>NAME</div>
            <div><?php echo htmlspecialchars($user['user_name']); ?></div>
        </div>

        <div>
            <div class='profile-info-label'>BIO</div>
            <div><?php echo htmlspecialchars($user['user_bio']); ?></div>
        </div>

        <div>
            <div class='profile-info-label'>EMAIL</div>
            <div><?php echo htmlspecialchars($user['user_email']); ?></div>
        </div>

        <div>
            <div class='profile-info-label'>TALENTS</div>
            <div class="talent-container">
                <?php
                $talents = explode(',', $user['user_talent']); 
                foreach ($talents as $talent) {
                    $trimmed = trim($talent);
                    if (!empty($trimmed)) {
                        echo "<div class='talent'>" . htmlspecialchars($trimmed) . "</div>";
                    }
                }
                ?>

            </div>
        </div>
    </div>


<?php
include '../includes/footer.php';
?>