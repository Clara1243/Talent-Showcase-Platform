<?php
include '../includes/auth_header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/registration.css">
    <script src="https://kit.fontawesome.com/549a073f81.js" crossorigin="anonymous"></script>
    <title>Sign Up</title>
</head>

<body>
    <div class="profile-container">
        <div class="profile-title">
            <h1>Create your account</h1>
        </div>
        <form id="profileForm" action="save_registration.php" method="POST" enctype="multipart/form-data">
            <div class="profile-upload">
                <label for="profile" class="profile-label">
                    <img class='profile-img' src="../images/profile.svg" id="profile-preview">
                </label>
                <input type="file" id="profile" name="profile" accept=".png,.jpg,.jpeg, .svg" hidden>
            </div>

            <label for="name">FULL NAME</label>
            <input type='text' id="name" name="name" placeholder="John Doe" required />

            <label for="name">EMAIL</label>
            <input type='email' id="email" name="email" placeholder="john@gmail.com" required />

            <label for="password">
                PASSWORD
                <span class="info-icon" title="Minimum 8 characters, including uppercase, lowercase, and numbers.">
                    <i class="fa-solid fa-circle-info"></i>
                </span>
            </label>

            <input type='password' id="password" name="password" required></textarea>

            <label for="name">BIO</label>
            <textarea id="bio" name="bio" placeholder="Hi, nice to meet you!"></textarea>

            <?php
            $type = ['Art', 'Business', 'Marketing', 'Finance', 'Music', 'Design', 'Writing'];
            $typeClass = '';
            $typeMessage = '';
            ?>
            <div class="form-group <?php echo $typeClass ?>">
                <label for="talent">TALENTS</label>
                <div class="checkbox-group">
                    <?php foreach ($type as $option): ?>
                        <label>
                            <input type="checkbox" name="talent[]" value="<?php echo htmlspecialchars($option); ?>">
                            <?php echo htmlspecialchars($option); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <p class="help-block"><?php echo $typeMessage; ?></p>
            </div>

            <div class="form-buttons">
                <button type="reset" onclick="window.location.href='login.php'">Back</button>
                <button type="submit">Sign Up</button>
            </div>
        </form>
    </div>

</body>
<script src="../js/registration.js"></script>

</html>