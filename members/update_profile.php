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
        </div>
        <form id="profileForm" action="save_profile.php" method="POST" enctype="multipart/form-data">
            <div class="profile-upload">
                <label for="profile" class="profile-label">
                    <img class='profile-img' src="<?php echo ($user['user_image']) ?
                                                        '../uploads/user_profile/' . htmlspecialchars($user['user_image']) :
                                                        '../images/profile.svg'; ?>" id="profile-preview">
                </label>
                <input type="file" id="profile" name="profile" accept=".png,.jpg,.jpeg, .svg" hidden>
            </div>

            <label for="name">NAME</label>
            <input type='text' id="name" name="name" placeholder=<?php echo htmlspecialchars($user['user_name']); ?> />

            <label for="name">BIO</label>
            <textarea id="bio" name="bio" placeholder="<?php echo htmlspecialchars($user['user_bio']); ?>"></textarea>

            <label for="name">EMAIL</label>
            <input type='email' id="email" name="email" placeholder=<?php echo htmlspecialchars($user['user_email']); ?> />

            <?php
            $type = ['Art', 'Business', 'Marketing', 'Finance', 'Music', 'Design', 'Writing'];
            $userTalents = isset($user['user_talent'])
                ? array_map('trim', explode(',', $user['user_talent']))
                : [];
            $typeClass = '';
            $typeMessage = '';
            ?>
            <div class="form-group <?php echo $typeClass ?>">
                <label for="talent">TALENTS</label>
                <div class="checkbox-group">
                    <?php foreach ($type as $option): ?>
                        <label>
                            <input type="checkbox" name="talent[]" value="<?php echo htmlspecialchars($option); ?>"
                                <?php if (in_array($option, $userTalents)) echo 'checked'; ?>>
                            <?php echo htmlspecialchars($option); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
                <p class="help-block"><?php echo $typeMessage; ?></p>
            </div>

            <div class="form-buttons">
                <button type="reset" onclick="window.location.href='profile.php'">Cancel</button>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>


    <?php
    include '../includes/footer.php';
    ?>
    <script src="../js/registration.js"></script>
</body>

</html>