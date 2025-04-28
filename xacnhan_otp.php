<?php
// Nếu không phải HTTPS thì chuyển hướng sang HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit();
}
?>

<?php
require_once('session.php'); // Gọi session

// Kiểm tra CSRF token 
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']); // Hủy token sau kiểm tra để tránh reuse
    $_SESSION['message'] = ['type' => 'error', 'text' => 'CSRF token không hợp lệ'];
    header("Location: fgpassword.php");
    exit();
}

// Kiểm tra session chứa OTP và email có tồn tại không 
if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_email'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Vui lòng nhập đúng yêu cầu!'];
    header("Location: fgpassword.php");
    exit();
}

// Kiểm tra thời gian hết hạn OTP
if (!isset($_SESSION['otp_created_at']) || (time() - $_SESSION['otp_created_at']) > 300) { // 5 phút
    unset($_SESSION['otp']);
    unset($_SESSION['otp_created_at']);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.'];
    header("Location: fgpassword.php");
    exit();
}

// Kiểm tra số lần nhập OTP
if (!isset($_SESSION['otp_attempts'])) {
    $_SESSION['otp_attempts'] = 0; // Khởi tạo lần đầu
}
$_SESSION['otp_attempts']++;

// Nếu nhập sai quá 5 lần thì reset OTP
if ($_SESSION['otp_attempts'] > 5) {
    unset($_SESSION['otp']);
    unset($_SESSION['otp_created_at']);
    unset($_SESSION['otp_attempts']);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Bạn đã nhập sai quá nhiều lần. Vui lòng nhập email để lấy mã mới.'];
    header("Location: fgpassword.php");
    exit();
}

// Xác thực OTP
$userOtp = $_POST['otp']; // Lấy mã OTP người dùng nhập

if (hash_equals((string)$_SESSION['otp'], (string)$userOtp)) {
    // Nếu OTP chính xác

    unset($_SESSION['otp']);           // Xóa OTP sau khi xác thực thành công
    unset($_SESSION['otp_created_at']); // Xóa thời gian OTP
    unset($_SESSION['otp_attempts']);   // Xóa số lần nhập OTP

    session_regenerate_id(true);        // Đổi session ID để bảo mật thêm

    $_SESSION['message'] = ['type' => 'success', 'text' => 'Xác thực OTP thành công! Bạn có thể đặt lại mật khẩu.'];
    header("Location: reset_password.php"); // Điều hướng đến trang reset password
    exit();
} else {
    // Nếu OTP không đúng
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã OTP không chính xác. Vui lòng thử lại.'];
    header("Location: fgpassword.php?otp_sent=true"); // Quay lại trang nhập OTP
    exit();
}
?>
