<?php
header('Content-type: text/html; charset=utf-8');

// Hàm thực hiện yêu cầu POST
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// Kết nối đến cơ sở dữ liệu để lấy thông tin đơn hàng
include('../config/database.php');
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$conn = Database::getConnection();
$user_id = $_SESSION['user']['user_id'];

// Truy vấn để lấy sản phẩm từ giỏ hàng và tính tổng tiền
$product_query = "SELECT products.price AS discount_price, cart_item.quantity 
FROM products JOIN cart_item ON products.product_id = cart_item.product_id 
JOIN cart ON cart_item.cart_id = cart.cart_id WHERE cart.user_id = $user_id";
$result = $conn->query($product_query);

$products = $result->fetch_all(MYSQLI_ASSOC);
$total = array_reduce($products, fn($carry, $item) => $carry + $item['discount_price'] * $item['quantity'], 0);

// Tính toán phí giao hàng
$shipping_fee = 0;
$total_amount = $total + $shipping_fee; 

// Lấy tổng tiền từ query string
$amount = $_GET['amount'] ?? $total_amount; // Sử dụng tổng tiền đã tính toán

$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

// Định nghĩa thông tin xác thực và các biến khác
$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$orderInfo = "Thanh toán qua MoMo";
$orderId = time() . "";
$redirectUrl = "http://localhost:8080/ShopThoiTrang/views/checkout.php";
$ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
$extraData = "";

// Tạo các tham số yêu cầu
$requestId = time() . "";
$requestType = "captureWallet";  

// Tạo chuỗi thô để ký
$rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";

// Ký yêu cầu
$signature = hash_hmac("sha256", $rawHash, $secretKey);

// Chuẩn bị dữ liệu gửi đi trong yêu cầu API
$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,  // Sử dụng tổng tiền đã tính toán
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);

// Gửi yêu cầu
$result = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true); 

// Kiểm tra nếu payUrl tồn tại và chuyển hướng
if (isset($jsonResult['payUrl'])) {
    header('Location: ' . $jsonResult['payUrl']);
    exit;
} else {
    echo "Lỗi: Không tìm thấy payUrl trong phản hồi.";
}
?>