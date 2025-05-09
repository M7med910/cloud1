<?php
// استرجاع الصورة من قاعدة البيانات
$sql = "SELECT image FROM product WHERE auction_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product && $product['image']) {
    // إظهار الصورة
    echo '<img src="data:image/jpeg;base64,' . base64_encode($product['image']) . '" alt="صورة المنتج">';
} else {
    // في حالة عدم وجود صورة
    echo '<img src="default.jpg" alt="صورة افتراضية">';
}
?>
