<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
 if (!isset($_SESSION['admin_id'])) {
    header("Location: login_admin.php");
    exit;
 }
?>
<body>
    <link rel="stylesheet" href="../includes/header.css">
    <div class="header">
        <div class="logo">
            <a href="../admin/admin_dashboard.php"><img src="../images/logo_2.png" alt="Logo" class="logo-img"></a>
        </div>
        
        <ul class="nav-links">
            <li class="nav-item"><a href="../admin/admin_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a href="../admin/slideshow_admin.php">News & Announcement</a></li>
            <li class="nav-item"><a href="../admin/discussion_admin.php">Discussion</a></li>
            <li class="nav-item"><a href="../admin/faq_admin_with_add.php">FAQ</a></li>
            <li class="nav-item"><a href="../admin/feedback_admin.php">Feedback</a></li>
            <li class="nav-item"><a href="../admin/about_us.php">About Us</a></li>
        </ul>
        
        <ul class="icon">
            <li class="profile">
            <a class="icons-drpbtn"><img src="../images/profile-placeholder.png" alt="Profile"></a>
                <ul class="profile-dropdown">
                    <li><a href="../admin/profile_admin.php">Profile</a></li>
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
                    <button class="modal-btn confirm-btn" id="admin-confirmBtn" onclick=
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