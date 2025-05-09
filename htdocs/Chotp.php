<?php
session_start();

$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';
$conn = new mysqli($host, $username, $password, $dbname);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";
$otp_success = false;

// التحقق من انتهاء صلاحية OTP وحذفه من قاعدة البيانات بعد 60 ثانية
if (isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time']) > 60) {
    // تم انتهاء الوقت، نزيل الرمز من قاعدة البيانات
    $stmt = $conn->prepare("UPDATE user SET otp = NULL WHERE user_id = ?");
    if ($stmt === false) {
        die("Error preparing the query: " . $conn->error);
    }
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $message = "لقد انتهت صلاحية رمز التحقق. الرجاء طلب رمز جديد.";
}

// التحقق من رمز OTP المدخل فقط إذا لم تنتهِ الصلاحية
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify_otp'])) {
    // التحقق من صلاحية الرمز أولاً
    if (isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time']) > 60) {
        $message = "لقد انتهت صلاحية رمز التحقق. الرجاء إعادة إرسال رمز جديد.";
    } else {
        if (isset($_SESSION['otp']) && $_POST['otp'] == $_SESSION['otp']) {
            $otp_success = true;
            unset($_SESSION['otp']);
            unset($_SESSION['otp_sent']);
            unset($_SESSION['otp_time']);
            $message = "تم التحقق بنجاح!";

            // تخزين اسم المستخدم في الجلسة بعد التحقق بنجاح
            $_SESSION['name'] = "اسم المستخدم";  // استبدل "اسم المستخدم" بالاسم الفعلي من قاعدة البيانات

            // إضافة إعادة التوجيه إلى الصفحة الرئيسية بعد التحقق الناجح
            header("Location: index.php");  // التوجيه إلى الصفحة الرئيسية
            exit();  // تأكد من إيقاف باقي السكربت بعد التوجيه
        } else {
            $message = "رمز التحقق غير صحيح!";
        }
    }
}

// إعادة إرسال رمز التحقق بعد انتهاء صلاحية الرمز
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resend_otp'])) {
    $email = $_SESSION['otp_email'];  // استخدم البريد الإلكتروني المخزن في الجلسة
    $otp = rand(100000, 999999);  // توليد رمز OTP جديد
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();  // تحديث الوقت
    $stmt = $conn->prepare("UPDATE user SET otp = ? WHERE email = ?");
    if ($stmt === false) {
        die("Error preparing the query: " . $conn->error);
    }
    $stmt->bind_param("is", $otp, $email);  // استخدام "email"
    $stmt->execute();

    // إرسال رمز OTP إلى البريد الإلكتروني
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'waleedksa130@gmail.com';
        $mail->Password = 'mtblkbpekpdmodmk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('waleedksa130@gmail.com', 'E-BidZone');
        $mail->addAddress($email);
        $mail->Subject = 'رمز التحقق OTP';
        $mail->Body = "رمز التحقق الخاص بك هو: $otp";

        if ($mail->send()) {
            $message = "تم إرسال رمز التحقق إلى بريدك الإلكتروني.";
        }
    } catch (Exception $e) {
        $message = "خطأ في إرسال البريد الإلكتروني: {$mail->ErrorInfo}";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد رمز التحقق</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icon/favicon-16x16.png">
    <link rel="shortcut icon" href="icon/favicon.ico">
    
    <!-- Meta Tags -->
    <meta name="description" content="منصة المزادات الرائدة لبيع وشراء السيارات والعقارات والإلكترونيات بسهولة وأمان. سجل الآن وابدأ المزايدة!">
    <meta property="og:title" content="منصة المزادات">
    <meta property="og:description" content="المنصة الأفضل للمزادات الإلكترونية في الوطن العربي">
    <meta property="og:image" content="https://ebidzones.com/icon/icon22new.png">
    <meta property="og:url" content="https://ebidzones.com/">
    <meta name="twitter:card" content="summary_large_image">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background-color: #f4f4f9;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            flex-direction: column;
        }
        .otp-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
            margin-top: 10px;
        }
        h2 {
            font-weight: 600;
            color: #2c6b4f;
        }
        .form-control {
            border-radius: 12px;
        }
        .btn-primary {
            background-color: #2c6b4f;
            border-radius: 12px;
            width: 100%;
        }
        .timer {
            font-size: 20px;
            color: red;
        }
        .alert-danger {
            color: red;
            font-weight: bold;
            border: 1px solid red;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f8d7da;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>إدخال رمز التحقق</h2>
        <?php if ($message) { 
            if (strpos($message, 'انتهت صلاحية') !== false) {
                echo "<p class='alert alert-danger'>$message</p>";
            } else {
                echo "<p class='alert alert-info'>$message</p>";
            }
        } ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="otp" class="form-label">رمز التحقق</label>
                <input type="text" class="form-control" name="otp" required>
            </div>
            <button type="submit" name="verify_otp" class="btn btn-primary">تحقق</button>
        </form>

        <!-- عداد الوقت -->
        <div id="timer" class="timer"></div>

        <?php if (isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time']) > 60) { ?>
            <form method="POST" action="">
                <button type="submit" name="resend_otp" class="btn btn-primary">إعادة إرسال رمز التحقق</button>
            </form>
        <?php } ?>
    </div>

    <script>
        <?php if (isset($_SESSION['otp_time'])): ?>
            var otpTimeSent = <?php echo $_SESSION['otp_time']; ?>;
            var expirationTime = otpTimeSent + 60; // صلاحية 60 ثانية
            var currentTime = Math.floor(Date.now() / 1000); // الوقت الحالي بالثواني
            var remainingTime = expirationTime - currentTime;

            // تحديث العدّاد في الصفحة
            var timerElement = document.getElementById('timer');
            var timerInterval = setInterval(function() {
                remainingTime--;
                var seconds = remainingTime % 60;

                if (remainingTime > 0) {
                    timerElement.innerHTML = `الوقت المتبقي: ${seconds} ثانية`;
                } else {
                    clearInterval(timerInterval);
                    timerElement.innerHTML = 'انتهت صلاحية رمز التحقق';
                }
            }, 1000);
        <?php endif; ?>
    </script>
</body>
</html>
