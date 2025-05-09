<?php
// الاتصال بقاعدة البيانات
$host = 'n11111111.mysql.database.azure.com';
$dbname = 'newschema';
$username = 'm';
$password = '11111111nN';

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
