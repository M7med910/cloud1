<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// تعريف بيانات الاتصال
$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

// تحقق من الجلسة
if (isset($_SESSION['user_id'])) {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("فشل الاتصال بالقاعدة: " . $conn->connect_error);
    }

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

// اتصال PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

$stmt = $pdo->prepare("
    SELECT a.auction_id, a.starting_price, p.name AS product_name, p.category, p.image
    FROM auction a
    JOIN product p ON a.product_id = p.product_id
    WHERE p.category = 'إلكترونيات'   
    LIMIT 10  -- لتقليل عدد النتائج
");
$stmt->execute();
$auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM product WHERE category = 'إلكترونيات'");
$stmt->execute();
$product = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>مزاد الإلكترونيات | منصة المزادات</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icon/favicon-16x16.png">
    <link rel="shortcut icon" href="icon/favicon.ico">
    
    <!-- Meta Tags -->
    <meta name="description" content="منصة المزادات الرائدة لبيع وشراء الأجهزة الإلكترونية بسهولة وأمان. سجل الآن وابدأ المزايدة!">
    <meta property="og:title" content="مزاد الإلكترونيات | منصة المزادات">
    <meta property="og:description" content="المنصة الأفضل لمزادات الأجهزة الإلكترونية في الوطن العربي">
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
        
        /* Auction Grid */
        .auctions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(clamp(280px, 40vw, 350px), 1fr));
            gap: clamp(1.5rem, 3vw, 2rem);
            margin-top: clamp(1rem, 3vw, 2rem);
            justify-content: center;
        }
        
        .auction-card {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            text-align: center;
        }
        
        .auction-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }
        
        .auction-image-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        
        .auction-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .auction-card:hover .auction-image {
            transform: scale(1.05);
        }
        
        .no-image {
            width: 100%;
            height: 100%;
            background-color: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            font-size: 16px;
        }
        
        .auction-details {
            padding: 1.5rem;
        }
        
        .auction-title {
            font-size: clamp(1.2rem, 4vw, 1.5rem);
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            font-weight: 700;
        }
        
        .auction-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            color: #666;
            font-size: clamp(0.9rem, 3vw, 1rem);
        }
        
        .auction-price {
            color: var(--secondary-color);
            font-weight: bold;
            font-size: clamp(1rem, 3vw, 1.2rem);
        }
        
        .auction-button {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
            font-size: clamp(0.9rem, 3vw, 1rem);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .auction-button:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            padding: 1rem;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            max-width: 1400px;
            margin: 1.5rem auto 0;
            gap: 0.5rem;
        }
        
        .breadcrumb li {
            color: var(--text-color);
        }
        
        .breadcrumb li::after {
            content: ">";
            margin: 0 0.5rem;
            color: #888;
        }
        
        .breadcrumb li:last-child::after {
            content: "";
        }
        
        .breadcrumb a {
            color: var(--secondary-color);
            font-weight: 500;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
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
            .auction-card:hover,
            .footer-col a:hover,
            .social-links a:hover {
                transform: none !important;
            }
            
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
            <input type="text" class="search-input" placeholder="ابحث عن مزادات الإلكترونيات...">
        </div>
    </div>
</header>

<!-- Breadcrumb -->
<ul id="breadcrumb" class="breadcrumb animate-fade" style="animation-delay: 0.1s"></ul>
    
<!-- Auctions Section -->
<section id="auctions" class="section animate-fade" style="animation-delay: 0.2s">
    <h2 class="section-title">مزادات الإلكترونيات</h2>
    
    <div class="auctions-grid">
        <?php foreach ($auctions as $auction): ?>
            <div class="auction-card animate-fade" style="animation-delay: 0.3s">
                <div class="auction-image-container">
                    <?php if (!empty($auction['image']) && file_exists('uploads/' . $auction['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($auction['image']) ?>" alt="<?= htmlspecialchars($auction['product_name']) ?>" class="auction-image">
                    <?php else: ?>
                        <div class="no-image">
                            <i class="fas fa-laptop" style="font-size: 40px; color: #999;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="auction-details">
                    <h3 class="auction-title"><?= htmlspecialchars($auction['product_name']) ?></h3>
                    <div class="auction-meta">
                        <span><i class="fas fa-microchip"></i> <?= htmlspecialchars($auction['category']) ?></span>
                        <span class="auction-price">
                            <?= number_format($auction['starting_price']) ?> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 10">
                                <g style="font-size:8px;line-height:0;font-family:Andika;-inkscape-font-specification:Andika;opacity:1;vector-effect:none;fill:#097969;stroke-width:.15;stroke-linecap:square;stroke-linejoin:bevel;paint-order:markers stroke fill">
                                    <path d="m6.836 8.468 2.595-.55q-.045.454-.236.876l-2.589.55q.04-.46.23-.876Zm2.359-1.325-2.589.55v-1.65l-.808.173v.916q0 .123-.068.224l-.421.623q-.168.242-.45.298l-2.29.489q.045-.455.236-.876l2.184-.466v-1.04l-2.038.433q.045-.455.235-.87l1.803-.388V2.353q.342-.416.809-.68v3.718l.808-.174v-2.46q.343-.415.803-.68v2.971l2.022-.432q-.045.455-.236.876l-1.786.382v.82l2.022-.427q-.045.46-.236.876Z" style="font-size:11.5px;font-family:'Gentium Unicode';-inkscape-font-specification:'Gentium Unicode'" aria-label="⃁"/>
                                </g>
                            </svg>
                        </span>
                    </div>
                    
                    <form action="auction_details.php" method="GET">
                        <input type="hidden" name="auction_id" value="<?= $auction['auction_id'] ?>">
                        <button type="submit" class="auction-button">
                            <i class="fas fa-gavel"></i>
                            انضم إلى المزاد
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
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
                <li><a href="#">الأسئلة الشائعة</a></li>
                <li><a href="#">اتصل بنا</a></li>
                <li><a href="#">سياسة الخصوصية</a></li>
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
        const buttons = document.querySelectorAll('.btn, .auction-card');
        
        buttons.forEach(button => {
            button.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            
            button.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
        
        // جافاسكريبت لمسار التنقل
        const breadcrumb = document.getElementById("breadcrumb");
        
        // الحصول على المسار الحالي وتقسيمه
        const pathArray = window.location.pathname.split("/").filter(Boolean);
        
        // إضافة "الرئيسية" كرابط فقط
        const home = document.createElement("li");
        home.innerHTML = '<a href="/"><i class="fas fa-home"></i> الرئيسية</a>';
        breadcrumb.appendChild(home);
        
        // باقي الأجزاء تظهر كنصوص فقط
        pathArray.forEach((segment, index) => {
            const li = document.createElement("li");
            const label = decodeURIComponent(segment.replace(/-/g, " "));
            
            // آخر عنصر: اسم الصفحة الحالية من العنوان أو المسار
            if (index === pathArray.length - 1) {
                li.innerHTML = `<i class="fas fa-laptop"></i> ${document.title || label}`;
            } else {
                li.textContent = label;
            }
            
            breadcrumb.appendChild(li);
        });
    });
</script>
</body>
</html>