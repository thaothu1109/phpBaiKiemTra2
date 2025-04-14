<?php
// Kiểm tra nếu không phải HTTPS, chuyển hướng sang HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit();
}
session_start();        // Bắt đầu session
session_unset();        // Xoá tất cả các biến session
session_destroy();      // Huỷ session hiện tại

// Quay về trang đăng nhập
header("Location: login.php");
exit();
?>