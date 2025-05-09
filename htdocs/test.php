<?php
$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

session_start();
if (!isset($_SESSION['user_id'])) {
    die("يجب تسجيل الدخول أولاً.");
}
$seller_user_id = $_SESSION['user_id'];

$name = $_POST['name'] ?? null;
$description = $_POST['description'] ?? null;
$category = $_POST['category'] ?? null;
$starting_price = $_POST['starting_price'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$image = $_FILES['image'] ?? null;

if (!$name || !$description || !$category || !$starting_price || !$start_date || !$end_date || !$image) {
    die("⚠️ جميع الحقول مطلوبة، تأكد من تعبئتها.");
}

// تحقق من نوع الملف
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($image['type'], $allowed_types)) {
    die("⚠️ نوع الصورة غير مدعوم.");
}

if ($image['size'] > 5 * 1024 * 1024) {
    die("⚠️ حجم الصورة كبير جداً (الحد 5MB).");
}

// تجهيز اسم فريد للملف
$image_name = uniqid('img_') . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
$image_path = 'uploads/' . $image_name;

// رفع الصورة إلى المجلد
if (!move_uploaded_file($image['tmp_name'], $image_path)) {
    die("❌ فشل في رفع الصورة.");
}

try {
    $pdo->beginTransaction();

    // تخزين اسم الصورة في جدول product
    $stmt1 = $pdo->prepare("INSERT INTO product (name, description, category, seller_user_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt1->execute([$name, $description, $category, $seller_user_id, $image_name]);
    $product_id = $pdo->lastInsertId();

    // إدخال المزاد
    $stmt2 = $pdo->prepare("INSERT INTO auction (start_date, end_date, starting_price, product_id, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt2->execute([$start_date, $end_date, $starting_price, $product_id]);

    $pdo->commit();

    header("Location: index.php");
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "❌ حدث خطأ أثناء إضافة المزاد: " . $e->getMessage();
}
?>
