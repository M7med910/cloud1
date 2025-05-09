<?php
session_start();

$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التأكد أن المستخدم مسؤول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// التحقق من وجود user_id
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // جلب حالة المستخدم الحالية
    $stmt = $conn->prepare("SELECT is_blocked FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($is_blocked);
    $stmt->fetch();
    $stmt->close();

    if ($is_blocked === 0 || $is_blocked === 1) {
        // قلب الحالة: من محظور إلى نشط أو العكس
        $new_status = $is_blocked ? 0 : 1;

        $stmt = $conn->prepare("UPDATE user SET is_blocked = ? WHERE user_id = ?");
        $stmt->bind_param("ii", $new_status, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: admin_dashboard.php"); // رجوع إلى لوحة التحكم
exit();
?>
