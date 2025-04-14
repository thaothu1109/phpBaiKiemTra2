<?php
// Kiểm tra nếu không phải HTTPS, chuyển hướng sang HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit();
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user'])) {
    // Nếu đã đăng nhập, chuyển hướng đến trang chủ
    header("Location: trangchu.php");
    exit(); // Dừng mã ở đây để tránh tiếp tục xử lý
} else {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng mã ở đây để tránh tiếp tục xử lý
}
?>
