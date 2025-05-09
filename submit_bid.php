<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("يجب تسجيل الدخول لتقديم مزايدة.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $auction_id = (int) $_POST['auction_id'];
    $bid_amount = (float) $_POST['bid_amount'];

    $host = 'sql200.infinityfree.com';
    $dbname = 'if0_38543174_my_db';
    $username = 'if0_38543174';
    $password = 'MDH6WuEkvPyL';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // التحقق من أن المزاد نشط
        $stmt = $pdo->prepare("SELECT * FROM auction WHERE auction_id = ? AND status = 'active' AND NOW() BETWEEN start_date AND end_date");
        $stmt->execute([$auction_id]);
        $auction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$auction) {
            die("المزاد غير متاح.");
        }

        // جلب أعلى مزايدة حالية
        $stmt = $pdo->prepare("SELECT MAX(bid_amount) FROM bid WHERE auction_id = ?");
        $stmt->execute([$auction_id]);
        $maxBid = $stmt->fetchColumn();
        $minBid = $maxBid ? $maxBid + 1 : $auction['starting_price'];

        if ($bid_amount < $minBid) {
            die("المبلغ يجب أن يكون أعلى من المزايدة الحالية.");
        }

        // إضافة المزايدة
        $stmt = $pdo->prepare("INSERT INTO bid (user_id, auction_id, bid_amount, bid_time) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $auction_id, $bid_amount]);

        header("Location: auction_details.php?auction_id=$auction_id");
        exit;
    } catch (PDOException $e) {
        die("خطأ: " . $e->getMessage());
    }
}
