<?php
include 'dp_config.php';  // تضمين ملف الاتصال

// اختبار الاتصال
try {
    echo "تم الاتصال بقاعدة البيانات بنجاح!";
} catch (PDOException $e) {
    echo "فشل الاتصال: " . $e->getMessage();
}
?>
