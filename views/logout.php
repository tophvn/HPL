<?php
session_start(); // Bắt đầu phiên
// Hủy tất cả các biến phiên
$_SESSION = [];
// Hủy phiên
session_destroy();
header("Location: ../index.php"); 
exit();
?>

