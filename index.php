<?php
session_start();
// Định nghĩa đường dẫn cơ sở
$BASE_PATH = '/CUULONGRESORT';

// Kết nối database
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=cuulong_resort;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Không thể kết nối với cơ sở dữ liệu. Vui lòng thử lại sau.");
}

// Lấy danh sách tour
$stmt = $pdo->query("SELECT id, name, description, price, duration, slug FROM tours");
$tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Thiết lập các biến cho SEO
$page_title = "Cửu Long Resort - Trang Chủ";
$page_description = "Khám phá Cửu Long Resort, điểm đến nghỉ dưỡng lý tưởng tại Vĩnh Long với không gian thiên nhiên, phòng nghỉ sang trọng và dịch vụ chuyên nghiệp.";
$page_keywords = "Cửu Long Resort, nghỉ dưỡng Vĩnh Long, khách sạn miền Tây, đặt phòng resort, du lịch miền Tây";
// canonical_url chỉ là domain gốc cho trang chủ
$canonical_url = "https://cuulongresort.com/";
?>

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="author" content="Ton Duc Thang University">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/vegas.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo $BASE_PATH; ?>/">
                <img src="<?php echo $BASE_PATH; ?>/layout/images/LOGO.png" style="width: 50px" class="navbar-brand-image img-fluid" alt="Logo Cửu Long Resort">C|L RESORT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-lg-auto">
                    <li class="nav-item"><a class="nav-link active" href="<?php echo $BASE_PATH; ?>/">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/gioi-thieu">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/dich-vu">Dịch vụ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/tour-du-lich">Tour du lịch</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/dat-phong">Đặt phòng</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/lien-he">Liên hệ</a></li>
                </ul>
                <div class="ms-lg-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="btn custom-btn custom-border-btn" href="<?php echo $BASE_PATH; ?>/ho-so">Hồ sơ<i class="bi-person ms-2"></i></a>
                    <?php else: ?>
                        <a class="btn custom-btn custom-border-btn" href="<?php echo $BASE_PATH; ?>/dang-nhap">Đăng nhập<i class="bi-arrow-up-right ms-2"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero-section d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row align-items-center" data-aos="fade-top">
                    <div class="col-lg-6 col-12 mx-auto">
                        <em class="small-text">Chào mừng đến với chúng tôi!</em>
                        <h1>Cửu Long Resort</h1>
                        <p class="text-white mb-4 pb-lg-2">Tự hào mang đến cho bạn trải nghiệm nghỉ dưỡng tốt nhất.</p>
                        <a class="btn custom-btn smoothscroll me-2 mb-2" href="<?php echo $BASE_PATH; ?>/lien-he">Liên hệ</a>
                        <a class="btn custom-btn smoothscroll me-2 mb-2" href="<?php echo $BASE_PATH; ?>/dat-phong"><strong>Loại phòng</strong></a>
                    </div>
                </div>
            </div>
            <div class="hero-slides"></div>
        </section>

        <!-- Search Form -->
        <div class="search-form">
            <div class="search-field">
                <label for="location"><i class="fas fa-map-marker-alt"></i> Địa điểm</label>
                <select id="location" name="location">
                    <option value="">Chọn địa điểm...</option>
                    <option value="VinhLong">Vĩnh Long</option>
                    <option value="TienGiang">Tiền Giang</option>
                    <option value="CanTho">Cần Thơ</option>
                </select>
            </div>
            <div class="search-field">
                <label for="check-in"><i class="fas fa-calendar-alt"></i> Nhận phòng</label>
                <input type="date" class="form-control" id="check-in" name="check-in" />
            </div>
            <div class="search-field">
                <label for="check-out"><i class="fas fa-calendar-alt"></i> Trả phòng</label>
                <input type="date" class="form-control" id="check-out" name="check-out" />
            </div>
            <div class="search-field">
                <label for="room"><i class="fas fa-users"></i> Phòng</label>
                <select id="room" name="room">
                    <option value="">Chọn số lượng khách...</option>
                    <option value="1-2">1-2 Khách</option>
                    <option value="3-5">3-5 Khách</option>
                    <option value="6-10">6-10 Khách</option>
                </select>
            </div>
            <button class="search-btn" onclick="window.location.href='<?php echo $BASE_PATH; ?>/dat-phong'"><i class="fas fa-search"></i> TÌM KIẾM</button>
        </div>

        <!-- Giới Thiệu -->
        <section class="about-section section-padding" style="background-color:#5D7B6F;">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-8 col-sm-10 ms-auto" data-aos="fade-left">
                        <div class="ratio ratio-1x1">
                            <video autoplay loop muted class="custom-video" poster="">
                                <source src="<?php echo $BASE_PATH; ?>/layout/images/Home2.mp4" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 mt-4 mt-lg-0 mx-auto" style="text-align: justify;" data-aos="fade-left">
                        <em class="small-text">Bắt đầu hành trình!</em>
                        <h2 class="text-white mb-3">Cửu Long Resort</h2>
                        <p class="text-white">Vì sao bạn nên chọn Resort của chúng tôi?</p>
                        <p class="text-white">Việc lựa chọn điểm đến nghỉ dưỡng lý tưởng chính là bước đầu tiên để khởi tạo một hành trình hoàn hảo. Tại resort của chúng tôi, bạn sẽ được đắm mình trong không gian thanh bình giữa thiên nhiên trù phú, nơi mỗi chi tiết đều được chăm chút tỉ mỉ nhằm mang lại sự thoải mái tuyệt đối.</p>
                        <p class="text-white">Từ hệ thống phòng nghỉ đẳng cấp, dịch vụ chuyên nghiệp, đến ẩm thực tinh tế và các tiện ích giải trí đa dạng. Tất cả đều được thiết kế để đáp ứng nhu cầu nghỉ dưỡng của những vị khách khó tính nhất. Chúng tôi không chỉ mang đến một kỳ nghỉ, mà còn là một trải nghiệm đáng nhớ, mang đậm dấu ấn riêng.</p>
                        <a class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4" href="<?php echo $BASE_PATH; ?>/dat-phong">Đặt phòng ngay!</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section section-padding" id="section_2" style="background-color:#5D7B6F;">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-12 mt-4 mt-lg-0 mx-auto" style="text-align: justify;" data-aos="fade-right">
                        <em class="small-text">Bắt đầu hành trình!</em>
                        <h2 class="text-white mb-3">Cửu Long Resort</h2>
                        <p class="text-white">Khám phá không gian nghỉ dưỡng lý tưởng với đa dạng hạng phòng. Tại resort của chúng tôi, bạn sẽ được lựa chọn đa dạng các hạng phòng từ Deluxe hướng vườn yên bình, phòng Suite hướng biển lãng mạn, đến biệt thự hồ bơi riêng sang trọng và biệt lập của loại phòng King và Family.</p>
                        <p class="text-white">Từ hệ thống phòng nghỉ đẳng cấp, dịch vụ chuyên nghiệp, đến ẩm thực tinh tế và các tiện ích giải trí đa dạng. Tất cả đều được thiết kế để đáp ứng nhu cầu nghỉ dưỡng của những vị khách khó tính nhất. Chúng tôi không chỉ mang đến một kỳ nghỉ, mà còn là một trải nghiệm đáng nhớ, mang đậm dấu ấn riêng.</p>
                        <p class="text-white">Dù bạn đi nghỉ cùng gia đình, người yêu hay nhóm bạn, chúng tôi luôn có lựa chọn phù hợp để kỳ nghỉ của bạn trở nên trọn vẹn và đáng nhớ.</p>
                        <a class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4" href="<?php echo $BASE_PATH; ?>/dat-phong">Đặt phòng ngay!</a>
                    </div>
                    <div class="col-lg-5 col-md-8 col-sm-10 me-auto" data-aos="fade-right">
                        <div class="ratio ratio-1x1">
                            <video autoplay loop muted class="custom-video" poster="">
                                <source src="<?php echo $BASE_PATH; ?>/layout/images/room/Room.mp4" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Facilities -->
        <section class="barista-section section-padding section-bg" id="barista-team" style="background-color:#41554d">
            <div class="container">
                <div class="row justify-content-center" data-aos="fade-top">
                    <div class="col-lg-12 col-12 text-center mb-4 pb-lg-2">
                        <em class="text-white">Cửu Long Resort</em>
                        <h2 class="text-white">Tiện ích</h2>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                        <div class="team-block-wrap">
                            <div class="team-block-image-wrap">
                                <video class="team-block-image img-fluid" autoplay muted loop playsinline>
                                    <source src="<?php echo $BASE_PATH; ?>/layout/images/service/gym1.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                        <div class="team-block-wrap">
                            <div class="team-block-image-wrap">
                                <video class="team-block-image img-fluid" autoplay muted loop playsinline>
                                    <source src="<?php echo $BASE_PATH; ?>/layout/images/service/res1.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                        <div class="team-block-wrap">
                            <div class="team-block-image-wrap">
                                <video class="team-block-image img-fluid" autoplay muted loop playsinline>
                                    <source src="<?php echo $BASE_PATH; ?>/layout/images/service/res5.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                        <div class="team-block-wrap">
                            <div class="team-block-image-wrap">
                                <video class="team-block-image img-fluid" autoplay muted loop playsinline>
                                    <source src="<?php echo $BASE_PATH; ?>/layout/images/Aboutus.mp4" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Menu Phòng -->
        <section class="about-section section-padding" id="section_2" style="background-color:#5D7B6F;">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-8 col-sm-10 ms-auto" data-aos="fade-left">
                        <div class="ratio ratio-4x3">
                            <video autoplay loop muted class="custom-video" poster="">
                                <source src="<?php echo $BASE_PATH; ?>/layout/images/service/re3.mp4" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 mt-4 mt-lg-0 mx-auto" style="text-align: justify;" data-aos="fade-left">
                        <em class="small-text">Bắt đầu hành trình!</em>
                        <h2 class="text-white mb-3">Cửu Long Resort</h2>
                        <p class="text-white">Trải nghiệm hệ thống tiện ích tại Resort.</p>
                        <p class="text-white">Thưởng thức tinh hoa ẩm thực tại nhà hàng sang trọng, thư giãn tại hồ bơi ngoài trời trong lành, làm mới cơ thể với dịch vụ spa chuyên nghiệp hay giữ dáng cùng phòng gym hiện đại đầy đủ trang thiết bị.</p>
                        <p class="text-white">Mỗi dịch vụ đều được chăm chút kỹ lưỡng, mang lại sự hài lòng tối đa cho mọi nhu cầu nghỉ ngơi, chăm sóc sức khỏe và giải trí.</p>
                        <a class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4" href="<?php echo $BASE_PATH; ?>/dat-phong">Đặt phòng ngay!</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="menu-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-12 mb-4 mb-lg-0" data-aos="fade-top">
                        <div class="menu-block-wrap">
                            <div class="text-center mb-4 pb-lg-2">
                                <em class="text-white">Cửu Long Resort</em>
                                <h4 class="text-white">Phòng nghỉ</h4>
                            </div>
                            <div class="menu-block">
                                <div class="d-flex">
                                    <h6>Phòng Deluxe</h6>
                                    <div class="rating" style="margin-left: 8px;">
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="far fa-star" style="color: rgb(223, 255, 15);"></i>
                                    </div>
                                    <span class="underline"></span>
                                    <strong class="ms-auto">500.000đ/Đêm</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>1 giường nhỏ, phù hợp với 1 - 2 khách.</small>
                                </div>
                            </div>
                            <div class="menu-block my-4">
                                <div class="d-flex">
                                    <h6>Phòng Suite</h6>
                                    <div class="rating" style="margin-left: 8px;">
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="far fa-star" style="color: rgb(223, 255, 15);"></i>
                                    </div>
                                    <span class="underline"></span>
                                    <strong class="text-white ms-auto"><del>850.000</del></strong>
                                    <strong class="ms-2">700.000đ/Đêm</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>1 giường lớn, phù hợp với 1 - 4 khách.</small>
                                </div>
                            </div>
                            <div class="menu-block">
                                <div class="d-flex">
                                    <h6>Phòng Family</h6>
                                    <div class="rating" style="margin-left: 8px;">
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                    </div>
                                    <span class="underline"></span>
                                    <strong class="text-white ms-auto"><del>1.100.000</del></strong>
                                    <strong class="ms-2">900.000đ/Đêm</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>2 giường lớn, phù hợp 3 - 10 khách.</small>
                                </div>
                            </div>
                            <div class="menu-block my-4">
                                <div class="d-flex">
                                    <h6>Phòng King</h6>
                                    <div class="rating" style="margin-left: 8px;">
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                        <i class="fas fa-star" style="color: rgb(223, 255, 15);"></i>
                                    </div>
                                    <span class="underline"></span>
                                    <strong class="text-white ms-auto"><del>1.800.000</del></strong>
                                    <strong class="ms-2">1.500.000đ/Đêm</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>1 giường siêu lớn, phù hợp với 1 - 10 khách.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12 mb-4 mb-lg-0" data-aos="fade-top">
                        <div class="menu-block-wrap">
                            <div class="text-center mb-4 pb-lg-2">
                                <em class="text-white">Cửu Long Resort</em>
                                <h4 class="text-white">Phòng bán chạy 2024</h4>
                            </div>
                            <div class="menu-block">
                                <div class="d-flex">
                                    <h6>Tháng 1 - 3: Phòng Deluxe</h6>
                                    <span class="underline"></span>
                                    <strong class="ms-auto">12.056 phòng</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>Đánh giá mức độ hài lòng từ khách hàng: 4.7/5</small>
                                </div>
                            </div>
                            <div class="menu-block my-4">
                                <div class="d-flex">
                                    <h6>Tháng 4 - 6: Phòng King</h6>
                                    <span class="underline"></span>
                                    <strong class="ms-auto">20.244 phòng</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>Đánh giá mức độ hài lòng từ khách hàng: 4.8/5</small>
                                </div>
                            </div>
                            <div class="menu-block">
                                <div class="d-flex">
                                    <h6>Tháng 7 - 9: Phòng Suite</h6>
                                    <span class="underline"></span>
                                    <strong class="ms-auto">10.244 phòng</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>Đánh giá mức độ hài lòng từ khách hàng: 4.3/5</small>
                                </div>
                            </div>
                            <div class="menu-block my-4">
                                <div class="d-flex">
                                    <h6>Tháng 10 - 12: Phòng Suite</h6>
                                    <span class="underline"></span>
                                    <strong class="ms-auto">4.244 phòng</strong>
                                </div>
                                <div class="border-top mt-2 pt-2">
                                    <small>Đánh giá mức độ hài lòng từ khách hàng: 4.5/5</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section section-padding" style="position: relative; padding: 0; background-color:#5D7B6F; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-12 p-0" data-aos="fade-left">
                        <div class="image-slider">
                            <img src="<?php echo $BASE_PATH; ?>/layout/images/service/res6.jpg" class="slider-img" alt="Nhà hàng Cửu Long Resort" loading="lazy">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-left">
                        <em class="small-text">Khám phá ngay!</em>
                        <h2 class="text-white mb-3">Nhà hàng ẩm thực</h2>
                        <p class="text-white">Nhà hàng tại Cửu Long Resort với thiết kế sang trọng, hài hòa giữa nét truyền thống và hiện đại, mang đến trải nghiệm ẩm thực trọn vẹn, phù hợp cho cả bữa ăn gia đình, tiệc họp mặt, hay các sự kiện đặc biệt.</p>
                        <p class="text-white">Thực đơn phong phú, kết hợp tinh tế giữa ẩm thực truyền thống Nam Bộ và phương Tây hiện đại. Các món đặc sản miền Tây như cá lóc nướng trui, lẩu mắm, gỏi bông điên điển hay bánh xèo được chế biến từ nguyên liệu tươi ngon. Các món Âu như bò lúc lắc, mì Ý, beefsteak cũng được phục vụ với tiêu chuẩn cao.</p>
                        <p class="text-white">Không gian thoáng đãng với tầm nhìn hướng sông hoặc vườn xanh, lý tưởng cho tiệc ngoài trời, tiệc cưới, sinh nhật hoặc sự kiện doanh nghiệp.</p>
                        <a class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4" href="<?php echo $BASE_PATH; ?>/dich-vu">Đặt ngay!</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section section-padding" style="position: relative; padding: 0; background-color:#5D7B6F; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-4 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-right">
                        <em class="small-text">Khám phá ngay!</em>
                        <h2 class="text-white mb-3">Hồ bơi vô cực</h2>
                        <p class="text-white">Khám phá hồ bơi vô cực với thiết kế hiện đại, mang đến cảm giác hòa mình vào thiên nhiên. Nằm trên tầng cao, hồ bơi là nơi lý tưởng để thư giãn và thưởng thức tầm nhìn tuyệt đẹp.</p>
                        <p class="text-white">Thư giãn trong làn nước mát lạnh, tận hưởng không gian yên bình và cảnh quan xung quanh.</p>
                        <a class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4" href="<?php echo $BASE_PATH; ?>/dich-vu">Tìm hiểu thêm!</a>
                    </div>
                    <div class="col-lg-7 col-12 p-0" data-aos="fade-right">
                        <div class="image-slider" style="max-height: 1500px; max-width: 1000px; overflow: hidden; margin-left: auto;">
                            <img src="<?php echo $BASE_PATH; ?>/layout/images/service/pool.jpg" alt="Hồ bơi vô cực Cửu Long Resort" style="width: 100%; height: auto; display: block; object-fit: cover;" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section section-padding" style="position: relative; padding: 0; background-color:#5D7B6F; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-6 col-12 p-0" data-aos="fade-left">
                        <div class="image-slider">
                            <img src="<?php echo $BASE_PATH; ?>/layout/images/service/bar.jpg" class="slider-img" alt="Quầy bar Cửu Long Resort" loading="lazy">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-left">
                        <em class="small-text">Khám phá ngay!</em>
                        <h2 class="text-white mb-3">Bar, Gym & Spa</h2>
                        <p class="text-white">Hệ thống tiện ích cao cấp tại Cửu Long Resort bao gồm Spa thư giãn, phòng Gym hiện đại và Bar sang trọng, miễn phí cho khách lưu trú.</p>
                        <p class="text-white">Quầy Bar với không gian mở, phục vụ cocktail, rượu vang và nước trái cây tươi, lý tưởng để ngắm hoàng hôn. Khu Spa với liệu trình massage, xông hơi mang lại sự thư thái. Phòng Gym được trang bị hiện đại, phù hợp mọi đối tượng.</p>
                        <a class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4" href="<?php echo $BASE_PATH; ?>/dich-vu">Tìm hiểu thêm!</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tour du lịch -->
        <section class="about-section section-padding" style="position: relative; padding: 0; background-color:#5D7B6F; text-align: justify;">
            <div class="container-fluid p-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-4 col-md-6 ms-auto me-auto mt-4 mt-lg-0" data-aos="fade-right">
                        <em class="small-text">Khám phá ngay!</em>
                        <h2 class="text-white mb-3">Tour du lịch</h2>
                        <p class="text-white">Cửu Long Resort mang đến cho du khách chuỗi hành trình khám phá miền Tây đặc sắc, kết hợp hoàn hảo giữa nghỉ dưỡng.</p>
                        <?php foreach ($tours as $tour): ?>
                        <p class="text-white">
                            <strong><?php echo htmlspecialchars($tour['name']); ?> – <?php echo htmlspecialchars($tour['duration']); ?>:</strong>
                            <?php echo htmlspecialchars(substr($tour['description'], 0, 100)); ?>...
                            <a href="<?php echo $BASE_PATH; ?>/dat-tour/<?php echo htmlspecialchars($tour['slug']); ?>" class="text-white">Chi tiết</a>
                        </p>
                        <?php endforeach; ?>
                        <a class="smoothscroll btn custom-btn custom-border-btn mt-3 mb-4" href="<?php echo $BASE_PATH; ?>/tour-du-lich">Xem tất cả tour!</a>
                    </div>
                    <div class="col-lg-7 col-12 p-0" data-aos="fade-right">
                        <div class="image-slider" style="max-height: 1000px; max-width: 1000px; overflow: hidden; margin-left: auto;">
                            <img src="<?php echo $BASE_PATH; ?>/layout/images/service/tour7.jpg" alt="Tour du lịch miền Tây Cửu Long Resort" style="width: 100%; height: auto; display: block; object-fit: cover;" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="reviews-section section-padding section-bg">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-12 text-center mb-4 pb-lg-2" data-aos="fade-top">
                        <em class="text-white">Cửu Long Resort</em>
                        <h2 class="text-white">Đánh giá</h2>
                    </div>
                    <div class="timeline">
                        <div class="timeline-container timeline-container-left" data-aos="fade-right">
                            <div class="timeline-content">
                                <div class="reviews-block">
                                    <div class="reviews-block-info">
                                        <p>Đã có dịp trải nghiệm Cửu Long Resort. Nhân viên nhiệt tình, phòng sạch và thơm, sẽ ghé lại lần sau!</p>
                                        <div class="d-flex border-top pt-3 mt-4">
                                            <em class="text-black"><small class="ms-2">Từ: Lê Thị Mỹ Phụng</small></em>
                                            <div class="reviews-group ms-auto">
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-container timeline-container-right" data-aos="fade-left">
                            <div class="timeline-content">
                                <div class="reviews-block">
                                    <div class="reviews-block-info">
                                        <p>Không gian yên tĩnh, nhiều tiện ích thư giãn, rất thích hợp để nghỉ dưỡng cùng gia đình.</p>
                                        <div class="d-flex border-top pt-3 mt-4">
                                            <em class="text-black"><small class="ms-2">Từ: Đặng Kim Anh</small></em>
                                            <div class="reviews-group ms-auto">
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-container timeline-container-left" data-aos="fade-right">
                            <div class="timeline-content">
                                <div class="reviews-block">
                                    <div class="reviews-block-info">
                                        <p>Dịch vụ chuyên nghiệp, đồ ăn ngon, cảm thấy hài lòng từ lúc check-in đến lúc rời đi.</p>
                                        <div class="d-flex border-top pt-3 mt-4">
                                            <em class="text-black"><small class="ms-2">Từ: Nguyễn Lại Vũ Phong</small></em>
                                            <div class="reviews-group ms-auto">
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star-fill"></i>
                                                <i class="bi-star"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                            <li class="d-flex">Cuối tuần
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

    <script src="<?php echo $BASE_PATH; ?>/layout/js/jquery.min.js"></script>
    <script src="<?php echo $BASE_PATH; ?>/layout/js/bootstrap.min.js"></script>
    <script src="<?php echo $BASE_PATH; ?>/layout/js/jquery.sticky.js"></script>
    <script src="<?php echo $BASE_PATH; ?>/layout/js/click-scroll.js"></script>
    <script src="<?php echo $BASE_PATH; ?>/layout/js/vegas.min.js"></script>
    <script src="<?php echo $BASE_PATH; ?>/layout/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>
</body>
</html>