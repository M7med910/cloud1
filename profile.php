<?php
session_start();

$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ فشل الاتصال: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("❌ خطأ: لا يوجد user_id في الجلسة.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, email, phone_number, profile_pic FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone_number, $profile_pic);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الملف الشخصي - <?php echo htmlspecialchars($name); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="الصفحة الشخصية للمستخدم تحتوي على معلومات الحساب والصورة الشخصية">
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
        :root {
            --primary-color: #1e4b3a;
            --secondary-color: #2c6b4f;
            --accent-color: #3a8c6e;
            --danger-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --text-color: #333;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease-in-out;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ✅ تحسينات الهيدر */
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
        }
        
        header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* ✅ تحسينات مسار التنقل */
        .breadcrumb-container {
            background-color: var(--light-color);
            padding: 0.5rem 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            margin: 0 auto;
            max-width: 1200px;
        }
        
        .breadcrumb li {
            margin: 0 0.25rem;
            display: flex;
            align-items: center;
        }
        
        .breadcrumb li::after {
            content: "\f054";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin: 0 0.5rem;
            color: #888;
            font-size: 0.7rem;
        }
        
        .breadcrumb li:last-child::after {
            content: "";
        }
        
        .breadcrumb a {
            text-decoration: none;
            color: var(--secondary-color);
            font-size: 0.9rem;
            transition: var(--transition);
        }
        
        .breadcrumb a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* ✅ محتوى الصفحة الرئيسي */
        .container {
            flex: 1;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ✅ بطاقة الملف الشخصي */
        .profile-card {
            background: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 500px;
            text-align: center;
            border-top: 4px solid var(--secondary-color);
            transition: var(--transition);
            animation: fadeInUp 0.6s ease-out;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        .profile-pic-container {
            position: relative;
            margin: 0 auto 1.5rem;
            width: 130px;
            height: 130px;
        }
        
        .profile-card img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }
        
        .profile-card img:hover {
            transform: scale(1.05);
        }
        
        .profile-card h2 {
            margin: 1rem 0;
            color: var(--secondary-color);
            font-size: 1.6rem;
            font-weight: 700;
        }

        /* ✅ قسم المعلومات */
        .info {
            margin-top: 1.5rem;
            text-align: right;
            background: var(--light-color);
            padding: 1.5rem;
            border-radius: var(--border-radius);
        }
        
        .info p {
            margin: 1rem 0;
            font-size: 1rem;
            color: var(--text-color);
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        
        .info i {
            color: var(--secondary-color);
            margin-left: 0.5rem;
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        /* ✅ أزرار التحكم */
        .buttons {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }
        
        .btn {
            flex: 1;
            text-decoration: none;
            padding: 0.8rem 0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            transition: var(--transition);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn i {
            font-size: 0.9rem;
        }
        
        .btn-back {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-back:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .btn-logout {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-logout:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        /* ✅ الفوتر */
        footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            padding: 1.2rem 0;
            margin-top: auto;
            font-size: 0.9rem;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        }

        /* ✅ تأثيرات للصورة عند عدم وجودها */
        .no-image {
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
        }

        /* ✅ تحسينات للجوال */
        @media (max-width: 768px) {
            .profile-card {
                padding: 1.5rem;
            }
            
            .profile-pic-container {
                width: 110px;
                height: 110px;
            }
            
            .info {
                padding: 1rem;
            }
            
            .buttons {
                flex-direction: column;
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            header h1 {
                font-size: 1.4rem;
            }
            
            .profile-card h2 {
                font-size: 1.3rem;
            }
            
            .info p {
                font-size: 0.9rem;
            }
            
            .btn {
                font-size: 0.9rem;
                padding: 0.7rem 0;
            }
        }
    </style>
</head>
<body>

<!-- ✅ الهيدر -->
<header class="animate__animated animate__fadeInDown">
    <h1>الملف الشخصي</h1>
</header>

<!-- ✅ مسار التنقل -->
<div class="breadcrumb-container">
    <ul id="breadcrumb" class="breadcrumb"></ul>
</div>

<!-- ✅ محتوى الصفحة -->
<main class="container">
    <div class="profile-card">
        <div class="profile-pic-container">
            <?php if(!empty($profile_pic)): ?>
                <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="الصورة الشخصية" class="animate__animated animate__fadeIn">
            <?php else: ?>
                <div class="no-image animate__animated animate__fadeIn">
                    <?php echo mb_substr($name, 0, 1, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <h2 class="animate__animated animate__fadeIn"><?php echo htmlspecialchars($name); ?></h2>

        <div class="info animate__animated animate__fadeInUp">
            <p>
                <i class="fas fa-envelope"></i>
                <?php echo htmlspecialchars($email); ?>
            </p>
            <p>
                <i class="fas fa-phone"></i>
                <?php echo htmlspecialchars($phone_number); ?>
            </p>
        </div>

        <div class="buttons">
            <a href="index.php" class="btn btn-back animate__animated animate__fadeInLeft">
                <i class="fas fa-arrow-right"></i>
                العودة للرئيسية
            </a>
            <a href="logout.php" class="btn btn-logout animate__animated animate__fadeInRight">
                <i class="fas fa-sign-out-alt"></i>
                تسجيل الخروج
            </a>
        </div>
    </div>
</main>

<!-- ✅ الفوتر -->
<footer class="animate__animated animate__fadeInUp">
    &copy; <?php echo date('Y'); ?> جميع الحقوق محفوظة لموقعنا
</footer>

<script>
    // تحسين مسار التنقل
    document.addEventListener('DOMContentLoaded', function() {
        const breadcrumb = document.getElementById('breadcrumb');
        const pathArray = window.location.pathname.split('/').filter(Boolean);
        let path = '/';
        
        // إضافة الرئيسية
        const home = document.createElement('li');
        home.innerHTML = '<a href="/"><i class="fas fa-home"></i> الرئيسية</a>';
        breadcrumb.appendChild(home);
        
        // إضافة الأجزاء الأخرى من المسار
        pathArray.forEach((segment, index) => {
            path += segment + '/';
            const li = document.createElement('li');
            
            if (index === pathArray.length - 1) {
                const label = document.title || decodeURIComponent(segment.replace(/-/g, ' '));
                li.innerHTML = `<a href="${path}" aria-current="page">${label}</a>`;
            } else {
                const label = decodeURIComponent(segment.replace(/-/g, ' '));
                li.innerHTML = `<a href="${path}">${label}</a>`;
            }
            
            breadcrumb.appendChild(li);
        });
    });
</script>

</body>
</html>