<?php
require_once '../includes/db_connect.php';
$conn = OpenCon();

session_start();
if (isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) {
    include '../includes/header_user.php';
} elseif (isset($_SESSION['admin_id']) || isset($_SESSION['logged_in'])) {
    include '../includes/header_admin.php';
} else {
    include '../includes/header.php';
}

$members = [
    [
        'name' => 'Liew Wen Xing',
        'id' => '251UC25099',
        'section' => 'TC1L',
        'email' => 'LIEW.WEN.XING@student.mmu.edu.my',
        'photo' => '../images/wx.jpg',
    ],
    [
        'name' => 'Tai Qi Tong',
        'id' => '1211102777',
        'section' => 'TC1L',
        'email' => '1211102777@student.mmu.edu.my',
        'photo' => '../images/qt.jpeg',
    ],
    [
        'name' => 'Dharmendiran Sukumaran',
        'id' => '1191101901',
        'section' => 'TC1L',
        'email' => '1191101901@student.mmu.edu.my',
        'photo' => '"../images/Dharmen.jpg"',
    ],
    [
        'name' => 'Amirtha A/P Sathies Kumar',
        'id' => '251UC250NX',
        'section' => 'TC1L',
        'email' => 'AMIRTHA.SATHIES.KUMAR@student.mmu.edu.my',
        'photo' => '../images/Amirtha.jpeg',
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/about-us.css">
    <title>About Us</title>
</head>

<body>
    <main class="container">
        <section class="header-section">
            <h1>Group W Members Details</h1>
        </section>

        <section class="content-section">
            <table class="members-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name & ID</th>
                        <th>Section</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td data-label="Photo">
                                <figure>
                                    <img src="<?= htmlspecialchars($member['photo']) ?>" alt="Photo of <?= htmlspecialchars($member['name']) ?>" class="member-photo">
                                </figure>
                            </td>
                            <td data-label="Name & ID">
                                <div class="member-name"><?= htmlspecialchars($member['name']) ?></div>
                                <div class="student-id">Student ID: <?= htmlspecialchars($member['id']) ?></div>
                            </td>
                            <td data-label="Section">
                                <span class="section-badge"><?= htmlspecialchars($member['section']) ?></span>
                            </td>
                            <td data-label="Contact">
                                <a href="mailto:<?= htmlspecialchars($member['email']) ?>" class="contact-link">Send Email</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php
    if (isset($_SESSION['admin_id']) || isset($_SESSION['logged_in'])) {
        include '../includes/footer-admin.php';
    } else {
        include '../includes/footer.php';
    }
    ?>
</body>

</html>