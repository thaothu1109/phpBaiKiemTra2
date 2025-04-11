
<?php
session_start();

// Kiểm tra trạng thái session
if (session_status() !== PHP_SESSION_ACTIVE) {
    die("Không thể khởi tạo phiên làm việc. Vui lòng thử lại sau.");
}

// Tạo CSRF token nếu chưa có
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Cấu hình timeout (tính bằng giây)
$timeout = 3600; // 1 giờ

// Kiểm tra nếu đã lưu thời gian hoạt động trước đó
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $duration = time() - $_SESSION['LAST_ACTIVITY'];

    if ($duration > $timeout) {
        // Hết hạn → hủy session
        session_unset();
        session_destroy();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Phiên làm việc của bạn đã hết hạn. Vui lòng đăng nhập lại.'];
        header("Location: login.php");
        exit();
    }
}

// Cập nhật thời gian hoạt động mới nhất
$_SESSION['LAST_ACTIVITY'] = time();
// Regenerate session ID để giảm nguy cơ tấn công session fixation
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} elseif (time() - $_SESSION['CREATED'] > 300) { // 5 phút
    session_regenerate_id(true); // Tạo session ID mới
    $_SESSION['CREATED'] = time();
}
// Kết nối đến cơ sở dữ liệu
?>