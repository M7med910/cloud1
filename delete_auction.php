<?php
session_start();

$host = 'n11111111.mysql.database.azure.com';
$dbname = 'newschema';
$username = 'm';
$password = '11111111nN';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// التحقق من أن المستخدم Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// التحقق من رقم المزاد
if (isset($_GET['auction_id'])) {
    $auction_id = intval($_GET['auction_id']);

    // حذف المزاد من جدول auction (تقدر تضيف حذف من جداول ثانية حسب الحاجة)
    $stmt = $conn->prepare("DELETE FROM auction WHERE auction_id = ?");
    $stmt->bind_param("i", $auction_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header('Location: admin_dashboard.php');
exit();
?>
