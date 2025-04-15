<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo "<div class='success'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<style>
    .success {
        color: #27ae60;
        background-color: #eafaf1;
        border: 1px solid #27ae60;
        padding: 10px;
        margin: 10px 0;
        border-radius: 10px; /* Bo góc tròn */
        text-align: center;
        position: relative;
        animation: fadeOut 3s forwards; /* Thêm hiệu ứng mờ dần */
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
</style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toeic Mạnh Hà</title>
    <link href='assets/img/logotieude2.svg' rel='icon' type='image/x-icon' />
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/home-responsive.css">
    <link rel="stylesheet" href="./assets/css/toast-message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
     <script>
        
        function loadHtml(page) {
            // Cuộn lên đầu trang một cách mượt
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Ẩn các phần khác nếu cần

            document.getElementById('trangchu').classList.add('hide'); // add hide là ẩn trang chủ, remove hide hiển thị trang chủ
            document.getElementById('order-history').classList.remove('open'); //remove open ẩn trang order, add open là hiển thị trang order
            document.getElementById('account-user').classList.remove('open');
            document.getElementById('content').classList.add('open');

            // Sử dụng fetch để tải nội dung từ tệp HTML
            fetch(page)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.text();
                })
                .then(data => {
                    // Đặt nội dung HTML vào phần 'content'
                    document.getElementById('content').innerHTML = data;
                })
                .catch(error => {
                    // Xử lý lỗi nếu có
                    document.getElementById('content').innerHTML = 'Không thể tải nội dung: ' + error.message;
                });
        }
        </script>
<body>
    <!--Header-->   

    <header>
        <div class="header-top">
            <div class="container">
                <div class="header-top-left">
                    <ul class="header-top-list">
                        <li><a href=""><i class="fa-regular fa-phone"></i> 0123 456 789 (miễn phí)</a></li>
                        <li><a href=""><i class="fa-light fa-location-dot"></i> Xem vị trí cửa hàng</a></li>
                    </ul>
                </div>
                <div class="header-top-right">
                    <ul class="header-top-list">
                        <li><a href="">Giới thiệu</a></li>
                        <li><a href="">Cửa hàng</a></li>
                        <li><a href="">Chính sách</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="header-middle">
            <div class="container">
                <div class="header-middle-left">
                    <div class="header-logo">
                        <a href="">
                            <img src="logo-manhha.png" alt="" class="header-logo-img">
                        </a>
                    </div>
                </div>
                <div class="header-middle-center">
                    <form action="" class="form-search">
                        <span class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="form-search-input" placeholder="Tìm kiếm khóa học..."
                            oninput="searchProducts()">
                        <button class="filter-btn"><i class="fa-solid fa-filter"></i><span>Lọc</span></button>
                    </form>
                </div>
                <div class="header-middle-right">
                    <ul class="header-middle-right-list">
                        <li class="header-middle-right-item dnone open" onclick="openSearchMb()">
                            <div class="cart-icon-menu">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </div>
                        </li>
                        <li class="header-middle-right-item close" onclick="closeSearchMb()">
                            <div class="cart-icon-menu">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </div>
                        </li>
                        <li class="header-middle-right-item dropdown open">
                            <i class="fa-solid fa-user"></i>
                            <div class="auth-container">
                                <span class="text-tk">Tài khoản </span>
                            </div>
                            <ul class="header-middle-right-menu">
                                <li><a id="login" href="user_password.php"><i class="fa-solid fa-right-to-bracket"></i> Đổi mật khẩu</a></li>
                                <li><a id="signup" href="logout.php"><i class="fa-solid fa-user-plus"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <nav class="header-bottom">
        <div class="container">
            <ul class="menu-list">
                <li class="menu-list-item"><a href="#" class="menu-link">Trang chủ</a></li>
                <li class="menu-list-item"><a href="javascript:;" class="menu-link">Tài liệu</a></li>
                <li class="menu-list-item" onclick="showCategory('TOEIC 450')"><a href="javascript:;" class="menu-link">TOEIC 450</a></li>
                <li class="menu-list-item" onclick="showCategory('TOEIC 650')"><a href="javascript:;" class="menu-link">TOEIC 650</a></li>
                <li class="menu-list-item" onclick="showCategory('TOEIC 800')"><a href="javascript:;" class="menu-link">TOEIC 800</a></li>
                <li class="menu-list-item" onclick="showCategory('TOEIC SPEAKING')"><a href="javascript:;" class="menu-link">TOEIC SP</a></li>
                <li class="menu-list-item" onclick="showCategory('TOEIC WRITING')"><a href="javascript:;" class="menu-link">TOEIC WR</a></li>
                <li class="menu-list-item" onclick="showCategory('TOEIC 4SKILLS')"><a href="javascript:;" class="menu-link">TOEIC 4SKILLS</a></li>
                <li class="menu-list-item dropdown">
             <li class="menu-list-item dropdown">
            <a href="javascript:;" class="menu-link">Về chúng tôi</a>
            <div class="dropdown-content">
             <a href="javascript:;" onclick="loadHtml('gioithieu.html')">Giới thiệu</a>
              <a href="javascript:;" onclick="loadHtml('tintuc.html')">Tin tức</a>
              <a href="javascript:;" onclick="loadHtml('dieukhoan.html')">Điều khoản</a>
              <a href="javascript:;" onclick="loadHtml('lienhe.html')">Liên hệ</a>
            </div>
    </li>
    </ul>
        </div>
    </nav>
     <!-- Tính năng lọc  -->
    <div class="advanced-search">
        <div class="container">
            <div class="advanced-search-category">
                <span>Phân loại </span>
                <select name="" id="advanced-search-category-select" onchange="searchProducts()">
                    <option>ETS 2025</option>
                    <option>ETS 2024</option>
                    <option>ETS 2023</option>
                    <option>ETS 2022</option>
                    <option>ETS 2021</option>
                    <option>ETS 2020</option>
                </select>
            </div>            
        </div>
    </div>
    <main class="main-wrapper">
        <div class="container" id="trangchu">
            <div class="home-slider">
                <img src="banner.jpg" alt="">
                <!-- <img src="./assets/img/banner-2.png" alt="">
                <img src="./assets/img/banner-3.png" alt="">
                <img src="./assets/img/banner-4.png" alt="">
                <img src="./assets/img/banner-5.png" alt=""> -->
            </div>
            
            <div class="home-title-block" id="home-title">
                <h2 class="home-title">Khám phá những khóa học mới nhất</h2>
            </div>
            <div class="home-products" id="home-products">
            </div>
            <div class="page-nav">
                <ul class="page-nav-list">
                </ul>
            </div>
         
  </div>
            <div id="content" class="content-wrapper">
                
            </div>    
    </main>

    <div class="modal product-detail">
        <button class="modal-close close-popup"><i class="fa-solid fa-xmark"></i></button>
        <div class="modal-container mdl-cnt" id="product-detail-content">
        </div>
    </div>
    
    
    
    <footer class="footer">
        <div class="widget-area">
            <div class="container">
                <div class="widget-row">
                    <div class="widget-row-col-1">
                        <h3 class="widget-title">Về chúng tôi</h3>
                        <div class="widget-row-col-content">
                            <p>Toeic Mạnh Hà - Địa chỉ luyện thi TOEIC tin cậy, chất lượng hàng đầu tại Hà Nội</p>
                        </div>
                        <div class="widget-social">
                            <div class="widget-social-item">
                                  <a href="#" class="social-icon" >
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </div>
                            <div class="widget-social-item">
                                <a href="#" class="social-icon" >
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </div>
                            <div class="widget-social-item">
                                 <a href="#" class="social-icon" >
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                            <div class="widget-social-item">
                                 <a href="#" class="social-icon" >
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="widget-row-col">
                        <h3 class="widget-title">Giới thiệu</h3>
                        <ul class="widget-contact">
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <a href="javascript:;" onclick="loadHtml('gioithieu.html')"><span>Về chúng tôi</span></a>  
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <span>Khóa học</span>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                  <a href="javascript:;" onclick="loadHtml('dieukhoan.html')"><span>Điều khoản</span></a>  
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <a href="javascript:;" onclick="loadHtml('lienhe.html')"><span>Liên hệ</span></a>  
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <a href="javascript:;" onclick="loadHtml('tintuc.html')"><span>Tin tức</span></a>  
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="widget-row-col">
                        <h3 class="widget-title">Khóa học</h3>
                        <ul class="widget-contact">
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <a href="javascript:;" onclick="showCategory('TOEIC 450')"><span>TOEIC 450</span></a>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <a href="javascript:;" onclick="showCategory('TOEIC 650')"><span>TOEIC 650</span></a>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <a href="javascript:;" onclick="showCategory('TOEIC 800')"><span>TOEIC 800</span></a>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                   <a href="javascript:;" onclick="showCategory('TOEIC SPEAKING')"><span>TOEIC SP</span></a>
                                </a>
                            </li>
                            <li class="widget-contact-item">
                                <a href="">
                                    <i class="fa-solid fa-arrow-right"></i>
                                    <a href="javascript:;" onclick="showCategory('TOEIC WRITING')"><span>TOEIC WR</span></a>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="widget-row-col-1">
                        <h3 class="widget-title">Liên hệ</h3>
                        <div class="contact">
                            <div class="contact-item">
                                <div class="contact-item-icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="contact-content">
                                    <span>12 Chùa Bộc, Quang Trung, Đống Đa, Hà Nội</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <div class="contact-content contact-item-phone">
                                    <span>0123 456 789</span>
                                    <br />
                                    <span>0987 654 321</span>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-item-icon">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="contact-content conatct-item-email">
                                    <span>abc@domain.com</span><br />
                                    <span>infoabc@domain.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="copyright-wrap">
        <div class="container">
            <div class="copyright-content">
                <p>Bản quyền từ TOEIC Mạnh Hà</p>
            </div>
        </div>
    </div>
    <div class="back-to-top">
        <a href="#"><i class="fa-solid fa-arrow-up"></i>
    </div>
    <div class="checkout-page">
        <div class="checkout-header">
            <div class="checkout-return">
                <button onclick="closecheckout()"><i class="fa-solid fa-arrow-left"></i></button>
            </div>
            <h2 class="checkout-title">Thanh toán</h2>
        </div>
        <main class="checkout-section container">
            <div class="checkout-col-left">
                <div class="checkout-row">
                    <div class="checkout-col-title">
                        Thông tin đơn hàng
                    </div>
                    <div class="checkout-col-content">
                        <div class="content-group">
                            <p class="checkout-content-label">Hình thức giao nhận</p>
                            <div class="checkout-type-order">
                                <button class="type-order-btn active" id="giaotannoi">
                                    <i class="fa-solid fa-truck"
                                        style="--fa-secondary-opacity: 1.0; --fa-primary-color: dodgerblue; --fa-secondary-color: #ffb100;"></i>
                                    Giao tận nơi
                                </button>
                                <button class="type-order-btn" id="tudenlay">
                                    <i class="fa-brands fa-creative-commons-by"
                                        style="--fa-secondary-opacity: 1.0; --fa-primary-color: pink; --fa-secondary-color: palevioletred;"></i>
                                    Tự đến lấy
                                </button>
                            </div>
                        </div>
                        <div class="content-group">
                            <p class="checkout-content-label">Ngày giao hàng</p>
                            <div class="date-order">
                            </div>
                        </div>
                        <div class="content-group chk-ship" id="giaotannoi-group">
                            <p class="checkout-content-label">Chọn đơn vị vận chuyển</p>
                            <div class="delivery-time">
                                <input type="radio" name="giaongay" id="giaongay" class="radio">
                                <label for="giaongay">Giao hàng tiết kiệm</label>
                            </div>
                            <div class="delivery-time">
                                <input type="radio" name="giaongay" id="deliverytime" class="radio">
                                <label for="deliverytime">Giao hàng nhanh</label>
                            </div>
                        </div>
                        <div class="content-group" id="tudenlay-group">
                            <p class="checkout-content-label">Lấy hàng tại chi nhánh</p>
                            <div class="delivery-time">
                                <input type="radio" name="chinhanh" id="chinhanh-1" class="radio">
                                <label for="chinhanh-1">12 Chùa Bộc, Quang Trung, Đống Đa</label>
                            </div>
                            <div class="delivery-time">
                                <input type="radio" name="chinhanh" id="chinhanh-2" class="radio">
                                <label for="chinhanh-2">Học viện Ngân Hàng</label>
                            </div>
                        </div>
                        <div class="content-group">
                            <p class="checkout-content-label">Ghi chú đơn hàng</p>
                            <textarea type="text" class="note-order" placeholder="Nhập ghi chú"></textarea>
                        </div>
                    </div>
                </div>
                <div class="checkout-row">
                    <div class="checkout-col-title">
                        Thông tin người nhận
                    </div>
                    <div class="checkout-col-content">
                        <div class="content-group">
                            <form action="" class="info-nhan-hang">
                                <div class="form-group">
                                    <input id="tennguoinhan" name="tennguoinhan" type="text"
                                        placeholder="Tên người nhận" class="form-control">
                                    <span class="form-message"></span>
                                </div>
                                <div class="form-group">
                                    <input id="sdtnhan" name="sdtnhan" type="text" placeholder="Số điện thoại nhận hàng"
                                        class="form-control">
                                    <span class="form-message"></span>
                                </div>
                                <div class="form-group">
                                    <input id="diachinhan" name="diachinhan" type="text" placeholder="Địa chỉ nhận hàng"
                                        class="form-control chk-ship">
                                    <span class="form-message"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="checkout-col-right">
                <p class="checkout-content-label">Đơn hàng</p>
                <div class="bill-total" id="list-order-checkout">
                </div>
                <div class="bill-payment">
                    <div class="total-bill-order">
                    </div>
                    <div class="policy-note">
                        Bằng việc bấm vào nút “Đặt hàng”, tôi đồng ý với
                        <a href="#" target="_blank">chính sách hoạt động</a>
                        của chúng tôi.
                    </div>
                </div>
                <div class="total-checkout">
                    <div class="text">Tổng tiền</div>
                    <div class="price-bill">
                        <div class="price-final" id="checkout-cart-price-final">0</div>
                    </div>
                </div>
                <button class="complete-checkout-btn">Đặt hàng</button>
            </div>
        </main>
    </div>
    <div id="toast"></div>
    <script src="./js/initialization.js"></script>
    <script src="./js/main.js"></script>
    <script src="./js/checkout.js"></script>
    <script src="./js/toast-message.js"></script>
</body>
</html>
