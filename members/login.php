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
            <h1>Welcome Back!</h1>
        </div>
        <form id="profileForm" action="post_login.php" method="POST" enctype="multipart/form-data">
            <div class="form-fields">
                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <input type="email" id="email" name="email" placeholder="john@gmail.com" required />
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required />
                </div>

                <div class="form-buttons">
                    <button type="submit">Sign In</button>
                </div>
            </div>
        </form>


        <div class="signup">
            <p>Don’t have an account? </p>
            <a href="registration.php"> Sign Up </a>
        </div>

        <div class="admin-login">
            <p>Are you an admin? </p>
            <a href="../admin/login_admin.php"> Admin Login </a>
        </div>
        
</body>