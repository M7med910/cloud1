<?php
// إعدادات قاعدة البيانات
$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

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
