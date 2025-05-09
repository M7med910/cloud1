<?php
// الاتصال بقاعدة البيانات
$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات.");
}

// حساب عدد الزيارات الكلي
$stmt = $pdo->query("SELECT COUNT(*) FROM visits");
$total_visits = $stmt->fetchColumn();
?>
