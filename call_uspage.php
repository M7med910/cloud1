<?php
session_start();

require 'track-visit.php';

if (isset($_SESSION['user_id'])) {
$host = 'n11111111.mysql.database.azure.com';
$dbname = 'newschema';
$username = 'm';
$password = '11111111nN';
    
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("فشل الاتصال بالقاعدة: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $sql = "SELECT name, email FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $email);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_name = $_POST['name'] ?? '';
    $contact_email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // هنا يمكنك إضافة كود إرسال البريد الإلكتروني أو حفظ الاستفسار في قاعدة البيانات
    $success_message = "شكراً لتواصلك معنا! سنرد على استفسارك في أقرب وقت ممكن.";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>اتصل بنا | منصة المزادات</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icon/favicon-16x16.png">
    <link rel="shortcut icon" href="icon/favicon.ico">
    
    <!-- Meta Tags -->
    <meta name="description" content="اتصل بفريق منصة المزادات - نحن هنا لمساعدتك في أي استفسارات أو مشاكل تواجهك">
    <meta property="og:title" content="اتصل بنا | منصة المزادات">
    <meta property="og:description" content="تواصل مع فريق الدعم الفني لمنصة المزادات">
    <meta property="og:image" content="https://ebidzones.com/icon/icon22new.png">
    <meta property="og:url" content="https://ebidzones.com/contact.php">
    <meta name="twitter:card" content="summary_large_image">
    
    <style>
        :root {
            --primary-color: #064635;
            --secondary-color: #519259;
            --accent-color: #F0BB62;
            --light-color: #F4EEA9;
            --dark-color: #042f2e;
            --text-color: #333;
            --light-text: #f8f9fa;
            --border-radius: 12px;
            --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo {
            height: clamp(30px, 8vw, 40px);
            transition: var(--transition);
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .nav-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .auth-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .btn {
            padding: clamp(0.5rem, 2vw, 0.75rem) clamp(0.75rem, 3vw, 1.5rem);
            border-radius: var(--border-radius);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: clamp(0.8rem, 3vw, 1rem);
            white-space: nowrap;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3e7a4a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-outline {
            background-color: transparent;
            color: white;
            border: 2px solid white;
        }
        
        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            color: var(--dark-color);
        }
        
        .btn-accent:hover {
            background-color: #e0a84d;
            transform: translateY(-2px);
        }
        
        .search-container {
            width: 100%;
        }
        
        .search-input {
            width: 100%;
            padding: clamp(0.5rem, 2vw, 0.75rem) clamp(1rem, 3vw, 1.5rem);
            border-radius: 50px;
            border: none;
            font-size: clamp(0.9rem, 3vw, 1rem);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(81, 146, 89, 0.3);
        }
        
        .welcome-message {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: clamp(0.8rem, 3vw, 1rem);
            color: white;
        }
        
        .welcome-message i {
            color: var(--accent-color);
        }
        
        /* Sections */
        .section {
            padding: clamp(2rem, 6vw, 4rem) clamp(1rem, 3vw, 2rem);
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        .section-title {
            text-align: center;
            font-size: clamp(1.5rem, 5vw, 2rem);
            margin-bottom: clamp(1.5rem, 5vw, 3rem);
            color: var(--primary-color);
            position: relative;
        }
        
        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--accent-color);
            margin: clamp(0.5rem, 2vw, 1rem) auto 0;
            border-radius: 2px;
        }
        
        /* Contact Styles */
        .contact-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .contact-info {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
        }
        
        .contact-info h3 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .contact-info h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .info-icon {
            background-color: var(--light-color);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .info-content h4 {
            color: var(--dark-color);
            margin-bottom: 0.3rem;
        }
        
        .info-content p, .info-content a {
            color: var(--text-color);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .info-content a:hover {
            color: var(--secondary-color);
        }
        
        .contact-form {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(81, 146, 89, 0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            display: none;
        }
        
        .contact-map {
            margin-top: 3rem;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            height: 400px;
        }
        
        .contact-map iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: clamp(2rem, 5vw, 3rem) clamp(1rem, 3vw, 2rem) clamp(1rem, 3vw, 1.5rem);
            margin-top: auto;
        }
        
        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(clamp(150px, 30vw, 200px), 1fr));
            gap: clamp(1rem, 3vw, 2rem);
        }
        
        .footer-col h4 {
            font-size: clamp(1rem, 4vw, 1.25rem);
            margin-bottom: clamp(0.75rem, 3vw, 1.5rem);
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 2px;
            background-color: var(--accent-color);
        }
        
        .footer-col ul {
            list-style: none;
        }
        
        .footer-col ul li {
            margin-bottom: clamp(0.5rem, 2vw, 0.75rem);
        }
        
        .footer-col a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
            font-size: clamp(0.8rem, 3vw, 0.9rem);
        }
        
        .footer-col a:hover {
            color: white;
            transform: translateX(-5px);
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: clamp(0.5rem, 2vw, 1rem);
        }
        
        .social-links a {
            color: white;
            font-size: clamp(1rem, 4vw, 1.25rem);
            transition: var(--transition);
        }
        
        .social-links a:hover {
            color: var(--accent-color);
            transform: translateY(-3px);
        }
        
        .copyright {
            text-align: center;
            margin-top: clamp(1.5rem, 5vw, 3rem);
            padding-top: clamp(0.75rem, 3vw, 1.5rem);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: clamp(0.7rem, 3vw, 0.9rem);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }
        
        /* Responsive Adjustments */
        @media (min-width: 768px) {
            .header-container {
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
            }
            
            .header-top {
                order: 1;
                flex-wrap: nowrap;
            }
            
            .search-container {
                order: 3;
                width: 100%;
                margin-top: 0.5rem;
            }
            
            .auth-buttons {
                gap: 1rem;
                flex-wrap: nowrap;
            }
        }
        
        @media (min-width: 992px) {
            .header-container {
                flex-direction: row;
                flex-wrap: nowrap;
            }
            
            .search-container {
                order: 2;
                width: auto;
                flex-grow: 1;
                margin: 0 2rem;
            }
        }
        
        /* Touch improvements for mobile */
        @media (hover: none) {
            .btn:hover, 
            .footer-col a:hover,
            .social-links a:hover {
                transform: none !important;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="main-header">
    <div class="header-container">
        <div class="header-top">
            <div class="logo-container">
                <img src="logo/png/logo-no-background.png" alt="منصة المزادات" class="logo">
            </div>
            
            <div class="nav-container">
                <nav class="auth-buttons">
                    <?php if (isset($name)): ?>
                        <span class="welcome-message">
                            <i class="fas fa-user-circle"></i>
                            مرحباً، <?php echo htmlspecialchars($name); ?>
                        </span>
                        <a href="profile.php" class="btn btn-outline">
                            <i class="fas fa-user"></i> <span class="btn-text">الملف الشخصي</span>
                        </a>
                        <a href="test2.html" class="btn btn-accent">
                            <i class="fas fa-plus"></i> <span class="btn-text">إضافة مزاد</span>
                        </a>
                        <a href="logout.php" class="btn btn-primary">
                            <i class="fas fa-sign-out-alt"></i> <span class="btn-text">تسجيل خروج</span>
                        </a>
                    <?php else: ?>
                        <a href="OTP.php" class="btn btn-outline">
                            <i class="fas fa-sign-in-alt"></i> <span class="btn-text">تسجيل الدخول</span>
                        </a>
                        <a href="SignUp.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> <span class="btn-text">إنشاء حساب</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        
        <div class="search-container">
            <input type="text" class="search-input" placeholder="ابحث في الموقع...">
        </div>
    </div>
</header>
    
<!-- Contact Section -->
<section class="section animate-fade" style="animation-delay: 0.1s">
    <h2 class="section-title">اتصل بنا</h2>
    
    <div class="contact-container">
        <!-- Contact Information -->
        <div class="contact-info animate-fade" style="animation-delay: 0.2s">
            <h3><i class="fas fa-info-circle"></i> معلومات التواصل</h3>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <h4>العنوان</h4>
                    <p>المملكة العربية السعودية، الرياض، حي المروج، شارع الملك فهد</p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <h4>الهاتف</h4>
                    <p><a href="tel:+966112345678">+966 11 234 5678</a></p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <h4>البريد الإلكتروني</h4>
                    <p><a href="mailto:info@ebidzones.com">info@ebidzones.com</a></p>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h4>ساعات العمل</h4>
                    <p>الأحد - الخميس: 8 صباحاً - 5 مساءً</p>
                    <p>الجمعة - السبت: إجازة</p>
                </div>
            </div>
            
            <div class="social-links" style="justify-content: flex-start; margin-top: 2rem;">
                <a href="#" aria-label="تويتر"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="فيسبوك"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="إنستغرام"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="واتساب"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="contact-form animate-fade" style="animation-delay: 0.3s">
            <h3><i class="fas fa-paper-plane"></i> أرسل رسالة</h3>
            
            <?php if (isset($success_message)): ?>
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
                <script>
                    document.getElementById('successMessage').style.display = 'block';
                </script>
            <?php endif; ?>
            
            <form action="contact.php" method="POST">
                <div class="form-group">
                    <label for="name">الاسم الكامل</label>
                    <input type="text" id="name" name="name" class="form-control" required 
                           value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="form-control" required
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="subject">الموضوع</label>
                    <select id="subject" name="subject" class="form-control" required>
                        <option value="">اختر موضوع الرسالة</option>
                        <option value="استفسار عام">استفسار عام</option>
                        <option value="مشكلة تقنية">مشكلة تقنية</option>
                        <option value="اقتراح أو شكوى">اقتراح أو شكوى</option>
                        <option value="التعاون والدعاية">التعاون والدعاية</option>
                        <option value="أخرى">أخرى</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">الرسالة</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                    <i class="fas fa-paper-plane"></i> إرسال الرسالة
                </button>
            </form>
        </div>
    </div>
    
</section>
    
<!-- Footer -->
<footer class="main-footer animate-fade" style="animation-delay: 0.5s">
    <div class="footer-container">
        <div class="footer-col">
            <img src="logo/png/logo-no-background.png" alt="منصة المزادات" style="height: clamp(30px, 8vw, 40px); margin-bottom: 1rem;">
            <p style="font-size: clamp(0.8rem, 3vw, 0.9rem);">منصة المزادات الرائدة لبيع وشراء كل شيء بأفضل الأسعار</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        
        <div class="footer-col">
            <h4>روابط سريعة</h4>
            <ul>
                <li><a href="index.php">الصفحة الرئيسية</a></li>
                <li><a href="CarsPage.php">مزادات السيارات</a></li>
                <li><a href="RealEstatePage.php">مزادات العقارات</a></li>
                <li><a href="ElectronicsPage.php">مزادات الإلكترونيات</a></li>
            </ul>
        </div>
        
        <div class="footer-col">
            <h4>حسابي</h4>
            <ul>
                <li><a href="profile.php">الملف الشخصي</a></li>
                <li><a href="#">مزاداتي</a></li>
                <li><a href="#">المزايدات</a></li>
                <li><a href="#">الإشعارات</a></li>
            </ul>
        </div>
        
        <div class="footer-col">
            <h4>الدعم</h4>
            <ul>
                <li><a href="quationspage.php">الأسئلة الشائعة</a></li>
                <li><a href="call_uspage.php">اتصل بنا</a></li>
                <li><a href="privcypage_php">سياسة الخصوصية</a></li>
                <li><a href="#">الشروط والأحكام</a></li>
            </ul>
        </div>
    </div>
    
    <div class="copyright">
        &copy; <?php echo date('Y'); ?> منصة المزادات. جميع الحقوق محفوظة.
    </div>
</footer>
    
<script>
    // Simple animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
        const animateElements = document.querySelectorAll('.animate-fade');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    entry.target.style.opacity = '1';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        animateElements.forEach(el => {
            observer.observe(el);
        });
        
        // Improve touch experience on mobile
        const buttons = document.querySelectorAll('.btn');
        
        buttons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            button.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
    });
</script>
</body>
</html>
