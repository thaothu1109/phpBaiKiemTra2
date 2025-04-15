<?php
require_once 'vendor/autoload.php';
include('session.php'); // Tạo CSRF token
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thay đổi mật khẩu</title>
  <style>
        /* Đặt nền toàn trang */
        body {
            font-family: 'Arial', sans-serif;
            background-color: rgb(200, 237, 200);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container chứa form */
        .login-container {
            background-color: #ffffff;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        /* Tiêu đề form */
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        /* Các trường input */
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-sizing: border-box;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border-color:  #006400;
        }

        /* Nút đăng nhập */
        .login-container button {
            width: 50%;
            padding: 10px;
            background-color:  #006400;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #0288d1;
        }

        /* Hiển thị thông báo lỗi */
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: 20px;
            border-radius: 12px;
            font-size: 14px;
            text-align: left;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: 20px;
            border-radius: 12px;
            font-size: 14px;
            text-align: left;
        }

        /* Nút quay lại */
        .back-button {
            width: 50%;
            padding: 12px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        /* Icon mắt */
        .eye-icon {
            position: absolute;
            right: 15px;
            top: 20px;
           cursor: pointer;
            font-size: 20px;
            color:  #006400;
        }

        /* Link quay lại trang chủ */
        .login-link p {
            font-size: 16px;
            margin-top: 20px;
            color: #333;
        }

        .login-link a {
            color:  #006400;
            text-decoration: none;
      
        }

        .login-link a:hover {
            text-decoration: underline;
        }
        .logo {
            width: 150px; /* Tăng kích thước logo */
          
        }
        .forgot-password {
       text-align: right;
       margin: 10px 0;
        }

        .forgot-password a {
       color: #006400;
       font-size: 14px;
       text-decoration: none;
        }

        .forgot-password a:hover {
       text-decoration: underline;
        }
        .back-to-trangchu {
    width: 40%;
    padding: 10px;
    background-color: none;
    color: white;
    border: none;
    border-radius: 25px;
    font-size: 15px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
}
    a.back-to-trangchu:hover {
        text-decoration: underline;
        color:rgb(19, 64, 215); /* Màu chữ khi hover */
        background-color: white; /* Màu nền khi hover */

    }


    </style>
</head>
<body>
<div class="login-container">
    <img src="logo-manhha.png" alt="Toeic Manh Ha" class="logo">

    <?php
    if (isset($_SESSION['user'])) {
        echo "<p style='font-size: 16px; color:  #0c046d;'>Tài khoản: " . htmlspecialchars($_SESSION['user']) . "</p>";
    }
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['login_error'])) {
        echo "<div class='error'>" . $_SESSION['login_error'] . "</div>";
        unset($_SESSION['login_error']);
    }
    ?>
 <form method="POST" action="xlchangepass.php">
        <div style="position: relative;">
            <input type="password" id="current_password" name="current_password" placeholder="Nhập mật khẩu cũ" required minlength="8">
            <i class="fa-regular fa-eye-slash eye-icon" onclick="togglePassword('current_password', this)"></i>    
        </div>
        <div style="position: relative;">
            <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required minlength="8"
                pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            <i class="fa-regular fa-eye-slash eye-icon" onclick="togglePassword('new_password', this)"></i>
        </div>
        <div style="position: relative;">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required minlength="8"
                pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            <i class="fa-regular fa-eye-slash eye-icon" onclick="togglePassword('confirm_password', this)"></i>
        </div>
        
        <?php
// Khởi tạo Google Authenticator
require_once 'vendor/autoload.php';  // Đảm bảo bạn đã cài đặt thư viện PHPGangsta/GoogleAuthenticator
$ga = new PHPGangsta_GoogleAuthenticator();

// Tạo secret key cho người dùng
$secret = $ga->createSecret();  // Tạo secret key
$_SESSION['secret_2fa'] = $secret;  // Lưu vào session

// Tạo URL mã QR cho Google Authenticator
$qrCodeUrl = $ga->getQRCodeGoogleUrl('ManhHaApp', $secret);  // Tạo URL mã QR

// Hiển thị mã QR
echo "<img src='" . htmlspecialchars($qrCodeUrl) . "' />";
?>
        <input type="text" name="otp" placeholder="Nhập mã OTP từ Google Authenticator" required>
       
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Đổi mật khẩu</button>
    </form>
    <a href="trangchu.php" class="back-to-trangchu" style="color: #006400;">Quay lại </a>

    </div>
</body>
</html>
