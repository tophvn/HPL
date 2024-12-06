<?php
include('../../config/config.php'); 
session_start(); 
$_SESSION = [];
// Hủy phiên
session_destroy();
header("Location: " . BASE_URL . "index.php");
exit();
?>

