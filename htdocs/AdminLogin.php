<?php
session_start();
$host = 'sql200.infinityfree.com';  // استخدام hostname الصحيح
$dbname = 'if0_38543174_my_db';    // اسم قاعدة البيانات التي قمت بإنشائها
$username = 'if0_38543174';         // اسم المستخدم لقاعدة البيانات
$password = 'MDH6WuEkvPyL';        // كلمة المرور لقاعدة البيانات

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// تسجيل دخول الأدمن
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // استعلام للتحقق من بيانات الأدمن
    $stmt = $conn->prepare("SELECT user_id, password, role FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password, $role);
    $stmt->fetch();

    // التحقق من صحة البيانات
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password) && $role == 'admin') {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role; // تخزين الدور في الجلسة

        // التوجيه إلى لوحة التحكم الخاصة بالأدمن
        header("Location: admin_dashboard.php");
        exit(); // تأكد من عدم استمرار التنفيذ بعد التوجيه
    } else {
        echo "<p class='alert alert-danger'>البريد الإلكتروني أو كلمة المرور غير صحيحة أو ليس لديك صلاحية أدمن!</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600&family=Tajawal:wght@300;400;600&family=Droid+Arabic+Kufi:wght@400;700&family=Amiri:wght@400;700&family=Lateef:wght@400&family=Reem+Kufi:wght@400&display=swap" rel="stylesheet">
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
            background: linear-gradient(135deg, rgba(6, 70, 53, 0.1), rgba(4, 47, 46, 0.1));
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #2c6b4f;
            color: white;
            text-align: center;
            padding: 20px;
            border-bottom: none;
        }

        .card-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #2c6b4f;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #2c6b4f;
            box-shadow: 0 0 0 0.25rem rgba(44, 107, 79, 0.25);
        }

        .btn-primary {
            background-color: #2c6b4f;
            border: none;
            font-weight: 600;
            padding: 12px;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #1e4f3a;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #2c6b4f;
            font-size: 0.9rem;
            margin-top: 30px;
            width: 100%;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 20px;
            }
            
            .card-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- نموذج تسجيل دخول الأدمن -->
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <h2>تسجيل دخول الأدمن</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمة المرور" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>

    <!-- تذييل الصفحة -->
    <footer>
        <p>جميع الحقوق محفوظة &copy; <span id="year"></span></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // إضافة السنة الحالية تلقائياً
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>