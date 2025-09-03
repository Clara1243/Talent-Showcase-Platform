<?php
 if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
 }
?>
<body>
    <link rel="stylesheet" href="../includes/header.css">
    <div class="header">
        <div class="logo">
            <a href="../members/index.php"><img src="../images/logo_2.png" alt="Logo" class="logo-img"></a>
        </div>
        
        <ul class="nav-links">
            <li class="nav-item"><a href="../members/index.php">News & Announcement</a></li>
            <li class="nav-item"><a href="../members/discussion.php">Discussion</a></li>
            <li class="dropdown">
            <a href="../members/view_catalog.php" class="dropbtn">Talent Marketplace <span>&#9662;</span></a>
                <ul class="dropdown-content">
                    <li><a href="../members/view_catalog.php">E-Catalogue</a></li>
                    <li><a href="../members/view_cart.php">Shopping Cart</a></li>
                    <li><a href="../members/upload_product.php">Post My Talent</a></li>
                </ul>
            </li>
            <li class="nav-item"><a href="../members/faq_user.php">FAQ</a></li>
            <li class="nav-item"><a href="../members/feedback.php">Feedback</a></li>
            <li class="nav-item"><a href="../admin/about_us.php">About Us</a></li>

        </ul>
        
        <ul class="icon">
            <li class="profile">
            <a class="icons-drpbtn"><img src="../images/profile-placeholder.png" alt="Profile"></a>
                <ul class="profile-dropdown">
                    <li><a href="profile.php">Profile</a></li>
                    <li><a onclick="showLogoutModal()">Logout</a></li>
                </ul>
            </li>
        </ul>
        
        <div class="modal-overlay" id="logoutModal">
            <div class="modal">
                <h3>Confirm Logout</h3>
                <p>Are you sure you want to logout? You will need to sign in again to access your account.</p>
                <div class="modal-buttons">
                    <button class="modal-btn cancel-btn" onclick="hideLogoutModal()">
                        Cancel
                    </button>
                    <button class="modal-btn confirm-btn" id="confirmBtn" onclick=
                    "confirmLogout()">
                        Yes, Logout
                    </button>
                </div>
            </div>
        </div>

        <script src="../js/logout.js"></script>

    </div>
</body>
</html>