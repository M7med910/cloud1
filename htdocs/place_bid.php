<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// تحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "يجب تسجيل الدخول للمزايدة.";
    header("Location: OTP.php"); // صفحة تسجيل الدخول
    exit;
}

// بيانات الاتصال
$host = 'sql200.infinityfree.com';
$dbname = 'if0_38543174_my_db';
$username = 'if0_38543174';
$password = 'MDH6WuEkvPyL';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error'] = "فشل الاتصال بقاعدة البيانات.";
    header("Location: auction_details.php?auction_id=" . $_POST['auction_id']);
    exit;
}

if (!isset($_POST['auction_id'], $_POST['bid_amount'])) {
    $_SESSION['error'] = "بيانات المزايدة ناقصة.";
    header("Location: auction_details.php?auction_id=" . $_POST['auction_id']);
    exit;
}

$auction_id = $_POST['auction_id'];
$bid_amount = floatval($_POST['bid_amount']);
$user_id = $_SESSION['user_id'];

// جلب السعر الحالي
$stmt = $pdo->prepare("
    SELECT a.starting_price, MAX(b.bid_amount) AS highest_bid
    FROM auction a
    LEFT JOIN bid b ON a.auction_id = b.auction_id
    WHERE a.auction_id = ?
");
$stmt->execute([$auction_id]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auction) {
    $_SESSION['error'] = "المزاد غير موجود.";
    header("Location: auction_details.php?auction_id=" . $auction_id);
    exit;
}

$current_price = max($auction['starting_price'], $auction['highest_bid'] ?? 0);

if ($bid_amount <= $current_price) {
    $_SESSION['error'] = "يجب أن تكون المزايدة أعلى من السعر الحالي (" . number_format($current_price) . " ريال).";
    header("Location: auction_details.php?auction_id=" . $auction_id);
    exit;
}

// إدخال المزايدة
$stmt = $pdo->prepare("
    INSERT INTO bid (bid_amount, bid_time, user_id, auction_id)
    VALUES (?, NOW(), ?, ?)
");
$stmt->execute([$bid_amount, $user_id, $auction_id]);

$_SESSION['success'] = "تمت المزايدة بنجاح!";
header("Location: auction_details.php?auction_id=" . $auction_id);
exit;
