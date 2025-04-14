<?php
// Kiểm tra nếu không phải HTTPS, chuyển hướng sang HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit();
}
?>
<?php
session_start();
include('connect.php'); // Kết nối đến cơ sở dữ liệu
include('session.php'); // Bao gồm mã kiểm tra session

// Kiểm tra CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit();
}

    // Kiểm tra mật khẩu mới và xác nhận mật khẩu
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Mật khẩu xác nhận không khớp.'];  
        header ("Location: reset_password.php");    
        exit();  
       
    }
    //
    // Kiểm tra độ mạnh của mật khẩu
    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPassword)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt.'];
        header("Location: reset_password.php");
        exit();
    }

    // Kiểm tra email trong session
    if (!isset($_SESSION['otp_email']) || !filter_var($_SESSION['otp_email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Email không hợp lệ. Vui lòng thử lại.'];
        header("Location: reset_password.php");
      
        exit();
    }

    // Cập nhật mật khẩu mới trong cơ sở dữ liệu
    
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Mã hóa mật khẩu
$email = $_SESSION['otp_email']; // Lấy email từ session

$stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
$stmt->bindParam(':password', $hashedPassword);
$stmt->bindParam(':email', $email);

if (!$stmt->execute()) {
    error_log("Lỗi khi cập nhật mật khẩu cho email: $email");
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Có lỗi xảy ra khi cập nhật mật khẩu.'];
    header("Location: reset_password.php");
    exit();
}

// Cập nhật thời điểm thay đổi mật khẩu vào bảng user_log
$stmt = $pdo->prepare("SELECT username FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $username = $user['username'];
    $updateLogQuery = "UPDATE user_log 
                       SET last_password_change = NOW() 
                       WHERE user_id = (SELECT id FROM users WHERE username = :username LIMIT 1)";
    $updateLogStmt = $pdo->prepare($updateLogQuery);
    $updateLogStmt->bindValue(':username', $username, PDO::PARAM_STR);
    $updateLogStmt->execute();
}

// Xóa email khỏi session và chuyển hướng đến trang reset_password
unset($_SESSION['otp_email']);
$_SESSION['message'] = ['type' => 'success', 'text' => 'Đặt lại mật khẩu thành công. Bạn có thể đăng nhập với mật khẩu mới.'];
header("Location: reset_password.php?redirect_to=login");
exit();
// 
?>
//
