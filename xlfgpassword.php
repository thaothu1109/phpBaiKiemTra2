<?php
session_start();
include('connect.php'); // Kết nối cơ sở dữ liệu
require 'vendor/autoload.php'; // Đảm bảo autoload được tải cho PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    // Kiểm tra CSRF token
    error_log("CSRF token trong session: " . ($_SESSION['csrf_token'] ?? 'Không tồn tại'));
    error_log("CSRF token trong form: " . ($_POST['csrf_token'] ?? 'Không tồn tại'));

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        unset($_SESSION['csrf_token']); // xóa token cũ
        $_SESSION['message'] = ['type' => 'error', 'text' => 'CSRF token không hợp lệ'];
        header("Location: fgpassword.php");
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = $_POST['username_or_email'];

    // kiểm tra để đảm bảo rằng $username_or_email không rỗng trước khi thực hiện truy vấn
    if (empty($username_or_email)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => "Vui lòng nhập tên người dùng hoặc email."];
        header("Location: fgpassword.php");
        exit();
    }

    // Kiểm tra xem người dùng có tồn tại trong cơ sở dữ liệu không
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username_or_email OR email = :username_or_email");
    $stmt->bindParam(':username_or_email', $username_or_email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Tạo mã OTP ngẫu nhiên 6 chữ số
        $otp = rand(100000, 999999);

        // Lưu mã OTP vào cơ sở dữ liệu, bảo mật hơn khi lưu ở session
        // Sử dụng password_hash để mã hóa mã OTP trước khi lưu vào cơ sở dữ liệu
        $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET otp = :otp, otp_created_at = NOW() WHERE email = :email");
        if (!$stmt->execute(['otp' => $hashedOtp, 'email' => $user['email']])) {
            $_SESSION['message'] = ['type' => 'error', 'text' => "Không thể lưu mã OTP. Vui lòng thử lại sau."];
            header("Location: fgpassword.php");
            exit();
        }
        // Lưu email vào session để xác minh sau này
        $_SESSION['otp_email'] = $user['email'];
        
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cấu hình SMTP của Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'thaothumai04@gmail.com'; // Email của bạn
            $mail->Password = 'rxzy vugk flan xmpc'; // Mật khẩu ứng dụng Gmail của bạn
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587; 

            // Đặt người gửi và người nhận
            $mail->setFrom('thaothumai04@gmail.com', 'Toeic Manh Ha');
            $mail->addAddress($user['email']); 

            // Cấu hình nội dung email
            $mail->isHTML(true);
            $mail->Subject = 'Ma OTP de thay doi mat khau';
            $mail->Body = "<p>Đây là mã OTP của bạn: <strong>$otp</strong></p><p>Vui lòng nhập mã này để tiếp tục thay đổi mật khẩu của bạn.</p>";

            // Gửi email
            if ($mail->send()) {
                $_SESSION['message'] = ['type' => 'success', 'text' => "Mã OTP đã được gửi tới email của bạn."];
                $_SESSION['otp_sent'] = true; // Đánh dấu OTP đã được gửi
                header("Location: xacnhan_otp.php?otp_sent=true"); // Chuyển hướng đến trang xác minh OTP
                exit();
            }
        } catch (Exception $e) {
            error_log("Lỗi khi gửi email OTP: " . $mail->ErrorInfo);
            $_SESSION['message'] = ['type' => 'error', 'text' => "Lỗi khi gửi email OTP: " . $mail->ErrorInfo];
            header("Location: fgpassword.php");
            exit();
        }
    } else {
        // Người dùng không tồn tại
        $_SESSION['message'] = ['type' => 'error', 'text' => "Người dùng hoặc email không tồn tại"];
        header("Location: fgpassword.php");
        exit();
    }
} else {
    // Trường hợp không phải là phương thức POST
    header("Location: fgpassword.php");
    exit();
}

?>