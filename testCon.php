<?php
// عرض الأخطاء للمساعدة في التصحيح
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// بيانات الاتصال بقاعدة البيانات
$host = 'n11111111.mysql.database.azure.com';
$dbname = 'newschema';
$username = 'm';
$password = '11111111nN';

// الاتصال باستخدام MySQLi
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// استرجاع اسم المستخدم إذا كان مسجل دخول
$name = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT name FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();
}

// جلب المزادات
$auctions = [];
$sql = "SELECT a.auction_id, a.starting_price, a.start_date, a.end_date,
               p.name AS product_name, p.category
        FROM auction a
        JOIN product p ON a.product_id = p.product_id
        ORDER BY a.auction_id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $auctions[] = $row;
    }
}

$conn->close();
?>
