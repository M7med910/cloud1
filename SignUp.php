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

// تسجيل المستخدم
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // التحقق من وجود البريد الإلكتروني في قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // إذا كان البريد الإلكتروني موجودًا مسبقًا
        echo "<p id='email-error' class='alert alert-danger'>البريد الإلكتروني موجود مسبقًا. يرجى استخدام بريد إلكتروني آخر.</p>";
    } else {
        // التحقق من طول كلمة المرور
        if (strlen($password) < 8 || strlen($password) > 16) {
            echo "<p id='password-error' class='alert alert-danger'>يجب أن تكون كلمة المرور بين 8 و 16 حرفًا.</p>";
        } else {
            // تشفير كلمة المرور
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // إدخال البيانات في قاعدة البيانات
            $stmt = $conn->prepare("INSERT INTO user (name, email, password, address, phone_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $password_hash, $address, $phone);
            if ($stmt->execute()) {
                echo "<p id='success-msg' class='alert alert-success'>تم التسجيل بنجاح!</p>";
            } else {
                echo "<p id='error-msg' class='alert alert-danger'>خطأ في التسجيل!</p>";
            }
        }
    }
    $stmt->close();
}

// تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT user_id, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();
    
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;
        echo "<p id='login-success' class='alert alert-success'>تم تسجيل الدخول بنجاح!</p>";
    } else {
        echo "<p id='login-error' class='alert alert-danger'>البريد الإلكتروني أو كلمة المرور غير صحيحة!</p>";
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
    <title>تسجيل حساب جديد</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600&family=Tajawal:wght@300;400;600&family=Droid+Arabic+Kufi:wght@400;700&family=Amiri:wght@400;700&family=Lateef:wght@400&family=Reem+Kufi:wght@400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background: linear-gradient(135deg, #f4f4f9 0%, #e6f0f9 100%);
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
            max-width: 600px;
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
            font-family: 'Tajawal', sans-serif;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #2c6b4f;
            margin-bottom: 8px;
            font-family: 'Tajawal', sans-serif;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            font-family: 'Cairo', sans-serif;
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
            font-family: 'Tajawal', sans-serif;
        }

        .btn-primary:hover {
            background-color: #1e4f3a;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            opacity: 1;
            transition: opacity 1s ease-in-out;
        }

        .alert.hide {
            opacity: 0;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #2c6b4f;
            font-size: 0.9rem;
            margin-top: 30px;
            width: 100%;
        }

        #password-error {
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
            margin-top: -15px;
            margin-bottom: 15px;
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

    <!-- نموذج تسجيل مستخدم -->
    <div class="login-container">
        <div class="card shadow-lg">
            <div class="card-header">
                <h2>تسجيل حساب جديد</h2>
            </div>
            <div class="card-body">
                <form method="post" onsubmit="return validatePassword();">
                    <div class="mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="أدخل اسمك" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمة المرور (8-16 حرفًا)" required>
                        <div id="password-error" class="text-danger" style="display:none;">يجب أن تكون كلمة المرور بين 8 و 16 حرفًا.</div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">العنوان</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="أدخل عنوانك">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="أدخل رقم هاتفك">
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">تسجيل</button>
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
        // التحقق من كلمة المرور
        function validatePassword() {
            var password = document.getElementById('password').value;
            var error = document.getElementById('password-error');
            if (password.length < 8 || password.length > 16) {
                error.style.display = 'block';
                return false;  // منع إرسال النموذج
            } else {
                error.style.display = 'none';
                return true;  // السماح بإرسال النموذج
            }
        }

        // إخفاء الرسائل بعد فترة معينة
        window.onload = function() {
            // إضافة السنة الحالية تلقائياً
            document.getElementById('year').textContent = new Date().getFullYear();
            
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    alert.classList.add('hide'); // إضافة الكلاس لإخفاء الرسالة
                });
            }, 5000);  // إخفاء الرسالة بعد 5 ثواني
        }
    </script>
</body>
</html>