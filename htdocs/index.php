<?php
session_start();

require 'track-visit.php';

if (isset($_SESSION['user_id'])) {
    $host = 'sql200.infinityfree.com';
    $dbname = 'if0_38543174_my_db';
    $username = 'if0_38543174';
    $password = 'MDH6WuEkvPyL';
    
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("فشل الاتصال بالقاعدة: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");


    $user_id = $_SESSION['user_id'];
    $sql = "SELECT name FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>منصة المزادات - بيع وشراء كل شيء</title>
    
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
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(4, 38, 46, 0.8), rgba(4, 38, 46, 0.8)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: clamp(3rem, 10vw, 5rem) clamp(1rem, 5vw, 2rem);
            text-align: center;
        }
        
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .hero-title {
            font-size: clamp(1.8rem, 6vw, 2.5rem);
            margin-bottom: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }
        
        .hero-subtitle {
            font-size: clamp(1rem, 3.5vw, 1.25rem);
            margin-bottom: clamp(1.5rem, 5vw, 2rem);
            opacity: 0.9;
            line-height: 1.5;
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
        
        /* Category Grid */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(clamp(200px, 40vw, 250px), 1fr));
            gap: clamp(1rem, 3vw, 2rem);
            margin-top: clamp(1rem, 3vw, 2rem);
            justify-content: center;
        }
        
        .category-card {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            text-align: center;
        }
        
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }
        
        .category-card a {
            text-decoration: none;
            color: inherit;
            display: block;
            padding: clamp(1.5rem, 5vw, 2rem) clamp(0.75rem, 3vw, 1rem);
        }
        
        .category-icon {
            font-size: clamp(2rem, 7vw, 3rem);
            color: var(--secondary-color);
            margin-bottom: clamp(0.5rem, 2vw, 1rem);
            transition: var(--transition);
        }
        
        .category-card:hover .category-icon {
            color: var(--primary-color);
            transform: scale(1.1);
        }
        
        .category-title {
            font-size: clamp(1.2rem, 4vw, 1.5rem);
            font-weight: 600;
            margin-bottom: clamp(0.25rem, 1vw, 0.5rem);
        }
        
        .category-desc {
            color: #666;
            font-size: clamp(0.8rem, 3vw, 0.9rem);
            line-height: 1.5;
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
            .category-card:hover, 
            .auction-card:hover,
            .footer-col a:hover,
            .social-links a:hover {
                transform: none !important;
            }
            
            .category-card:hover,
            .auction-card:hover {
                box-shadow: var(--box-shadow) !important;
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
            <input type="text" class="search-input" placeholder="ابحث عن مزادات، سيارات، عقارات...">
        </div>
    </div>
</header>
    
<!-- Hero Section -->
<section class="hero-section animate-fade" style="animation-delay: 0.1s">
    <div class="hero-content">
        <h1 class="hero-title">اكتشف أفضل الصفقات في منصة المزادات</h1>
        <p class="hero-subtitle">بيع واشترِ السيارات، العقارات، الإلكترونيات والمزيد بأسعار تنافسية</p>
        <a href="#categories" class="btn btn-accent" style="padding: 0.75rem 1.5rem;">
            <i class="fas fa-search"></i> تصفح الفئات
        </a>
    </div>
</section>
    
<!-- Categories Section -->
<section id="categories" class="section animate-fade" style="animation-delay: 0.2s">
    <h2 class="section-title">تصفح حسب الفئة</h2>
    <div class="category-grid">
        <div class="category-card animate-fade" style="animation-delay: 0.3s">
            <a href="CarsPage.php">
                <i class="fas fa-car category-icon"></i>
                <h3 class="category-title">السيارات</h3>
                <p class="category-desc">اكتشف أحدث الموديلات بأفضل الأسعار</p>
            </a>
        </div>
        
        <div class="category-card animate-fade" style="animation-delay: 0.4s">
            <a href="RealEstatePage.php">
                <i class="fas fa-home category-icon"></i>
                <h3 class="category-title">العقارات</h3>
                <p class="category-desc">شقق، فلل، أراضي واستثمارات</p>
            </a>
        </div>
        
        <div class="category-card animate-fade" style="animation-delay: 0.5s">
            <a href="ElectronicsPage.php">
                <i class="fas fa-laptop category-icon"></i>
                <h3 class="category-title">إلكترونيات</h3>
                <p class="category-desc">أحدث الأجهزة بأقل الأسعار</p>
            </a>
        </div>
    </div>
</section>
    
<!-- How It Works Section -->
<section class="section animate-fade" style="animation-delay: 0.4s">
    <h2 class="section-title">كيف تعمل المنصة؟</h2>
    <div class="category-grid" style="grid-template-columns: repeat(auto-fit, minmax(clamp(250px, 45vw, 300px), 1fr));">
        <div class="category-card">
            <i class="fas fa-user-plus category-icon" style="color: #4e73df;"></i>
            <h3 class="category-title">1. إنشاء حساب</h3>
            <p class="category-desc">سجل في المنصة بخطوات بسيطة وسريعة</p>
        </div>
        
        <div class="category-card">
            <i class="fas fa-search-dollar category-icon" style="color: #1cc88a;"></i>
            <h3 class="category-title">2. ابحث عن المنتج</h3>
            <p class="category-desc">تصفح آلاف المنتجات في مختلف الفئات</p>
        </div>
        
        <div class="category-card">
            <i class="fas fa-gavel category-icon" style="color: #f6c23e;"></i>
            <h3 class="category-title">3. شارك في المزاد</h3>
            <p class="category-desc">قم بالمزايدة على المنتجات التي تريدها</p>
        </div>
        
        <div class="category-card">
            <i class="fas fa-trophy category-icon" style="color: #e74a3b;"></i>
            <h3 class="category-title">4. اربح المزاد</h3>
            <p class="category-desc">احصل على المنتج بأفضل سعر</p>
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
                <li><a href="#">الصفحة الرئيسية</a></li>
                <li><a href="#categories">الفئات</a></li>
                <li><a href="#">المزادات المميزة</a></li>
                <li><a href="#">كيفية البيع</a></li>
                <li><a href="#">كيفية الشراء</a></li>
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
        const buttons = document.querySelectorAll('.btn, .category-card');
        
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