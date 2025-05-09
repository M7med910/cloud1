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
    <title>الأسئلة الشائعة | منصة المزادات</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icon/favicon-16x16.png">
    <link rel="shortcut icon" href="icon/favicon.ico">
    
    <!-- Meta Tags -->
    <meta name="description" content="الأسئلة الشائعة حول منصة المزادات - كل ما تحتاج معرفته عن كيفية البيع والشراء عبر المنصة">
    <meta property="og:title" content="الأسئلة الشائعة | منصة المزادات">
    <meta property="og:description" content="إجابات على أكثر الأسئلة شيوعاً حول استخدام منصة المزادات">
    <meta property="og:image" content="https://ebidzones.com/icon/icon22new.png">
    <meta property="og:url" content="https://ebidzones.com/faq.php">
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
        
        /* FAQ Styles */
        .faq-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .faq-category {
            margin-bottom: 2.5rem;
        }
        
        .faq-category-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent-color);
        }
        
        .faq-item {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 1rem;
            overflow: hidden;
            transition: var(--transition);
        }
        
        .faq-question {
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            background-color: #f9f9f9;
            transition: var(--transition);
        }
        
        .faq-question:hover {
            background-color: #f1f1f1;
        }
        
        .faq-question i {
            transition: var(--transition);
            color: var(--secondary-color);
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }
        
        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .faq-item.active .faq-answer {
            padding: 0 1.5rem 1.5rem;
            max-height: 500px;
        }
        
        .contact-promo {
            background-color: var(--light-color);
            padding: 2rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin-top: 3rem;
            box-shadow: var(--box-shadow);
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
            .faq-item:hover,
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
            <input type="text" class="search-input" placeholder="ابحث في الأسئلة الشائعة...">
        </div>
    </div>
</header>
    
<!-- FAQ Section -->
<section class="section animate-fade" style="animation-delay: 0.1s">
    <h2 class="section-title">الأسئلة الشائعة</h2>
    
    <div class="faq-container">
        <!-- General Questions -->
        <div class="faq-category animate-fade" style="animation-delay: 0.2s">
            <h3 class="faq-category-title"><i class="fas fa-question-circle"></i> أسئلة عامة</h3>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>ما هي منصة المزادات؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>منصة المزادات هي موقع إلكتروني يتيح للمستخدمين المشاركة في مزادات لشراء وبيع مختلف أنواع السلع والخدمات. نقدم بيئة آمنة وشفافة لإجراء المزادات الإلكترونية بمختلف أنواعها.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>كيف يمكنني التسجيل في المنصة؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>يمكنك التسجيل عن طريق النقر على زر "إنشاء حساب" في أعلى الصفحة وإدخال المعلومات المطلوبة مثل الاسم، البريد الإلكتروني، ورقم الهاتف. سيتم إرسال رمز تحقق إلى هاتفك لتأكيد الهوية.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>هل توجد رسوم للاشتراك في المنصة؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>التسجيل والمشاركة في المزادات مجاني تماماً. نقتطع نسبة صغيرة فقط عند بيع المنتجات بنجاح، وتظهر هذه النسبة بوضوح عند إنشاء المزاد.</p>
                </div>
            </div>
        </div>
        
        <!-- Buying Questions -->
        <div class="faq-category animate-fade" style="animation-delay: 0.3s">
            <h3 class="faq-category-title"><i class="fas fa-shopping-cart"></i> أسئلة حول الشراء</h3>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>كيف يمكنني المشاركة في المزاد؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>للمشاركة في أي مزاد:
                        <ol>
                            <li>سجل الدخول إلى حسابك</li>
                            <li>ابحث عن المنتج الذي ترغب فيه</li>
                            <li>اضغط على "انضم إلى المزاد"</li>
                            <li>أدخل المبلغ الذي ترغب في المزايدة به</li>
                            <li>سيتم إعلامك إذا تمت مزايدتك بنجاح</li>
                        </ol>
                    </p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>ماذا يحدث إذا فزت بالمزاد؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>عند الفوز بالمزاد، ستتلقى إشعاراً فورياً عبر البريد الإلكتروني والهاتف. سيتصل بك البائع خلال 24 ساعة لترتيب شحن المنتج أو استلامه حسب ما تم الاتفاق عليه. يمكنك دفع المبلغ عبر المنصة أو حسب الاتفاق مع البائع.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>هل يمكنني إلغاء المزايدة بعد تقديمها؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>لا يمكن إلغاء المزايدة بعد تقديمها إلا في حالات استثنائية مثل خطأ واضح في سعر المنتج. ننصح المستخدمين بالتأكد جيداً قبل تقديم أي مزايدة.</p>
                </div>
            </div>
        </div>
        
        <!-- Selling Questions -->
        <div class="faq-category animate-fade" style="animation-delay: 0.4s">
            <h3 class="faq-category-title"><i class="fas fa-tag"></i> أسئلة حول البيع</h3>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>كيف يمكنني إضافة منتج للمزاد؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>لإضافة منتج:
                        <ol>
                            <li>سجل الدخول إلى حسابك</li>
                            <li>اضغط على "إضافة مزاد" في القائمة العلوية</li>
                            <li>املأ معلومات المنتج (الاسم، الوصف، الفئة، السعر الابتدائي)</li>
                            <li>أضف صوراً واضحة للمنتج</li>
                            <li>حدد مدة المزاد (24 ساعة، 3 أيام، أسبوع)</li>
                            <li>اضغط على "نشر المزاد"</li>
                        </ol>
                    </p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>ما هي النسبة التي تأخذها المنصة من البيع؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>تأخذ المنصة نسبة 5% من سعر البيع النهائي كرسوم خدمة، بحد أدنى 20 ريال. هذه النسبة تغطي تكاليف تشغيل المنصة وتطويرها.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>كيف أحصل على أموالي بعد البيع؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>بعد تأكيد استلام المشتري للمنتج ورضاه عنه، سيتم تحويل المبلغ إلى حسابك البنكي خلال 3-5 أيام عمل. يمكنك إضافة بيانات الحساب البنكي في قسم "الملف الشخصي".</p>
                </div>
            </div>
        </div>
        
        <!-- Payment & Security -->
        <div class="faq-category animate-fade" style="animation-delay: 0.5s">
            <h3 class="faq-category-title"><i class="fas fa-lock"></i> الأمان والدفع</h3>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>هل بياناتي المالية آمنة على المنصة؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>نعم، نستخدم أحدث تقنيات التشفير لحماية بياناتك المالية. لا نخزن معلومات بطاقات الائتمان على خوادمنا، بل نستخدم بوابات دفع معتمدة وآمنة.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>ما هي طرق الدفع المتاحة؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>نحن نقدم عدة خيارات دفع آمنة:
                        <ul>
                            <li>بطاقات الائتمان والمدينة (Visa, MasterCard, Mada)</li>
                            <li>حوالة بنكية</li>
                            <li>الدفع عند الاستلام (لمنتجات محددة)</li>
                            <li>محافظ إلكترونية (حسب المنطقة)</li>
                        </ul>
                    </p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <span>ماذا أفعل إذا لم أستلم المنتج الذي اشتريته؟</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>في حال عدم استلام المنتج خلال المدة المتفق عليها:
                        <ol>
                            <li>تواصل مع البائع عبر رسائل المنصة</li>
                            <li>إذا لم يتم حل المشكلة، يمكنك فتح تذكرة دعم</li>
                            <li>سيقوم فريقنا بالتحقيق وإعادة المبلغ إذا ثبت التجاوز</li>
                        </ol>
                        لدينا سياسة حماية مشترين تضمن استرداد الأموال في حالات الغش.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Contact Promo -->
        <div class="contact-promo animate-fade" style="animation-delay: 0.6s">
            <h3>لم تجد إجابتك؟</h3>
            <p>فريق الدعم لدينا مستعد لمساعدتك على مدار الساعة</p>
            <a href="call_uspage.php" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="fas fa-headset"></i> اتصل بنا
            </a>
        </div>
    </div>
</section>
    
<!-- Footer -->
<footer class="main-footer animate-fade" style="animation-delay: 0.7s">
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
                <li><a href="quations.php">الأسئلة الشائعة</a></li>
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
    // FAQ Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            
            question.addEventListener('click', () => {
                // Close all other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                item.classList.toggle('active');
            });
        });
        
        // Simple animation on scroll
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
        const buttons = document.querySelectorAll('.btn, .faq-question');
        
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
