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
#include('session.php');
// Kiểm tra thông báo trong session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
    $redirectToLogin = isset($_GET['redirect_to']) && $_GET['redirect_to'] === 'login'; // Kiểm tra tham số chuyển hướng
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(200, 237, 200);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-password-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .reset-password-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .reset-password-container input[type="password"] {
        width: 100%;
        padding: 15px; /* Tăng kích thước padding */
        margin-bottom: 25px;
        border: 2px solid #006400; /* Thêm khung màu xanh */
        border-radius: 20px;
        outline: none;
        font-size: 15px; /* Tăng kích thước chữ */
        transition: border-color 0.3s; /* Hiệu ứng chuyển đổi màu khung */

        
    }
        .reset-password-container input[type="password"]::placeholder {
        font-style: italic; /* Chữ in nghiêng */
        font-size: 0.9em; /* Cỡ chữ nhỏ hơn */
        color: #888; /* Màu chữ placeholder (tùy chọn) */
    }

        .reset-password-container button {
            width: 40%;
    padding: 12px;
    background-color: #006400;
    color: white;
    border: none;
    border-radius: 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
        }

        .reset-password-container button:hover {
            background-color:rgb(1, 77, 1);
        }
        input[type="text"] {
    width: 100%;
            padding: 15px; /* Tăng kích thước padding */
           margin-bottom: 20px;
            border: 2px solid  #006400;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
}

        .error, .success {
            justify-content: center;
            align-items: center;
            width: 80%;
            padding: 10px;
    margin-bottom: 20px;
    margin-left: 30px;
    border-radius: 20px;
    font-size: 14px;

        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
    label {
    display: block;
    margin-bottom: 8px;
    margin-left: 60px;
    text-align: left;
    font-size: 16px;
    color: #0c046d;
    }
    .logo {
            width: 150px; /* Tăng kích thước logo */
            margin-bottom: 30px;
        }
        a.back-to-login {
    display: inline-block;
    margin-top: 20px;
    color: #00bcd4;
    text-decoration: none;
    font-size: 14px;
}

a.back-to-login:hover {
    text-decoration: underline;
}
button:hover {
    background-color:rgb(2, 69, 2); /* Màu tối hơn khi hover */
}
.password-wrapper {
    position: relative; /* Đặt vị trí tương đối để định vị biểu tượng mắt */
    width: 80%; /* Thu hẹp chiều rộng của wrapper */
    margin: 15px auto; /* Căn giữa wrapper */
}

.password-wrapper input {
    width: 100%; /* Chiều rộng của ô nhập */
    padding: 12px 40px 12px 15px; /* Chừa khoảng trống bên phải cho biểu tượng mắt */
    border: 2px solid #006400; /* Khung màu xanh */
    border-radius: 25px; /* Bo tròn các góc */
    font-size: 15px; /* Kích thước chữ */
    box-sizing: border-box; /* Đảm bảo padding không làm thay đổi kích thước ô nhập */
    outline: none; /* Loại bỏ viền khi focus */
    transition: border-color 0.3s; /* Hiệu ứng chuyển đổi màu khung */
}


    </style>

    <script>
        // Tự động chuyển hướng sau khi hiển thị thông báo Đặt lại mật khẩu thành công
        function redirectToLogin() {
            setTimeout(function () {
                window.location.href = "login.php";
            }, 3000); // Chuyển hướng sau 5 giây
        }
    </script>
</head>
<body>
    <div class="reset-password-container">
    <img src="logo-manhha.png" alt="Toeic Manh Ha" class="logo">
    <h3 style = "margin-bottom: 20px; margin-top: -30px; color: #0c046d;">Đặt lại mật khẩu</h3>
        <!-- Hiển thị thông báo -->
        <?php if (isset($message)): ?>
            <div class="<?php echo htmlspecialchars($message['type']); ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
            <?php if (!empty($redirectToLogin)): ?>
                <script>
                    redirectToLogin(); // Gọi hàm chuyển hướng
                </script>
            <?php endif; ?>
        <?php endif; ?>

        <form action="xlrspw.php" method="POST">
        <div class="input-container">
            <label for="new_password">Mật khẩu mới:</label>
            <div class="password-wrapper">
                <input type="password" id="new_password" name="new_password" placeholder="Mật khẩu mới" required minlength="8"
                    pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                    title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            </div>
        </div>

        <div class="input-container">
            <label for="confirm_password">Xác nhận mật khẩu mới:</label>
            <div class="password-wrapper">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required minlength="8"
                    pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                    title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            </div>
         </div>

         <input type="hidden" name="email" value="<?php echo isset($_SESSION['otp_email']) ? htmlspecialchars($_SESSION['otp_email']) : ''; ?>">        
         <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
         <button type="submit">Đặt Lại Mật Khẩu</button>
        </form>
        <a href="login.php" class="back-to-login" style="color: #0c046d;">Quay lại trang đăng nhập</a>
    </div>

   
</body>
</html>
