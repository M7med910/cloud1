<?php
// إعدادات قاعدة البيانات
$host = 'n11111111.mysql.database.azure.com';
$dbname = 'newschema';
$username = 'm';
$password = '11111111nN';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// إنشاء الاتصال باستخدام PDO
try {
    // الاتصال بقاعدة البيانات
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // تعيين إعدادات التعامل مع الأخطاء
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // في حال حدوث خطأ أثناء الاتصال
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
