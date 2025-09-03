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
    <link rel="stylesheet" href="../css/login.css">
    <title>Sign In</title>
</head>

<body>
    <div class="profile-container">
        <div class="profile-title">
            <h1>Admin Login</h1>
        </div>
        <form id="profileForm" action="post_login_admin.php" method="POST" enctype="multipart/form-data">
            <div class="form-fields">
                <div class="form-group">
                    <label for="id">ADMIN ID</label>
                    <input type="text" id="id" name="id" required />
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="form-buttons">
                    <button type="submit">Sign In</button>
                </div>

                <div class="user-login">
                    <p>Are you a user? </p>
                    <a href="../members/login.php"> User Login </a>
                </div>
            </div>
        </form>


</body>