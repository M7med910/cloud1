<?php
// بدء الجلسة
session_start();

// الاتصال بقاعدة البيانات (تأكد من أنك قد قمت بإنشاء الاتصال بنجاح في ملف آخر مثل config.php)
$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';
$conn = new mysqli($host, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من أن المستخدم هو المسؤول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // إعادة التوجيه إلى صفحة الدخول إذا لم يكن المستخدم مسؤول
    exit();
}

// التحقق من وجود معرّف المستخدم
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // التحقق إذا كان المستخدم الحالي ليس هو المسؤول
    if ($user_id != $_SESSION['user_id']) {  // التأكد من عدم سحب الترقية للمسؤول نفسه
        // تحديث دور المستخدم إلى "user"
        $sql = "UPDATE user SET role = 'user' WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            // إعادة التوجيه إلى لوحة التحكم بعد نجاح العملية
            header("Location: admin_dashboard.php?message=تم سحب الترقية بنجاح");
        } else {
            // في حال حدوث خطأ في التنفيذ
            echo "حدث خطأ أثناء سحب الترقية.";
        }
    } else {
        echo "لا يمكنك سحب الترقية من نفسك.";
    }
} else {
    echo "معرّف المستخدم مفقود.";
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();

?>
