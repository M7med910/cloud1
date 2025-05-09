<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("معرف المستخدم غير موجود");
}

$user_id = intval($_GET['user_id']);

$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

$stmt = $conn->prepare("UPDATE user SET is_blocked = 1 WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "فشل في تنفيذ الحظر: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
