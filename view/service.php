<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Định nghĩa đường dẫn cơ sở
$BASE_PATH = '/CUULONGRESORT';

// Thiết lập các biến cho SEO
$page_title = "Cửu Long Resort - Trang Chủ";
$page_description = "Khám phá Cửu Long Resort, điểm đến nghỉ dưỡng lý tưởng tại Vĩnh Long với không gian thiên nhiên, phòng nghỉ sang trọng và dịch vụ chuyên nghiệp.";
$page_keywords = "Cửu Long Resort, nghỉ dưỡng Vĩnh Long, khách sạn miền Tây, đặt phòng resort, du lịch miền Tây";
$canonical_url = "http://www.cuulongresort.com/dich-vu";
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="trang-gioi-thieu-cua-Cuu-Long-Resort">
    <meta name="author" content="TonDucThangUniversity">
    <title>About</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/vegas.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/style.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/service.css" rel="stylesheet">
</head>
<body style="background-color: #41554d">
    <main>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="<?php echo $BASE_PATH; ?>./layout/images/LOGO.png" style="width: 50px" class="navbar-brand-image img-fluid" alt="Logo Cửu Long Resort">C|L RESORT
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-lg-auto">
                        <li class="nav-item"><a class="nav-link active" href="../index.php">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">Giới thiệu</a></li>
                        <li class="nav-item"><a class="nav-link" href="service.php">Dịch vụ</a></li>
                        <li class="nav-item"><a class="nav-link" href="tour.php">Tour du lịch</a></li>
                        <li class="nav-item"><a class="nav-link" href="room.php">Đặt phòng</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Liên hệ</a></li>
                    </ul>
                    <div class="ms-lg-3">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a class="btn custom-btn custom-border-btn" href="<?php echo $BASE_PATH; ?>/layout/profile.php">Hồ sơ<i class="bi-person ms-2"></i></a>
                        <?php else: ?>
                            <a class="btn custom-btn custom-border-btn" href="<?php echo $BASE_PATH; ?>/layout/login.php">Đăng nhập<i class="bi-arrow-up-right ms-2"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Free Parking Section -->
        <section class="about-section section-padding" style="position: relative; padding: 0; background-color: #709888; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-4 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-right">
                        <em class="small-text">Secure Parking!</em>
                        <h2 class="text-white mb-3">Hầm Xe Miễn Phí</h2>
                        <p class="text-white">Cửu Long Resort cung cấp dịch vụ hầm giữ xe miễn phí với không gian rộng rãi, hiện đại và an toàn tuyệt đối. Hầm xe được thiết kế để đáp ứng nhu cầu đậu xe của cả xe hơi lẫn xe máy, mang lại sự tiện lợi tối đa cho du khách.</p>
                        <p class="text-white">Nằm ngay bên dưới khu nghỉ dưỡng, hầm giữ xe giúp bạn dễ dàng di chuyển đến các khu vực chức năng khác mà không cần lo lắng về phương tiện cá nhân trong suốt kỳ nghỉ.</p>
                    </div>
                    <div class="col-lg-7 col-12 p-0" data-aos="fade-right">
                        <div class="image-slider" style="max-height: 1500px; max-width: 1000px; overflow: hidden; margin-left: auto;">
                            <img src="../layout/images/service/car.jpg" alt="Hầm xe miễn phí tại Cửu Long Resort" style="width: 100%; height: auto; display: block; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Spacious Garden Section -->
        <section class="about-section section-padding" style="position: relative; padding: 0; background-color: #709888; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-6 col-12 p-0" data-aos="fade-left">
                        <div class="image-slider">
                            <img src="../layout/images/service/nature.jpg" class="slider-img" alt="Sân nghỉ mát tại Cửu Long Resort">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-left">
                        <em class="small-text">Relax in Nature!</em>
                        <h2 class="text-white mb-3">Sân Nghỉ Mát Rộng Rãi</h2>
                        <p class="text-white">Đắm mình trong không gian xanh mát và thoáng đãng tại sân nghỉ mát của Cửu Long Resort – nơi lý tưởng để thư giãn, đọc sách, tản bộ hoặc trò chuyện cùng người thân giữa thiên nhiên trong lành miền sông nước.</p>
                        <p class="text-white">Sân được thiết kế hài hòa với cảnh quan xung quanh, có nhiều ghế nghỉ, bóng râm và lối đi lát đá sạch đẹp, mang đến cảm giác bình yên và dễ chịu cho du khách ở mọi lứa tuổi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Culinary and Events Section -->
        <section class="intro-service-section" style="background-color: #41554d;">
            <div class="container text-center">
                <h2 class="text-white mb-3">Ẩm thực và sự kiện</h2>
                <p class="text-white">
                    Resort cũng tự hào cung cấp đa dạng các dịch vụ ẩm thực và giải trí, từ nhà hàng với thực đơn đặc sắc đậm đà hương vị miền Tây, buffet sáng phong phú, đến quầy bar trên cao sang trọng với tầm nhìn toàn cảnh. Ngoài ra, Cửu Long Resort còn tổ chức các sự kiện ngoài trời, hội nghị trong nhà và các workshop thảo mộc thú vị, tạo nên không gian sôi động và đẳng cấp cho du khách.
                </p>
            </div>
        </section>

        <!-- Hotel Services Section -->
        <section class="hotel-services-section">
            <div class="hotel-service-box">
                <div class="image-box" style="background-image: url('../layout/images/service/res6.jpg');">
                    <div class="overlay">
                        <h3>RESTAURANT</h3>
                        <p>Buffet hải sản đa dạng, từ các món Âu tinh tế đến đặc sản miền Tây đậm đà, mang đến trải nghiệm ẩm thực phong phú và đẳng cấp ngay tại Cửu Long Resort.</p>
                    </div>
                </div>
            </div>
            <div class="hotel-service-box">
                <div class="image-box" style="background-image: url('../layout/images/service/bar.jpg');">
                    <div class="overlay">
                        <h3>SKY BAR</h3>
                        <p>Quầy bar tầng thượng với không gian mở, nơi bạn có thể nhâm nhi cocktail và ngắm nhìn toàn cảnh sông nước lung linh về đêm.</p>
                    </div>
                </div>
            </div>
            <div class="hotel-service-box">
                <div class="image-box" style="background-image: url('../layout/images/service/event2.jpg');">
                    <div class="overlay">
                        <h3>EVENT</h3>
                        <p>Resort chúng tôi cung cấp dịch vụ tổ chức sự kiện chuyên nghiệp với không gian linh hoạt, sang trọng – lý tưởng cho tiệc cưới, hội nghị và các buổi tiệc ngoài trời ấn tượng.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Wellness Section -->
        <section class="intro-service-section" style="background-color: #41554d;">
            <div class="container text-center">
                <h2 class="text-white mb-3">Chăm sóc sức khỏe và thư giãn</h2>
                <p class="text-white">
                    Tại Cửu Long Resort, du khách sẽ được trải nghiệm hệ thống chăm sóc sức khỏe toàn diện với khu spa chuyên nghiệp, phòng gym hiện đại và hồ bơi vô cực sang trọng. Những tiện ích này giúp bạn thư giãn, tái tạo năng lượng và hòa mình vào không gian thiên nhiên thanh bình, mang lại cảm giác cân bằng tuyệt vời cho tinh thần và cơ thể.
                </p>
            </div>
        </section>

        <!-- Wellness Services -->
        <section class="hotel-services-section">
            <div class="hotel-service-box">
                <div class="image-box" style="background-image: url('../layout/images/service/spa3.jpg');">
                    <div class="overlay">
                        <h3>SPA</h3>
                        <p>Resort chúng tôi cung cấp dịch vụ spa thư giãn chuyên nghiệp, nơi du khách được chăm sóc cơ thể và tinh thần bằng liệu pháp trị liệu thiên nhiên trong không gian yên tĩnh.</p>
                    </div>
                </div>
            </div>
            <div class="hotel-service-box">
                <div class="image-box" style="background-image: url('../layout/images/service/gym3.jpeg');">
                    <div class="overlay">
                        <h3>GYM</h3>
                        <p>Resort chúng tôi cung cấp phòng gym hiện đại với đầy đủ trang thiết bị luyện tập cao cấp, giúp du khách duy trì thói quen rèn luyện sức khỏe ngay cả trong thời gian nghỉ dưỡng.</p>
                    </div>
                </div>
            </div>
            <div class="hotel-service-box">
                <div class="image-box" style="background-image: url('../layout/images/service/pool2.jpg');">
                    <div class="overlay">
                        <h3>POOL</h3>
                        <p>Resort chúng tôi cung cấp hồ bơi vô cực rộng lớn trên tầng cao, nơi du khách có thể thư giãn giữa làn nước mát và chiêm ngưỡng toàn cảnh thiên nhiên sông nước thơ mộng trải dài vô tận.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Policy Section -->
        <section class="policy-section" style="background-color: #41554d;">
            <div class="container">
                <h2 class="section-title">Chính sách và cam kết dịch vụ</h2>
                <p class="section-subtitle">
                    Tại Cửu Long Resort, sự hài lòng và an toàn của khách hàng luôn được đặt lên hàng đầu. Chúng tôi cam kết mang đến trải nghiệm dịch vụ hoàn hảo với những chính sách rõ ràng, minh bạch và thân thiện.
                </p>
                <div class="policy-cards">
                    <div class="policy-card" data-aos="fade-up" data-aos-delay="100">
                        <h3>Chính sách bảo vệ</h3>
                        <p>Chúng tôi cam kết bảo mật thông tin cá nhân và bảo vệ quyền lợi của khách hàng theo tiêu chuẩn quốc tế, đảm bảo mọi giao dịch và trải nghiệm đều an toàn tuyệt đối.</p>
                    </div>
                    <div class="policy-card" data-aos="fade-up" data-aos-delay="200">
                        <h3>Cam kết an toàn</h3>
                        <p>Resort áp dụng nghiêm ngặt các quy trình vệ sinh và phòng chống dịch bệnh, đảm bảo môi trường nghỉ dưỡng sạch sẽ, an toàn cho mọi du khách và nhân viên.</p>
                    </div>
                    <div class="policy-card" data-aos="fade-up" data-aos-delay="300">
                        <h3>Chính sách hoàn hủy</h3>
                        <p>Chúng tôi cung cấp chính sách hoàn hủy linh hoạt, hỗ trợ tối đa khách hàng trong các trường hợp thay đổi kế hoạch, giúp quý khách yên tâm đặt phòng và dịch vụ.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container" data-aos="fade-top">
                <div class="row">
                    <div class="col-lg-4 col-12 mb-4">
                        <em class="text-white d-block mb-4">Thông tin liên hệ</em>
                        <strong class="text-white">
                            <i class="bi-geo-alt me-2"></i>10/10 Nguyễn Văn Thiệt, Phường 3, Vĩnh Long, Việt Nam
                        </strong>
                    </div>
                    <div class="col-lg-3 col-12 mb-4">
                        <em class="text-white d-block mb-4">Liên hệ</em>
                        <p class="d-flex mb-1">
                            <strong class="me-2">Số điện thoại:</strong>
                            <a href="tel:0987654321" class="site-footer-link">0987654321</a>
                        </p>
                        <p class="d-flex">
                            <strong class="me-2">Email:</strong>
                            <a href="mailto:CuuLong@gmail.com" class="site-footer-link">CuuLong@gmail.com</a>
                        </p>
                        <h4 class="mt-3">Theo dõi chúng tôi</h4>
                        <div class="social-icon">
                            <a href="https://www.facebook.com" class="social-icon-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.twitter.com" class="social-icon-link"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com" class="social-icon-link"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com" class="social-icon-link"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-12 mb-4">
                        <em class="text-white d-block mb-4">Giờ hoạt động</em>
                        <ul class="opening-hours-list">
                            <li class="d-flex">Thứ 2 - Thứ 6
                                <span class="underline"></span>
                                <strong>7:00 - 23:00</strong>
                            </li>
                            <li class="d-flex">Cuối Tuần
                                <span class="underline"></span>
                                <strong>Cả ngày</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-12 col-12 mt-4">
                        <p class="copyright-text mb-0">
                            © 2025 Cửu Long Resort.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="<?php echo $BASE_PATH; ?>./layout/js/jquery.min.js"></script>
    <script src="<?php echo $BASE_PATH; ?>./layout/js/bootstrap.min.js"></script>
    <script src="<?php echo $BASE_PATH; ?>./layout/js/jquery.sticky.js"></script>
    <script src="<?php echo $BASE_PATH; ?>./layout/js/click-scroll.js"></script>
    <script src="<?php echo $BASE_PATH; ?>./layout/js/vegas.min.js"></script>
    <script src="<?php echo $BASE_PATH; ?>./layout/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>
    <script>
        function openNews(id) {
            document.getElementById(id).classList.add('active');
        }

        function closeNews(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
</body>
</html>