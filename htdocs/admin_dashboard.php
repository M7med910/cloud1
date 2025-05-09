<?php
session_start();

require 'visit-count.php';


$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");


// التحقق من أن المستخدم هو المسؤول
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// استلام كلمات البحث
$userSearch = isset($_GET['user_search']) ? trim($_GET['user_search']) : '';
$auctionProductSearch = isset($_GET['auction_product_search']) ? trim($_GET['auction_product_search']) : '';
$auctionUserSearch = isset($_GET['auction_user_search']) ? trim($_GET['auction_user_search']) : '';


?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المسؤول</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon وأيقونات الموقع -->
    <link rel="icon" type="image/png" sizes="32x32" href="icon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="icon/favicon-16x16.png" />
    <link rel="shortcut icon" href="icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="icon/apple-touch-icon.png" />
    <link rel="manifest" href="icon/site.webmanifest" />
    <meta name="theme-color" content="#ffffff" />

    <!-- Windows Tile -->
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="msapplication-TileImage" content="icon/mstile-144x144.png" />

    <!-- Safari Pin -->
    <link rel="mask-icon" href="icon/safari-pinned-tab.svg" color="#5bbad5" />

    <!-- Open Graph (فيسبوك، سناب، واتساب...) -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="منصة المزادات" />
    <meta property="og:description" content="موقع مخصص لعالم المزادات لبيع وشراء المنتجات ." />
    <meta property="og:url" content="https://ebidzones.com/" />
    <meta property="og:image" content="https://ebidzones.com/icon/icon22new.png" />

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="منصة المزادات" />
    <meta name="twitter:description" content="موقع مخصص لعالم المزادات لبيع وشراء المنتجات ." />
    <meta name="twitter:image" content="https://ebidzones.com/icon/icon22new.png" />
    <style>
        :root {
            --primary-color: #2c6b4f;
            --secondary-color: #3a8d6e;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --danger-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #3498db;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
            direction: rtl;
        }
        
        .admin-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            padding: 20px;
            margin: 20px auto;
            max-width: 99%;
        }
        
        .admin-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .section-title {
            color: var(--secondary-color);
            margin: 20px 0 15px;
            padding-right: 10px;
            border-right: 4px solid var(--secondary-color);
            font-size: 1.2rem;
        }
        
        .search-form {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px 15px;
            margin-bottom: 10px;
        }
        
        .btn {
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 500;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            font-size: 0.9rem;
        }
        
        .table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            padding: 12px;
            white-space: nowrap;
        }
        
        .table td {
            vertical-align: middle;
            padding: 10px;
            text-align: center;
        }
        
        .table tr:nth-child(even) {
            background-color: rgba(44, 107, 79, 0.05);
        }
        
        .table tr:hover {
            background-color: rgba(44, 107, 79, 0.1);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eee;
        }
        
        .action-btn {
            padding: 6px 10px;
            font-size: 0.8rem;
            margin: 2px;
            min-width: 70px;
        }
        
        .badge {
            padding: 5px 8px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .logout-btn {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            padding: 8px 20px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        
        .no-results {
            text-align: center;
            padding: 30px;
            color: #6c757d;
        }
        
        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            .admin-container {
                padding: 15px;
                margin: 10px auto;
                border-radius: 8px;
            }
            
            .admin-header h2 {
                font-size: 1.3rem;
            }
            
            .section-title {
                font-size: 1.1rem;
                margin: 15px 0 10px;
            }
            
            .search-form {
                padding: 10px;
            }
            
            .form-control {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
            
            .btn {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            
            .table th, .table td {
                padding: 8px;
                font-size: 0.8rem;
            }
            
            .user-avatar {
                width: 35px;
                height: 35px;
            }
            
            .action-btn {
                padding: 5px 8px;
                font-size: 0.75rem;
                min-width: 60px;
                margin: 1px;
            }
        }
        
        /* تحسينات للجوالات الصغيرة جداً */
        @media (max-width: 576px) {
            .admin-container {
                padding: 10px;
                margin: 5px auto;
            }
            
            .admin-header h2 {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 1rem;
            }
            
            .search-form .row > div {
                padding: 0 5px;
            }
            
            .form-control {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
            
            .btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
            
            .table th {
                padding: 6px;
                font-size: 0.75rem;
            }
            
            .table td {
                padding: 6px;
                font-size: 0.75rem;
            }
            
            .logout-btn {
                padding: 6px 15px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="admin-container">
                    <div class="admin-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0"><i class="fas fa-cogs"></i> لوحة تحكم المسؤول</h2>
                            <div class="d-flex align-items-center">
                               <div class="box">
    
                                 <div class="visit-box">
        عدد زيارات الموقع: <strong><?php echo $total_visits; ?></strong>
    </div>
                            </div>
                        </div>
                    </div>

                    <!-- بحث عن المستخدمين -->
                    <h4 class="section-title"><i class="fas fa-users"></i> إدارة المستخدمين</h4>
                    <form class="search-form" method="get">
                        <div class="row g-2">
                            <div class="col-md-8 col-12">
                                <input type="text" name="user_search" class="form-control" 
                                       placeholder="ابحث عن مستخدم بالاسم أو البريد..." 
                                       value="<?= htmlspecialchars($userSearch) ?>">
                            </div>
                            <div class="col-md-4 col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> <span class="d-none d-md-inline">بحث</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>المستخدم</th>
            <th class="d-none d-md-table-cell">معلومات التواصل</th>
            <th>الدور</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
        </tr>
        </thead>
        <tbody>
        <?php
        
        $sql = "SELECT user_id, name, email, phone_number, profile_pic, role, is_blocked FROM user";
        if ($userSearch !== '') {
            $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($userSearch) . "%' OR email LIKE '%" . $conn->real_escape_string($userSearch) . "%'";
        }
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['is_blocked'] ? "<span class='badge bg-danger'>محظور</span>" : "<span class='badge bg-success'>نشط</span>";
                $actionLabel = $row['is_blocked'] ? "تفعيل" : "تعطيل";
                $buttonClass = $row['is_blocked'] ? "btn-success" : "btn-danger";
                $profilePic = !empty($row['profile_pic']) ? "uploads/{$row['profile_pic']}" : "https://via.placeholder.com/50";
                
                echo "<tr>
                        <td>{$row['user_id']}</td>
                        <td>
                            <div class='d-flex align-items-center'>
                                <img src='{$profilePic}' class='user-avatar me-2' alt='صورة المستخدم'>
                                <div>
                                    <div class='fw-bold'>{$row['name']}</div>
                                    <small class='d-md-none text-muted'>{$row['email']}</small>
                                </div>
                            </div>
                        </td>
                        <td class='d-none d-md-table-cell'>
                            <div><i class='fas fa-envelope me-2'></i>{$row['email']}</div>
                            <div><i class='fas fa-phone me-2'></i>{$row['phone_number']}</div>
                        </td>
                        <td>
                            " . ($row['role'] === 'admin' ? "<span class='badge bg-warning'><i class='fas fa-crown'></i></span>" :
                              "<span class='badge bg-info'>مستخدم</span>") . "
                        </td>
                        <td>{$status}</td>
                        <td>
                            <div class='d-flex flex-wrap justify-content-center'>
                                <a href='toggle_block.php?user_id={$row['user_id']}' class='btn {$buttonClass} action-btn' title='{$actionLabel}'>
                                    <i class='fas fa-user-lock d-none d-md-inline'></i> {$actionLabel}
                                </a>
                                " . ($row['role'] !== 'admin' 
                                    ? "<a href='promote_user.php?user_id={$row['user_id']}' class='btn btn-warning action-btn' title='ترقية' onclick='return confirm(\"هل أنت متأكد أنك تريد ترقية هذا المستخدم إلى مسؤول؟\");'>
                                        <i class='fas fa-user-shield d-none d-md-inline'></i> ترقية
                                    </a>" 
                                    : "<a href='demote_user.php?user_id={$row['user_id']}' class='btn btn-dark action-btn' title='سحب الترقية' onclick='return confirm(\"هل أنت متأكد أنك تريد سحب صلاحيات المسؤول من هذا المستخدم؟\");'>
                                        <i class='fas fa-user-slash d-none d-md-inline'></i> سحب الترقية
                                    </a>") . "
                            </div>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='no-results'><i class='fas fa-info-circle fa-2x mb-3'></i><br>لا توجد نتائج مطابقة للبحث</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

                    <!-- بحث عن المزادات -->
                    <h4 class="section-title"><i class="fas fa-gavel"></i> إدارة المزادات</h4>
                    <form class="search-form" method="get">
                        <div class="row g-2">
                            <div class="col-md-4 col-12">
                                <input type="text" name="auction_product_search" class="form-control" 
                                       placeholder="اسم المنتج..." value="<?= htmlspecialchars($auctionProductSearch) ?>">
                            </div>
                            <div class="col-md-4 col-12">
                                <input type="text" name="auction_user_search" class="form-control" 
                                       placeholder="بريد المستخدم..." value="<?= htmlspecialchars($auctionUserSearch) ?>">
                            </div>
                            <div class="col-md-4 col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> <span class="d-none d-md-inline">بحث</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>المنتج</th>
                                <th class="d-none d-md-table-cell">الفترة</th>
                                <th>السعر</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $auctionQuery = "SELECT a.auction_id, a.start_date, a.end_date, a.starting_price, a.status, p.name AS product_name 
                                             FROM auction a
                                             JOIN product p ON a.product_id = p.product_id
                                             WHERE 1=1";

                            if ($auctionProductSearch !== '') {
                                $auctionQuery .= " AND p.name LIKE '%" . $conn->real_escape_string($auctionProductSearch) . "%'";
                            }

                            if ($auctionUserSearch !== '') {
                                $auctionQuery .= " AND a.user_id IN (SELECT user_id FROM user WHERE email LIKE '%" . $conn->real_escape_string($auctionUserSearch) . "%')";
                            }

                            $auctionQuery .= " ORDER BY a.auction_id DESC";
                            $auctions = $conn->query($auctionQuery);

                            if ($auctions->num_rows > 0) {
                                while ($auction = $auctions->fetch_assoc()) {
                                    $statusBadge = '';
                                    switch ($auction['status']) {
                                        case 'active':
                                            $statusBadge = "<span class='badge bg-success'>نشط</span>";
                                            break;
                                        case 'ended':
                                            $statusBadge = "<span class='badge bg-secondary'>منتهي</span>";
                                            break;
                                        case 'pending':
                                            $statusBadge = "<span class='badge bg-warning'>قيد الانتظار</span>";
                                            break;
                                        default:
                                            $statusBadge = "<span class='badge bg-info'>{$auction['status']}</span>";
                                    }
                                    
                                    echo "<tr>
                                            <td>{$auction['auction_id']}</td>
                                            <td><strong>{$auction['product_name']}</strong></td>
                                            <td class='d-none d-md-table-cell'>
                                                <div><i class='fas fa-calendar-start me-2'></i>{$auction['start_date']}</div>
                                                <div><i class='fas fa-calendar-end me-2'></i>{$auction['end_date']}</div>
                                            </td>
                                            <td>" . number_format($auction['starting_price'], 2) . " <small>ريال</small></td>
                                            <td>{$statusBadge}</td>
                                            <td>
                                                <div class='d-flex flex-wrap justify-content-center'>
                                                    <a href='delete_auction.php?auction_id={$auction['auction_id']}' class='btn btn-danger action-btn' title='حذف' onclick='return confirm(\"هل أنت متأكد أنك تريد حذف هذا المزاد؟ لن يمكنك استرجاعه لاحقًا.\");'>
                                                        <i class='fas fa-trash d-none d-md-inline'></i> حذف
                                                    </a>
                                                </div>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='no-results'><i class='fas fa-info-circle fa-2x mb-3'></i><br>لا توجد مزادات متاحة</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="logout.php" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تفعيل عناصر Bootstrap
        document.addEventListener('DOMContentLoaded', function() {
            // تفعيل Tooltips للأزرار
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    placement: 'top'
                });
            });
            
            // إضافة تأثيرات للجداول
            const rows = document.querySelectorAll('.table-hover tbody tr');
            rows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.style.transform = 'translateX(-3px)';
                    row.style.transition = 'all 0.2s ease';
                });
                row.addEventListener('mouseleave', () => {
                    row.style.transform = 'translateX(0)';
                });
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>