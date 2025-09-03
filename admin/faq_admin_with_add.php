<?php
include '../includes/header_admin.php';
include('../includes/db_connect.php');
$conn = OpenCon();

$sql = "SELECT question, answer FROM faq_admin ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 30px;
            text-align: left;
        }

        .faq-container {
            padding: 80px 100px;
        }

        .faq-box {
            background-color: #f9f9f9;
            padding: 40px 50px;
            border-radius: 10px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .faq-box p {
            font-size: 16px;
            line-height: 1.6;
        }

        .faq-question {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .faq-answer {
            margin-bottom: 25px;
        }

        .add-button-container {
            text-align: center;
            margin-top: 30px;
        }

        .add-button {
            background-color: #004aad;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .add-button:hover {
            background-color: #00337a;
        }
    </style>
</head>
<body>

<div class="faq-container">
    <h2>Admin/FAQ</h2>
    <div class="faq-box">
        <?php
        if (mysqli_num_rows($result) > 0) {
            $qnum = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<p class='faq-question'>Q$qnum: " . htmlspecialchars($row['question']) . "</p>";
                echo "<p class='faq-answer'>A: " . htmlspecialchars($row['answer']) . "</p>";
                $qnum++;
            }
        } else {
            echo "<p>No admin FAQs available yet.</p>";
        }
        ?>

        <div class="add-button-container">
            <form action="faq_form.php" method="get">
                <button type="submit" class="add-button">Add</button>
            </form>
        </div>
    </div>
</div>

    <?php include '../includes/footer-admin.php'; ?>
</body>
</html>
