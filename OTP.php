<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .login-container, .otp-container {
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

        .otp-container {
            display: <?php echo $show_otp_field ? 'block' : 'none'; ?>;
        }

        .login-container {
            display: <?php echo !$show_otp_field ? 'block' : 'none'; ?>;
        }

        h2 {
            font-weight: 600;
            color: #2c6b4f;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            outline: none;
            box-shadow: none;
            border: 1px solid #2c6b4f;
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
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .forgot-password {
            display: block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-container {
            display: block;
            margin-top: 20px;
        }

        .btn-container .btn {
            margin-top: 10px;
        }

        .admin-login-btn {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>تسجيل الدخول</h2>
        
        <?php if (!empty($message)) { echo "<p class='alert alert-danger'>$message</p>"; } ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            
            <div class="btn-container">
                <button type="submit" name="login" class="btn btn-primary">تسجيل الدخول</button>
                <a href="AdminLogin.php" class="btn admin-login-btn">تسجيل دخول المسؤولين</a>
            </div>
        </form>
        <a href="SignUp.php" class="forgot-password">حساب جديد</a>
        <a href="reset-password.php" class="forgot-password">نسيت كلمة المرور؟</a>
    </div>
</body>
</html>
