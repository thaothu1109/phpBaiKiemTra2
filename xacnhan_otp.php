<?php
session_start();

// Kiểm tra CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Tạo lại CSRF token mới
    $_SESSION['message'] = ['type' => 'error', 'text' => 'CSRF token không hợp lệ'];
    header("Location: fgpassword.php");
    exit();
}
// Kiểm tra xem OTP và email đã được lưu trong csdl hay chưa
if (!isset($_SESSION['otp_email']) || !filter_var($_SESSION['otp_email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Email không hợp lệ. Vui lòng thử lại.'];
    header("Location: fgpassword.php");
    exit();
}

// Lấy thông tin OTP từ cơ sở dữ liệu
require 'connect.php'; // Kết nối cơ sở dữ liệu
$stmt = $pdo->prepare("SELECT otp, otp_created_at FROM users WHERE email = :email");
$stmt->execute(['email' => $_SESSION['otp_email']]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Không tìm thấy thông tin người dùng.'];
    header("Location: fgpassword.php");
    exit();
}
// Kiểm tra thời gian hiệu lực của OTP (ví dụ: 5 phút)
if (time() - strtotime($data['otp_created_at']) > 300) { // 300 giây = 5 phút
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.'];
    header("Location: fgpassword.php");
    exit();
}
// Lấy mã OTP người dùng nhập
$userOtp = $_POST['otp'] ;

// Kiểm tra định dạng OTP
if (!ctype_digit($userOtp) || strlen($userOtp) !== 6) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã OTP không hợp lệ.'];
    header("Location: fgpassword.php?otp_sent=true");
    exit();
}

// Xác thực OTP, Sử dụng hàm hash_equals() để so sánh mã OTP nhằm tránh các cuộc tấn công timing attack.
if (password_verify($userOtp, $data['otp'])) {
    // OTP hợp lệ
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Xác thực OTP thành công! Bạn có thể đặt lại mật khẩu.'];

    // Xóa OTP khỏi cơ sở dữ liệu sau khi sử dụng
    $stmt = $pdo->prepare("UPDATE users SET otp = NULL, otp_created_at = NULL WHERE email = :email");
    $stmt->execute(['email' => $_SESSION['otp_email']]);

    header("Location: reset_password.php"); // Chuyển đến trang đặt lại mật khẩu
    exit();
} else {
    // OTP không hợp lệ
    error_log("OTP không chính xác cho email: " . $_SESSION['otp_email']);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã OTP không chính xác. Vui lòng thử lại.'];
    header("Location: fgpassword.php?otp_sent=true");
    exit();
}

?>
