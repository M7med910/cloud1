<?php
$host = 'n11111111.mysql.database.azure.com';
$dbname = 'newschema';
$username = 'm';
$password = '11111111nN';

$conn = new mysqli($host, $username, $password, $dbname, 3306, '/path/to/ssl-cert.pem');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("fفشل الاتصال بقاعدة البيانات.");
}


}
?>
