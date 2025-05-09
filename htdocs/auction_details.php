<?php
// بداية الجلسة يجب أن تكون في أعلى الملف قبل أي إخراج
session_start();

// تفعيل الأخطاء للمساعدة في التصحيح
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// الاتصال بقاعدة البيانات
$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// التحقق من وصول auction_id
if (!isset($_GET['auction_id']) || empty($_GET['auction_id'])) {
    die("لم يتم تحديد المزاد.");
}

$auction_id = $_GET['auction_id'];

// جلب بيانات المزاد
$sql = "SELECT a.*, p.name AS product_name, p.category, p.image, p.description
        FROM auction a
        JOIN product p ON a.product_id = p.product_id
        WHERE a.auction_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$auction_id]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auction) {
    die("المزاد غير موجود.");
}

// جلب المزايدات
$sql = "SELECT b.*, u.name AS user_name
        FROM bid b
        JOIN user u ON b.user_id = u.user_id
        WHERE b.auction_id = ?
        ORDER BY b.bid_time DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$auction_id]);
$bids = $stmt->fetchAll(PDO::FETCH_ASSOC);

// تعيين المنطقة الزمنية
date_default_timezone_set('Asia/Riyadh');
$current_time = date('Y-m-d H:i:s');
// تحديد جميع صفحات المزادات
$auction_pages = [
    'سيارات' => [
        'page' => 'CarsPage.php',
        'icon' => 'fa-car'
    ],
    'إلكترونيات' => [
        'page' => 'ElectronicsPage.php',
        'icon' => 'fa-laptop'
    ],
    'عقار' => [
        'page' => 'RealEstatePage.php',
        'icon' => 'fa-building'
    ]
];

$current_category = htmlspecialchars($auction['category']);
$current_product = htmlspecialchars($auction['product_name']);
$current_page = basename($_SERVER['PHP_SELF']);

// بناء مسار التصفح
$breadcrumb = [
    [
        'title' => 'الرئيسية',
        'link' => 'index.php',
        'icon' => 'fa-home'
    ],
    [
        'title' => 'مزاد ' . $current_category,
        'link' => $auction_pages[$current_category]['page'] ?? '#',
        'icon' => $auction_pages[$current_category]['icon'] ?? 'fa-gavel'
    ],
    [
        'title' => $current_product,
        'link' => '',
        'icon' => 'fa-tag'
    ]
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل المزاد</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon وأيقونات الموقع -->
    <link rel="icon" type="image/png" sizes="32x32" href="icon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="icon/favicon-16x16.png" />
    <link rel="shortcut icon" href="icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="icon/apple-touch-icon.png" />
    <link rel="manifest" href="icon/site.webmanifest" />
    <meta name="theme-color" content="#ffffff" />

    <style>
        :root {
            --primary-color: #1a5d57;
            --secondary-color: #3a837a;
            --accent-color: #f8b400;
            --light-color: #f5f5f5;
            --dark-color: #0d2e2b;
            --text-color: #333333;
            --white-color: #ffffff;
            --error-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f9f9f9;
            color: var(--text-color);
            line-height: 1.6;
            padding: 0;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* رأس الصفحة */
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 100;
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            height: 40px;
            width: auto;
        }
        
        h1 {
            font-size: 1.8rem;
            margin: 0;
            font-weight: 700;
            color: var(--white-color);
        }
        
        h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-color);
        }
        
        h3 {
            color: var(--secondary-color);
            font-size: 1.4rem;
            margin-bottom: 1rem;
            position: relative;
            padding-right: 15px;
        }
        
        h3::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: -5px;
            width: 50px;
            height: 3px;
            background-color: var(--accent-color);
        }
        
        /* تنسيق الأقسام */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
            flex: 1;
        }
        
        section {
            background-color: var(--white-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e0e0e0;
        }
        
        section:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .product-info {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            align-items: flex-start;
        }
        
        .product-details {
            flex: 1;
            min-width: 300px;
        }
        
        .product-image {
            flex: 0 0 300px;
            text-align: center;
            background-color: var(--light-color);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        
        img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        img:hover {
            transform: scale(1.03);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.95rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        th, td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: center;
        }
        
        th {
            background-color: var(--primary-color);
            color: var(--white-color);
            font-weight: 500;
        }
        
        tr:nth-child(even) {
            background-color: rgba(26, 93, 87, 0.05);
        }
        
        tr:hover {
            background-color: rgba(26, 93, 87, 0.1);
        }
        
        /* تذييل الصفحة */
        footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white-color);
            text-align: center;
            padding: 1.5rem 0;
            margin-top: auto;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* تنسيق النماذج */
        .bid-form {
            background-color: rgba(26, 93, 87, 0.05);
            border: 1px solid rgba(26, 93, 87, 0.1);
        }
        
        .bid-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary-color);
        }
        
        .bid-form input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            font-family: 'Tajawal', sans-serif;
        }
        
        .bid-form input:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 93, 87, 0.1);
        }
        
        button {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white-color);
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Tajawal', sans-serif;
            font-weight: 500;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            opacity: 0.9;
        }
        
        /* تنسيق العد التنازلي */
        #countdown {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.4rem;
            text-align: center;
            padding: 0.5rem;
            background-color: rgba(26, 93, 87, 0.1);
            border-radius: 4px;
            border-left: 4px solid var(--accent-color);
        }
        
        /* رسائل التنبيه */
        .alert {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            border-left: 4px solid transparent;
        }
        
        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            border-color: var(--error-color);
        }
        
        .alert-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .status-message {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            border-left: 4px solid transparent;
        }
        
        .status-not-started {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .status-ended {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            border-color: var(--error-color);
        }
        
        /* تأثيرات للجوائز */
        .highlight {
            background-color: rgba(248, 180, 0, 0.15) !important;
            position: relative;
        }
        
        .highlight::before {

            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 10px;
            color: var(--accent-color);
        }
        
        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .product-info {
                flex-direction: column;
            }
            
            .product-image {
                flex: 1;
                width: 100%;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            h2 {
                font-size: 1.4rem;
            }
            
            h3 {
                font-size: 1.2rem;
            }
        }
        
        /* تنسيق Breadcrumb متكامل */
        .smart-breadcrumb {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 15px;
            border-radius: 8px;
            direction: rtl;
            backdrop-filter: blur(5px);
        }
        
        .smart-breadcrumb ol {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .smart-breadcrumb li {
            display: flex;
            align-items: center;
            margin: 0 5px;
        }
        
        .smart-breadcrumb a {
            color: var(--white-color);
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .smart-breadcrumb a:hover {
            background: rgba(255, 255, 255, 0.3);
            text-decoration: none;
        }
        
        .smart-breadcrumb a i {
            margin-left: 8px;
            font-size: 14px;
        }
        
        .smart-breadcrumb .current {
            color: var(--accent-color);
            font-weight: 600;
            padding: 5px;
            display: flex;
            align-items: center;
        }
        
        .smart-breadcrumb .current i {
            margin-left: 8px;
            color: var(--accent-color);
        }
        
        .smart-breadcrumb .separator {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            margin: 0 5px;
        }
        
        /* تحسينات إضافية */
        strong {
            color: var(--primary-color);
        }
        
        .currency-icon {
            width: 16px;
            height: 16px;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
        }
        
        .product-details p {
            margin-bottom: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- رأس الصفحة -->
    <header>
        <div class="header-container">
            <div class="header-top">
                <img src="logo/png/logo-no-background.png" alt="Logo" class="logo">
                <h1>تفاصيل المزاد</h1>
            </div>
            
            <!-- Breadcrumb متكامل -->
            <nav class="smart-breadcrumb" aria-label="مسار التصفح">
                <ol>
                    <?php foreach ($breadcrumb as $item): ?>
                        <li>
                            <?php if (!empty($item['link'])): ?>
                                <a href="<?= $item['link'] ?>">
                                    <i class="fas <?= $item['icon'] ?>"></i>
                                    <?= $item['title'] ?>
                                </a>
                            <?php else: ?>
                                <span class="current">
                                    <i class="fas <?= $item['icon'] ?>"></i>
                                    <?= $item['title'] ?>
                                </span>
                            <?php endif; ?>
                        </li>
                        <?php if ($item !== end($breadcrumb)): ?>
                            <li class="separator"><i class="fas fa-chevron-left"></i></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-error'><i class='fas fa-exclamation-circle'></i> " . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'><i class='fas fa-check-circle'></i> " . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        ?>
        
        <section>
            <h3>الوقت المتبقي</h3>
            <p id="countdown"></p>
        </section>
        
        <script>
            const startDate = new Date("<?= $auction['start_date'] ?>").getTime();
            const endDate = new Date("<?= $auction['end_date'] ?>").getTime();
            
            const countdown = document.getElementById('countdown');
            
            function updateCountdown() {
                const now = new Date().getTime();
                
                if (now < startDate) {
                    const distance = startDate - now;
                    showCountdown(distance, "المزاد سيبدأ خلال: ");
                } else if (now >= startDate && now <= endDate) {
                    const distance = endDate - now;
                    showCountdown(distance, "المزاد سينتهي خلال: ");
                } else {
                    countdown.innerHTML = "<i class='fas fa-hourglass-end'></i> انتهى المزاد.";
                }
            }
            
            function showCountdown(distance, label) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                countdown.innerHTML = `<i class='fas fa-clock'></i> ${label} ${days} يوم ${hours} ساعة ${minutes} دقيقة ${seconds} ثانية`;
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
        </script>
        
        <section>
            <div class="product-info">
                <div class="product-details">
                    <h2>منتج المزاد: <?= htmlspecialchars($auction['product_name']) ?></h2>
                    <p><strong><i class="fas fa-tag"></i> الفئة:</strong> <?= htmlspecialchars($auction['category']) ?></p>
                    <p><strong><i class="fas fa-money-bill-wave"></i> السعر الابتدائي:</strong> <?= number_format($auction['starting_price']) ?> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 10 " class="currency-icon"><g style="font-size:8px;line-height:0;font-family:Andika;-inkscape-font-specification:Andika;opacity:1;vector-effect:none;fill:#1a5d57;stroke-width:.15;stroke-linecap:square;stroke-linejoin:bevel;paint-order:markers stroke fill"><path d="m6.836 8.468 2.595-.55q-.045.454-.236.876l-2.589.55q.04-.46.23-.876Zm2.359-1.325-2.589.55v-1.65l-.808.173v.916q0 .123-.068.224l-.421.623q-.168.242-.45.298l-2.29.489q.045-.455.236-.876l2.184-.466v-1.04l-2.038.433q.045-.455.235-.87l1.803-.388V2.353q.342-.416.809-.68v3.718l.808-.174v-2.46q.343-.415.803-.68v2.971l2.022-.432q-.045.455-.236.876l-1.786.382v.82l2.022-.427q-.045.46-.236.876Z" style="font-size:11.5px;font-family:'Gentium Unicode';-inkscape-font-specification:'Gentium Unicode'" aria-label="⃁"/></g></svg></p>
                    <p><strong><i class="fas fa-align-right"></i> الوصف:</strong> <?= htmlspecialchars($auction['description'] ?? 'لا يوجد وصف') ?></p>
                </div>
                
                <?php if (!empty($auction['image']) && file_exists('uploads/' . $auction['image'])): ?>
                    <div class="product-image">
                        <img src="uploads/<?= htmlspecialchars($auction['image']) ?>" alt="صورة المنتج">
                    </div>
                <?php else: ?>
                    <div class="product-image">
                        <p><i class="fas fa-image"></i> لا توجد صورة للمنتج</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
        <section>
            <h3>المزايدات الحالية</h3>
            <?php if (count($bids) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>رقم المزايدة</th>
                            <th>المستخدم</th>
                            <th>القيمة</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bids as $index => $bid): ?>
                            <tr <?= $index === 0 ? 'class="highlight"' : '' ?>>
                                <td><?= htmlspecialchars($bid['bid_id']) ?></td>
                                <td><?= htmlspecialchars($bid['user_name']) ?></td>
                                <td><?= number_format($bid['bid_amount']) ?> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 10 " class="currency-icon"><g style="font-size:8px;line-height:0;font-family:Andika;-inkscape-font-specification:Andika;opacity:1;vector-effect:none;fill:#1a5d57;stroke-width:.15;stroke-linecap:square;stroke-linejoin:bevel;paint-order:markers stroke fill"><path d="m6.836 8.468 2.595-.55q-.045.454-.236.876l-2.589.55q.04-.46.23-.876Zm2.359-1.325-2.589.55v-1.65l-.808.173v.916q0 .123-.068.224l-.421.623q-.168.242-.45.298l-2.29.489q.045-.455.236-.876l2.184-.466v-1.04l-2.038.433q.045-.455.235-.87l1.803-.388V2.353q.342-.416.809-.68v3.718l.808-.174v-2.46q.343-.415.803-.68v2.971l2.022-.432q-.045.455-.236.876l-1.786.382v.82l2.022-.427q-.045.46-.236.876Z" style="font-size:11.5px;font-family:'Gentium Unicode';-inkscape-font-specification:'Gentium Unicode'" aria-label="⃁"/></g></svg></td>
                                <td><?= htmlspecialchars($bid['bid_time']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><i class="fas fa-info-circle"></i> لا توجد مزايدات حتى الآن.</p>
            <?php endif; ?>
        </section>
        
        <?php if ($current_time < $auction['start_date']): ?>
            <div class='status-message status-not-started'><i class="fas fa-hourglass-start"></i> المزاد لم يبدأ بعد.</div>
        <?php elseif ($current_time > $auction['end_date']): ?>
            <div class='status-message status-ended'><i class="fas fa-hourglass-end"></i> انتهى المزاد، لا يمكن تقديم مزايدات جديدة.</div>
        <?php else: ?>
            <section class="bid-form">
                <h3>أدخل مزايدتك</h3>
                <form action="place_bid.php" method="POST">
                    <input type="hidden" name="auction_id" value="<?= $auction_id ?>">
                    <label for="bid_amount">المبلغ (<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 12 10 " class="currency-icon"><g style="font-size:8px;line-height:0;font-family:Andika;-inkscape-font-specification:Andika;opacity:1;vector-effect:none;fill:#1a5d57;stroke-width:.15;stroke-linecap:square;stroke-linejoin:bevel;paint-order:markers stroke fill"><path d="m6.836 8.468 2.595-.55q-.045.454-.236.876l-2.589.55q.04-.46.23-.876Zm2.359-1.325-2.589.55v-1.65l-.808.173v.916q0 .123-.068.224l-.421.623q-.168.242-.45.298l-2.29.489q.045-.455.236-.876l2.184-.466v-1.04l-2.038.433q.045-.455.235-.87l1.803-.388V2.353q.342-.416.809-.68v3.718l.808-.174v-2.46q.343-.415.803-.68v2.971l2.022-.432q-.045.455-.236.876l-1.786.382v.82l2.022-.427q-.045.46-.236.876Z" style="font-size:11.5px;font-family:'Gentium Unicode';-inkscape-font-specification:'Gentium Unicode'" aria-label="⃁"/></g></svg>):</label>
                    <input type="number" name="bid_amount" id="bid_amount" required 
                           min="<?= $auction['starting_price'] ?>" 
                           step="1"
                           placeholder="أدخل قيمة المزايدة">
                    <button type="submit"><i class="fas fa-gavel"></i> تقديم المزايدة</button>
                </form>
            </section>
        <?php endif; ?>
    </div>
    
    <!-- تذييل الصفحة -->
    <footer>
        <div class="footer-container">
            <p>&copy; <?= date("Y") ?>  منصة المزادات - جميع الحقوق محفوظة</p>
        </div>
    </footer>
</body>
</html>