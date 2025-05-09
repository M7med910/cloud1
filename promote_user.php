<?php
session_start();

// التحقق من أن المستخدم الحالي هو مسؤول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// الاتصال بقاعدة البيانات
$host = 'n11111111.mysql.database.azure.com';
$dbname = 'newschema';
$username = 'm';
$password = '11111111nN';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// معالجة طلب الترقية
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    
    // التحقق من وجود المستخدم
    $check_sql = "SELECT user_id, role FROM user WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $user = $check_result->fetch_assoc();
        
        // التحقق من أن المستخدم ليس مسؤولاً بالفعل
        if ($user['role'] !== 'admin') {
            // ترقية المستخدم إلى مسؤول
            $update_sql = "UPDATE user SET role = 'admin' WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user_id);
            
            if ($update_stmt->execute()) {
                $_SESSION['success'] = "تم ترقية المستخدم إلى مسؤول بنجاح";
            } else {
                $_SESSION['error'] = "حدث خطأ أثناء ترقية المستخدم: " . $conn->error;
            }
            
            $update_stmt->close();
        } else {
            $_SESSION['error'] = "المستخدم مسؤول بالفعل!";
        }
    } else {
        $_SESSION['error'] = "المستخدم غير موجود!";
    }
    
    $check_stmt->close();
} else {
    $_SESSION['error'] = "لم يتم تحديد مستخدم!";
}

$conn->close();

// إعادة التوجيه إلى لوحة التحكم
header('Location: admin_dashboard.php');
exit();
?>
