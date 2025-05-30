<?php
// Kiểm tra nếu không phải HTTPS, chuyển hướng sang HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit();
}
?>
<?php
    // Tạo session 
    include('session.php');
    ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <!-- Link to Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
         /* Reset các style mặc định */
         * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Căn giữa toàn bộ trang */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color:#00FF00​;
        }

        /* Container chính bao gồm ảnh và form */
        .main-container {
            display: flex;
            align-items: center;
            gap: 70px; /* Tăng khoảng cách giữa ảnh và form */
        }

        /* Style ảnh nhân vật */
        .character-image {
            width: 400px; /* Tăng kích thước ảnh nhân vật */
            height: auto;
        }

        /* Khung chứa toàn bộ form đăng nhập */
        .login-container {
            text-align: center;
            width: 800px; /* Tăng kích thước của form */
        }

        /* Style logo */
        .logo {
            width: 200px; /* Tăng kích thước logo */
            margin-bottom: 30px;
        }

        /* Style các trường input */
        .login-input {
            width: 100%;
            padding: 20px; /* Tăng kích thước padding */
            margin: 15px 0;
            border: 2px solid #006400;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
        }

        /* Style nút đăng nhập */
        .login-button {
            width: 50%;
            padding: 20px; /* Tăng kích thước padding */
            background-color: #006400;
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-size: 18px; /* Tăng kích thước chữ */
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .login-button:hover {
            background-color: #008c9e;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: -10px;
            border-radius: 20px;
            font-size: 14px;
            text-align: left;
        }

        .success {
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            font-size: 15px;
            text-align: left;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            width: 80%;
            margin-left: 42px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }
        .register-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link a {
            color: #006400;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
        .eye-icon {
            position: absolute;
            right: 15px;
            top: 35px;
            cursor: pointer;
            font-size: 20px;
            color:#006400;
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

    </style>
</head>
<body>

<div class="login-container">
    <div class="main-container">
        <!-- Ảnh nhân vật minh họa -->
        <img src="TOEIC-logo.jpg" alt="Toiec" class="character-image">
    <div class="login-container">
            <!-- Logo của English with Toiec -->
            <img src="logo-manhha.png" alt="Toiec" class="logo">
            <h3 style = "margin-bottom: 20px; margin-top: -30px;margin-right: 10px; color: #006400;">Đăng nhập</h3>
    <!-- Hiển thị lỗi nếu có -->
    <?php
    if (isset($_SESSION['login_error'])) {
    echo "<div class='error'>" . $_SESSION['login_error'] . "</div>";
    unset($_SESSION['login_error']); // Xóa sau khi hiển thị
    }
    ?>
    
    <!-- Form đăng nhập -->
    <form method="POST" id="login-form" action="xllogin.php">
    <?php $is_locked = isset($_SESSION['locked']) && $_SESSION['locked'] > time(); ?>
    <input type="text" name="username_or_email" class="login-input" placeholder="Tên người dùng hoặc Email" required>

    <div style="position: relative;">
    <input type="password" id="password" name="password" class="login-input" placeholder="Mật khẩu" required minlength="5">
    <i class="fas fa-eye-slash eye-icon" id="togglePassword" onclick="togglePassword()"></i>
</div>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePassword');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }
</script>

    <div class="forgot-password">
        <a style = "color: #006400;" href="fgpassword.php">Quên mật khẩu?</a>
    </div>

    <!-- Token ẩn -->
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <button  id="login-button" type="submit" class="login-button" >Đăng nhập</button>
    </form>
    <script>
    const isLocked = <?php echo $is_locked ? 'true' : 'false'; ?>;

    const loginForm = document.getElementById('login-form');
    const loginButton = document.getElementById('login-button');

    if (isLocked) {
        loginButton.addEventListener('click', function(e) {
            e.preventDefault(); // chặn gửi form
            alert("Bạn không thể gửi được vì đã bị vô hiệu hóa. Vui lòng thử lại sau 5 phút.");
        });
    }
    </script>
    <div class="register-link">
        <p>Chưa có tài khoản? <a style = "color: #006400;" href="signup.php">Đăng ký</a></p>
    </div>
</div>
