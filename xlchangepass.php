<?php
session_start();
require_once 'vendor/autoload.php';
include('connect.php');

// Kiểm tra yêu cầu phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Yêu cầu không hợp lệ!";
    exit();
}

// CSRF token kiểm tra
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: user_password.php");
    exit();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    $_SESSION['login_error'] = 'Bạn chưa đăng nhập!';
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$otp = $_POST['otp'] ?? '';
$secret = $_SESSION['secret_2fa'] ?? '';

if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword) || empty($otp)) {
    $_SESSION['login_error'] = 'Vui lòng điền đầy đủ thông tin!';
    header("Location: user_password.php");
    exit();
}

// Kiểm tra mật khẩu mới và xác nhận mật khẩu
if ($newPassword !== $confirmPassword) {
    $_SESSION['login_error'] = 'Mật khẩu mới và xác nhận không khớp!';
    header("Location: user_password.php");
    exit();
}

// Kiểm tra mã OTP
if (!$secret) {
    $_SESSION['login_error'] = 'Chưa thiết lập mã xác thực 2 bước.';
    header("Location: user_password.php");
    exit();
}

$ga = new PHPGangsta_GoogleAuthenticator();
$isValid = $ga->verifyCode($secret, $otp, 2);
if (!$isValid) {
    $_SESSION['login_error'] = 'Mã OTP không hợp lệ!';
    header("Location: user_password.php");
    exit();
}

try {
    // Lấy mật khẩu cũ từ DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $dbPasswordHash = $stmt->fetchColumn();

    if (!$dbPasswordHash || !password_verify($currentPassword, $dbPasswordHash)) {
        $_SESSION['login_error'] = 'Mật khẩu hiện tại không đúng!';
        header("Location: user_password.php");
        exit();
    }

    // Cập nhật mật khẩu mới
    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $update = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
    $update->execute([':password' => $newHash, ':username' => $username]);

    // Ghi nhật ký đổi mật khẩu
    $log = $pdo->prepare("UPDATE user_log SET last_password_change = NOW() 
                          WHERE user_id = (SELECT id FROM users WHERE username = :username)");
    $log->execute([':username' => $username]);

    // Thông báo thành công
    $_SESSION['success_message'] = 'Đổi mật khẩu thành công!';
    header("Location: trangchu.php");
    exit();
} catch (PDOException $e) {
    // Lỗi hệ thống
    $_SESSION['login_error'] = 'Lỗi hệ thống: ' . $e->getMessage();
    header("Location: user_password.php");
    exit();
}
?>
