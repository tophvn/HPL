<?php
$vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
$vnp_Returnurl = "https://localhost:8080/views/vnpay_php/vnpay_return.php";
$vnp_TmnCode = "8LS0OVCA"; // Mã website tại VNPAY 
$vnp_HashSecret = "CK4LUM89NFUA6YSBMR6JC3RA7FUUVMSH"; // Chuỗi bí mật

$vnp_TxnRef = rand(00, 99999999); // Mã đơn hàng
$vnp_OrderInfo = 'Nội dung thanh toán';

// Lấy tham số từ GET
$vnp_OrderType = isset($_GET['order_type']) ? $_GET['order_type'] : 'default_order_type';
$vnp_Amount = isset($_GET['amount']) ? $_GET['amount'] * 100 : 0;

// Kiểm tra nếu amount là số
if (!is_numeric($vnp_Amount)) {
    $vnp_Amount = 0;
}

$vnp_Locale = 'vn';
$vnp_BankCode = 'NCB';
$vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

$inputData = array(
    "vnp_Version" => "2.1.0",
    "vnp_TmnCode" => $vnp_TmnCode,
    "vnp_Amount" => $vnp_Amount,
    "vnp_Command" => "pay",
    "vnp_CreateDate" => date('YmdHis'),
    "vnp_CurrCode" => "VND",
    "vnp_IpAddr" => $vnp_IpAddr,
    "vnp_Locale" => $vnp_Locale,
    "vnp_OrderInfo" => $vnp_OrderInfo,
    "vnp_OrderType" => $vnp_OrderType,
    "vnp_ReturnUrl" => $vnp_Returnurl,
    "vnp_TxnRef" => $vnp_TxnRef
);

if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}

$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}

// Chuyển hướng đến trang thanh toán
header('Location: ' . $vnp_Url);
die();
?>