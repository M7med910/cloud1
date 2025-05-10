<?php
$host = 'n11111111.mysql.database.azure.com';
$dbname = 'n11111111';
$username = 'm';
$password = '11111111nN';

$conn = new mysqli($host, $username, $password, $dbname);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("fفشل الاتصال بقاعدة البيانات.");
}
$ip = $_SERVER['REMOTE_ADDR'];

// تحقق هل هذا الـ IP زار اليوم بالفعل
$stmt = $pdo->prepare("SELECT COUNT(*) FROM visits WHERE ip_address = ? AND DATE(visit_time) = CURDATE()");
$stmt->execute([$ip]);
$already_visited = $stmt->fetchColumn();

if ($already_visited == 0) {
    // لم يُسجّل اليوم، نسجله الآن
    $stmt = $pdo->prepare("INSERT INTO visits (ip_address) VALUES (?)");
    $stmt->execute([$ip]);

}
?>
