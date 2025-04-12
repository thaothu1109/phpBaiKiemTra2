<?php
session_start();        // Bắt đầu session
session_unset();        // Xoá tất cả các biến session
session_destroy();      // Huỷ session hiện tại

// Quay về trang đăng nhập
header("Location: login.php");
exit();
?>