<?php
session_start();

// Định nghĩa đường dẫn cơ sở
$BASE_PATH = '/CUULONGRESORT';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: {$BASE_PATH}/layout/login.php?redirect=deluxe.php");
    exit;
}

// Kết nối cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=localhost;dbname=Cuulong_Resort", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT id, name, capacity, price, facilities FROM rooms WHERE name = ?");
    $stmt->execute(['Deluxe']);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$room) {
        die("Phòng Deluxe không tồn tại.");
    }

    // Lấy danh sách ảnh phòng
    $stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE room_id = ?");
    $stmt->execute([$room['id']]);
    $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (empty($images)) {
        $images = ['deluxe1.jpg', 'deluxe2.jpg', 'deluxe3.jpg', 'deluxe4.jpg', 'deluxe5.jpg', 'deluxe6.jpg'];
    }
} catch (PDOException $e) {
    error_log("Database error in deluxe.php: " . $e->getMessage());
    $room = ['id' => 2, 'name' => 'Deluxe', 'capacity' => 4, 'price' => 500000, 'facilities' => 'WiFi, TV, Ban công'];
    $images = ['deluxe1.jpg', 'deluxe2.jpg', 'deluxe3.jpg', 'deluxe4.jpg', 'deluxe5.jpg', 'deluxe6.jpg'];
}

// Thông báo đánh giá thành công
$reviewSuccess = $_SESSION['review_success'] ?? null;
if ($reviewSuccess) {
    unset($_SESSION['review_success']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Phòng Deluxe tại Cửu Long Resort - Không gian nghỉ ngơi lý tưởng với tiện nghi hiện đại">
    <meta name="keywords" content="Phòng Deluxe, Cửu Long Resort, nghỉ dưỡng Vĩnh Long, phòng nghỉ miền Tây">
    <meta name="author" content="TonDucThangUniversity">
    <title>Phòng Deluxe - Cửu Long Resort</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/vegas.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/style.css" rel="stylesheet">
    <link rel="canonical" href="https://cuulongresort.com/deluxe">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HotelRoom",
        "name": "Deluxe Room",
        "description": "Phòng Deluxe tại Cửu Long Resort mang đến không gian nghỉ ngơi lý tưởng với thiết kế hiện đại, tiện nghi cao cấp, phù hợp cho 2-4 khách.",
        "image": "<?php echo $BASE_PATH; ?>/layout/images/Room/deluxe3.jpg",
        "offers": {
            "@type": "Offer",
            "price": "<?php echo number_format($room['price'], 0, '', ''); ?>",
            "priceCurrency": "VND",
            "availability": "https://schema.org/InStock"
        },
        "containedInPlace": {
            "@type": "Hotel",
            "name": "Cửu Long Resort",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "10/10 Nguyễn Văn Thiệt, Phường 3",
                "addressLocality": "Vĩnh Long",
                "addressCountry": "VN"
            }
        }
    }
    </script>
</head>
<body style="background-color: #41554d;">
    <main>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="<?php echo $BASE_PATH; ?>/index.php">
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
                        <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/room.php">Đặt phòng</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/contact.php">Liên hệ</a></li>
                    </ul>
                    <div class="ms-lg-3">
                        <a class="btn custom-btn custom-border-btn" href="<?php echo $BASE_PATH; ?>./layout/profile.php">Hồ sơ<i class="bi-person ms-2"></i></a>
                    </div>
                </div>
            </div>
        </nav>

        <section class="about-section section-padding" style="margin-top: 100px; padding: 80px 0; background-color: #41554d;" data-aos="fade-left">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-12 col-12">
                        <div style="background-color: #ffffff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 600px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <img src="<?php echo $BASE_PATH; ?>./layout/images/Room/deluxe3.jpg" class="img-fluid rounded" alt="Deluxe Room 3">
                                        </div>
                                        <div class="col-6">
                                            <img src="<?php echo $BASE_PATH; ?>./layout/images/Room/deluxe2.jpg" class="img-fluid rounded" alt="Deluxe Room 2">
                                        </div>
                                        <div class="col-6 position-relative" style="cursor: pointer;" id="showGalleryBtn">
                                            <img src="<?php echo $BASE_PATH; ?>./layout/images/Room/deluxe1.jpg" class="img-fluid rounded img-zoom" alt="Deluxe Room 1" style="filter: brightness(50%);">
                                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 14px; pointer-events: none;">Xem thêm ảnh+</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h2 class="mb-3"><?php echo htmlspecialchars($room['name']); ?> Room</h2>
                                    <p style="line-height: 1.6; color: black; font-size: 19px;">Tận hưởng không gian nghỉ ngơi lý tưởng tại Phòng Deluxe – nơi sự kết hợp tinh tế giữa thiết kế hiện đại và cảm giác ấm cúng tạo nên một chốn dừng chân hoàn hảo. Phòng được trang bị đầy đủ tiện nghi cao cấp, mang đến sự thoải mái tối đa cho các cặp đôi hoặc gia đình nhỏ.</p>
                                    <ul class="room-feature-list">
                                        <li><i class="fa-solid fa-user-group icon"></i> <?php echo htmlspecialchars($room['capacity']); ?> khách</li>
                                        <li><i class="fa-solid fa-wifi icon"></i> Wi-Fi tốc độ cao miễn phí</li>
                                        <li><i class="fa-solid fa-tv icon"></i> TV màn hình phẳng</li>
                                        <li><i class="fa-solid fa-bed icon"></i> 1 giường lớn hoặc 2 giường đơn</li>
                                        <li><i class="fa-solid fa-shower icon"></i> Phòng tắm hiện đại</li>
                                    </ul>
                                    <div style="text-align: right; margin-top: 50px;">
                                        <a class="custom-button"><?php echo number_format($room['price'], 0, ',', '.') . ' VNĐ / Đêm'; ?></a>
                                        <a class="custom-button" href="<?php echo $BASE_PATH; ?>./layout/booking.php?room_id=<?php echo $room['id']; ?>">Đặt phòng ngay!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ảnh toàn màn hình -->
                <div id="galleryLightbox" style="display:none; position: fixed; top:0; left:0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.8); z-index: 1050; overflow-y: auto;">
                    <div class="container py-5">
                        <div class="row g-3 justify-content-center">
                            <?php foreach ($images as $img): ?>
                                <div class="col-12 col-md-6 col-lg-4">
                                    <img src="<?php echo $BASE_PATH; ?>./layout/images/Room/<?php echo htmlspecialchars($img); ?>" alt="Deluxe Room" class="img-fluid rounded img-zoom">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div style="text-align: right; margin-top: 20px;">
                            <a href="<?php echo $BASE_PATH; ?>./layout/deluxe.php" class="custom-button">Thoát ra</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Đánh giá -->
            <section class="about-section section-padding" style="background-color: #41554d" data-aos="fade-left">
                <div class="container">
                    <div class="row g-4">
                        <div class="col-lg-12 px-3">
                            <div style="background-color: #fff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 600px;">
                                <h3 style="margin-bottom: 25px; color: #302421;">Đánh giá của khách hàng</h3>
                                <?php if ($reviewSuccess): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php echo htmlspecialchars($reviewSuccess); ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                                <form id="reviewForm" class="mb-4" action="<?php echo $BASE_PATH; ?>/layout/submit_review.php" method="post">
                                    <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                    <div class="mb-3">
                                        <label for="reviewName" class="form-label">Họ tên</label>
                                        <input type="text" class="form-control" id="reviewName" name="reviewName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="reviewText" class="form-label">Nội dung đánh giá</label>
                                        <textarea class="form-control" id="reviewText" name="reviewText" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="custom-button">Gửi đánh giá</button>
                                </form>
                                <div id="reviewList">
                                    <?php
                                    $stmt = $pdo->prepare("SELECT reviewer_name, review_text, created_at FROM reviews WHERE room_id = ? ORDER BY created_at DESC");
                                    $stmt->execute([$room['id']]);
                                    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($reviews as $review): ?>
                                        <div class="review-item mb-3">
                                            <strong><?php echo htmlspecialchars($review['reviewer_name']); ?></strong>
                                            <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                                            <small><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="site-footer">
                <div class="container">
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
                            <p class="copyright-text mb-0">© 2025 Cửu Long Resort.</p>
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
        <script src="<?php echo $BASE_PATH; ?>/layout/js/room.js"></script>
        <script src="<?php echo $BASE_PATH; ?>/layout/js/feedback.js"></script>
        <script>
            AOS.init({ duration: 1000, once: true });
            document.getElementById('showGalleryBtn').addEventListener('click', function() {
                document.getElementById('galleryLightbox').style.display = 'block';
            });
        </script>
    </body>
</html>