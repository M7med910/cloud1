<?php
session_start(); // بدء الجلسة

// تدمير الجلسة لتسجيل الخروج
session_unset();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول أو الصفحة الرئيسية
header("Location: OTP.php");
exit();
?>
<?php
session_start(); // بدء الجلسة

// تدمير الجلسة لتسجيل الخروج
session_unset();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول أو الصفحة الرئيسية
header("Location: OTP.php");
exit();
?>
