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
$canonical_url = "http://www.cuulongresort.com/tour-du-lich";
?>


<!DOCTYPE html>
<html lang="vi">
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
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/vegas.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/style.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>./layout/css/tour.css" rel="stylesheet">
    <!-- Canonical URL for SEO -->
    <link rel="canonical" href="https://cuulongresort.com/tour">
    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TouristAttraction",
        "name": "Tour Du Lịch Cửu Long Resort",
        "description": "Khám phá các tour du lịch miền Tây tại Cửu Long Resort, bao gồm tour Lục Tỉnh 4N3Đ, Miền Tây Xanh 3N2Đ và tour một ngày trải nghiệm văn hóa Nam Bộ.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "10/10 Nguyễn Văn Thiệt, Phường 3",
            "addressLocality": "Vĩnh Long",
            "addressCountry": "VN"
        },
        "telephone": "+84987654321",
        "url": "https://cuulongresort.com/tour",
        "image": "https://cuulongresort.com/img/service/TOUR1.jpg"
    }
    </script>
</head>
<body>
    <main>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="<?php echo $BASE_PATH; ?>/layout/images/LOGO.png" style="width: 50px" class="navbar-brand-image img-fluid" alt="Logo Cửu Long Resort">C|L RESORT
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-lg-auto">
                        <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/index.php">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/about.php">Giới thiệu</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/service.php">Dịch vụ</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/tour.php">Tour du lịch</a></li>
                        <li class="nav-item"><a class="nav-link active" href="<?php echo $BASE_PATH; ?>/view/room.php">Đặt phòng</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/contact.php">Liên hệ</a></li>
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
        <!-- Tour 1: Lục Tỉnh 4N3Đ -->
        <section class="about-section section-padding" style="position: relative; padding: 0; background-color:#41554d; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-6 col-12 p-0" data-aos="fade-left">
                        <div class="image-slider" style="max-height: 1000px; max-width: 900px; overflow: hidden;">
                            <img src="../layout/images/service/TOUR1.jpg" alt="Tour Lục Tỉnh 4N3Đ tại Cửu Long Resort" style="width: 100%; max-width: 100%; height: auto; display: block; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-left">
                        <em class="small-text">Explore Now!</em>
                        <h2 class="text-white mb-3">Hành Trình Lục Tỉnh – Khám Phá Miền Tây 4N3Đ</h2>
                        <p class="text-white">Khám phá trọn vẹn vẻ đẹp đa dạng và sôi động của vùng Lục Tỉnh miền Tây trong chuyến hành trình 4 ngày 3 đêm đầy trải nghiệm và khám phá. Bạn sẽ được đặt chân đến những địa danh nổi tiếng như Tiền Giang – vùng đất của vườn trái cây trĩu quả, Cần Thơ – thành phố sầm uất với chợ nổi độc đáo, Bạc Liêu – nơi lưu giữ nhiều dấu ấn văn hóa dân tộc và kiến trúc cổ, Sóc Trăng với những ngôi chùa cổ kính mang đậm nét văn hóa Khmer, Cà Mau – mảnh đất tận cùng cực Nam tổ quốc với rừng đước bạt ngàn, và Châu Đốc – vùng đất thấm đẫm truyền thống và tâm linh.</p>
                        <p class="text-white">Trong suốt hành trình, bạn sẽ có cơ hội hòa mình vào đời sống văn hóa địa phương, thưởng thức ẩm thực đặc sắc với những món ngon đậm đà hương vị miền sông nước, khám phá các điểm đến lịch sử – văn hóa đầy màu sắc và trải nghiệm những hoạt động thú vị như tham quan chợ nổi, khám phá vườn trái cây, tắm bùn khoáng hay ngắm hoàng hôn trên dòng sông thơ mộng.</p>
                        <a class="btn custom-btn custom-border-btn mt-3 me-3">2.590.000 VNĐ</a>
                        <a class="btn custom-btn custom-border-btn mt-3 me-3" href="<?php echo $BASE_PATH; ?>/layout/booktour.php?tour=luc-tinh">Đặt ngay!</a>
                        <button class="btn custom-btn mt-3" onclick="document.getElementById('tourModal1').classList.add('active')">Xem thêm</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal for Tour 1 -->
        <div class="tour-modal" id="tourModal1">
            <div class="tour-modal-content">
                <span class="close" onclick="document.getElementById('tourModal1').classList.remove('active')">×</span>
                <h5>Hình ảnh chi tiết & Phản hồi từ khách hàng</h5>
                <div class="image-row">
                    <img src="../layout/images/service/cheo.jpg" alt="Chèo xuồng tại tour Lục Tỉnh" class="overlay-img">
                    <img src="../layout/images/service/tour8.jpg" alt="Chợ nổi trong tour Lục Tỉnh" class="overlay-img">
                    <img src="../layout/images/service/tour9.jpg" alt="Vườn trái cây tại tour Lục Tỉnh" class="overlay-img">
                </div>
                <div class="feedbacks">
                    <p><strong>Nguyễn Thị Mai:</strong> “Tour rất đáng giá tiền! Mỗi địa điểm đều thú vị, hướng dẫn viên nhiệt tình.”</p>
                    <p><strong>Trần Quốc Bảo:</strong> “Ẩm thực ngon, tổ chức chuyên nghiệp. Gia đình tôi rất hài lòng!”</p>
                    <p><strong>Đặng Thanh Hương:</strong> “Lần đầu đi miền Tây mà ấn tượng cực kỳ. Sẽ quay lại!”</p>
                </div>
            </div>
        </div>

        <!-- Tour 2: Miền Tây Xanh 3N2Đ -->
        <section class="about-section section-padding" style="position: relative; padding: 0; background-color:#5D7B6F; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-5 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-right">
                        <em class="small-text">Explore Now!</em>
                        <h2 class="text-white mb-3">Thoát Ly Phố Thị – Miền Tây Xanh 3N2Đ</h2>
                        <p class="text-white">Hãy tạm gác lại những ồn ào, náo nhiệt của phố thị để hòa mình vào không gian thiên nhiên trong lành, xanh mướt của miền Tây sông nước. Chuyến đi 3 ngày 2 đêm này sẽ đưa bạn đến với những khu rừng tràm bạt ngàn, nơi bạn có thể trải nghiệm cảm giác chèo xuồng nhẹ nhàng lướt trên mặt nước phẳng lặng, tận hưởng bầu không khí yên bình và thanh tịnh.</p>
                        <p class="text-white">Đây là hành trình lý tưởng dành cho những ai yêu thiên nhiên, muốn tìm về chốn bình yên để “sạc lại năng lượng” và làm mới tâm hồn. Bạn sẽ được khám phá cảnh sắc thơ mộng, nghe tiếng chim hót, cảm nhận sự giao hòa giữa con người và thiên nhiên qua những làng quê giản dị, mộc mạc.</p>
                        <p class="text-white">Với mức giá trọn gói hấp dẫn, tour “Thoát Ly Phố Thị – Miền Tây Xanh 3N2Đ” hứa hẹn mang đến cho bạn kỳ nghỉ thư giãn tuyệt vời, giúp bạn tái tạo sức sống và thêm yêu cuộc sống.</p>
                        <a class="btn custom-btn custom-border-btn mt-3 me-3">1.990.000 VNĐ</a>
                        <a class="btn custom-btn custom-border-btn mt-3 me-3" href="<?php echo $BASE_PATH; ?>/layout/booktour.php?tour=mien-tay-xanh">Đặt ngay!</a>
                        <button class="btn custom-btn mt-3" onclick="document.getElementById('tourModal2').classList.add('active')">Xem thêm</button>
                    </div>
                    <div class="col-lg-6 col-12 p-0" data-aos="fade-right">
                        <div class="image-slider" style="max-height: 900px; max-width: 900px; overflow: hidden; margin-left: auto;">
                            <img src="../layout/images/service/TOUR2.jpg" alt="Tour Miền Tây Xanh 3N2Đ tại Cửu Long Resort" style="width: 100%; height: auto; display: block; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal for Tour 2 -->
        <div class="tour-modal" id="tourModal2">
            <div class="tour-modal-content">
                <span class="close" onclick="document.getElementById('tourModal2').classList.remove('active')">×</span>
                <h5>Hình ảnh chi tiết & Phản hồi từ khách hàng</h5>
                <div class="image-row">
                    <img src="../layout/images/service/tour7.jpg" alt="Rừng tràm trong tour Miền Tây Xanh" class="overlay-img">
                    <img src="../layout/images/service/tour10.jpg" alt="Làng quê tại tour Miền Tây Xanh" class="overlay-img">
                    <img src="../layout/images/service/tour11.jpg" alt="Chèo xuồng tại tour Miền Tây Xanh" class="overlay-img">
                </div>
                <div class="feedbacks">
                    <p><strong>Phạm Minh Tuấn:</strong> “Dịch vụ tuyệt vời, nhân viên thân thiện và chuyên nghiệp. Rất đáng để trải nghiệm!”</p>
                    <p><strong>Ngô Thị Hạnh:</strong> “Không gian yên tĩnh, gần gũi với thiên nhiên. Gia đình tôi rất thích nơi này.”</p>
                    <p><strong>Vũ Hoàng Nam:</strong> “Lịch trình tour hợp lý, nhiều hoạt động thú vị. Hướng dẫn viên hiểu biết và vui tính.”</p>
                    <p><strong>Lê Kim Anh:</strong> “Resort sạch sẽ, đầy đủ tiện nghi. Ẩm thực đa dạng và rất hợp khẩu vị.”</p>
                </div>
            </div>
        </div>

        <!-- Tour 3: Miền Tây Một Ngày -->
        <section class="about-section section-padding" style="position: relative; padding: 0; background-color:#41554d; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-6 col-12 p-0" data-aos="fade-left">
                        <div class="image-slider" style="max-height: 900px; max-width: 900px; overflow: hidden;">
                            <img src="../layout/images/service/TOUR.jpg" alt="Tour Miền Tây Một Ngày tại Cửu Long Resort" style="width: 100%; max-width: 购房100%; height: auto; display: block; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-left">
                        <em class="small-text">Explore Now!</em>
                        <h2 class="text-white mb-3">Miền Tây Một Ngày</h2>
                        <p class="text-white">Hành trình ngắn ngày đầy ý nghĩa giúp bạn khám phá trọn vẹn những giá trị văn hóa đặc sắc của miền Tây Nam Bộ, nơi lưu giữ nét đẹp giản dị, chân chất và đầy bản sắc. Trong tour một ngày này, bạn sẽ được dẫn dắt qua những vùng đất Tiền Giang – Bến Tre với cảnh quan miệt vườn xanh tươi mát, những dòng sông uốn lượn thơ mộng và không khí trong lành đặc trưng của miền sông nước.</p>
                        <p class="text-white">Trải nghiệm tham quan các vườn trái cây trĩu quả, thưởng thức những món đặc sản mang đậm hương vị truyền thống như cá lóc nướng trui, bánh xèo miền Tây, hủ tiếu và nhiều món ngon dân dã khác. Đồng thời, bạn còn có cơ hội tìm hiểu sâu hơn về đời sống thường nhật của người dân địa phương qua những hoạt động như chèo xuồng, thăm làng nghề thủ công truyền thống và khám phá những câu chuyện văn hóa đậm đà bản sắc miền Tây.</p>
                        <p class="text-white">Đây là lựa chọn hoàn hảo dành cho những ai muốn khám phá nhanh, gọn nhưng vẫn trọn vẹn tinh hoa văn hóa miền Tây Nam Bộ.</p>
                        <a class="btn custom-btn custom-border-btn mt-3 me-3">1.190.000 VNĐ</a>
                        <a class="btn custom-btn custom-border-btn mt-3 me-3" href="<?php echo $BASE_PATH; ?>/layout/booktour.php?tour=mien-tay-mot-ngay">Đặt ngay!</a>
                        <button class="btn custom-btn mt-3" onclick="document.getElementById('tourModal3').classList.add('active')">Xem thêm</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal for Tour 3 -->
        <div class="tour-modal" id="tourModal3">
            <div class="tour-modal-content">
                <span class="close" onclick="document.getElementById('tourModal3').classList.remove('active')">×</span>
                <h5>Hình ảnh chi tiết & Phản hồi từ khách hàng</h5>
                <div class="image-row">
                    <img src="../layout/images/service/tour11.jpg" alt="Vườn trái cây trong tour Miền Tây Một Ngày" class="overlay-img">
                    <img src="../layout/images/service/tour12.jpg" alt="Làng nghề truyền thống tại tour Miền Tây Một Ngày" class="overlay-img">
                    <img src="../layout/images/service/tour13.jpg" alt="Ẩm thực miền Tây trong tour Miền Tây Một Ngày" class="overlay-img">
                </div>
                <div class="feedbacks">
                    <p><strong>Đỗ Minh Phương:</strong> “Mỗi địa điểm đều để lại ấn tượng khó quên. Mọi thứ đều được chuẩn bị chu đáo.”</p>
                    <p><strong>Lâm Thị Hòa:</strong> “Chuyến đi đúng nghĩa thư giãn. Cảnh đẹp, đồ ăn ngon và lịch trình không quá dày đặc.”</p>
                    <p><strong>Nguyễn Văn Thắng:</strong> “Giá cả hợp lý, chất lượng dịch vụ vượt mong đợi. Đáng tiền từng đồng!”</p>
                    <p><strong>Hồ Thảo My:</strong> “Nhân viên hỗ trợ rất nhiệt tình từ khâu tư vấn đến lúc kết thúc chuyến đi.”</p>
                    <p><strong>Trần Khánh Duy:</strong> “Không khí miền Tây thật dễ chịu. Đây là một trong những chuyến đi đáng nhớ nhất của tôi.”</p>
                </div>
            </div>
        </div>

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
</body>
</html>