<?php
// Start session if not already started
session_start();

// Định nghĩa đường dẫn cơ sở
$BASE_PATH = '/CUULONGRESORT';

// Thiết lập các biến cho SEO
$page_title = "Cửu Long Resort - Đặt Phòng";
$page_description = "Khám phá và đặt phòng tại Cửu Long Resort, điểm đến nghỉ dưỡng lý tưởng tại Vĩnh Long với không gian thiên nhiên, phòng nghỉ sang trọng và dịch vụ chuyên nghiệp.";
$page_keywords = "Cửu Long Resort, nghỉ dưỡng Vĩnh Long, khách sạn miền Tây, đặt phòng resort, du lịch miền Tây";
$canonical_url = "https://cuulongresort.com/room";

// Kết nối cơ sở dữ liệu và lấy dữ liệu combo
try {
    $pdo = new PDO("mysql:host=localhost;dbname=Cuulong_Resort", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Lấy thông tin các combo
    $stmt = $pdo->query("SELECT c.id, c.name, c.discount_percent, r.name as room_name, 
                        GROUP_CONCAT(s.name SEPARATOR ', ') as services 
                        FROM combos c 
                        JOIN rooms r ON c.room_id = r.id 
                        LEFT JOIN combo_services cs ON c.id = cs.combo_id 
                        LEFT JOIN services s ON cs.service_id = s.service_id 
                        GROUP BY c.id");
    $combos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Nếu không có dữ liệu combo, sử dụng dữ liệu mẫu
    if (empty($combos)) {
        $combos = [
            ['id' => 1, 'name' => 'Deluxe + Bữa Sáng', 'discount_percent' => 10, 'room_name' => 'Deluxe', 'services' => 'Bữa sáng'],
            ['id' => 2, 'name' => 'Suite + Spa', 'discount_percent' => 15, 'room_name' => 'Suite', 'services' => 'Spa'],
            ['id' => 3, 'name' => 'Family + Đưa Đón Sân Bay', 'discount_percent' => 20, 'room_name' => 'Family', 'services' => 'Đưa đón sân bay']
        ];
    }
    
    // Chuẩn bị mô tả cho combo dựa trên dữ liệu
    foreach ($combos as &$combo) {
        $combo['description'] = "Phòng {$combo['room_name']} kèm dịch vụ: {$combo['services']}.";
    }
    
} catch (PDOException $e) {
    error_log("Database error in room.php: " . $e->getMessage());
    // Dữ liệu mẫu nếu có lỗi kết nối CSDL
    $combos = [
        ['id' => 1, 'name' => 'Deluxe + Bữa Sáng', 'discount_percent' => 10, 'description' => 'Phòng Deluxe kèm bữa sáng buffet miễn phí.'],
        ['id' => 2, 'name' => 'Suite + Spa', 'discount_percent' => 15, 'description' => 'Phòng Suite với gói spa thư giãn 1 giờ.'],
        ['id' => 3, 'name' => 'Family + Đưa Đón Sân Bay', 'discount_percent' => 20, 'description' => 'Phòng Family với dịch vụ đưa đón sân bay tiện lợi.']
    ];
}

// Thông tin các phòng
$rooms = [
    1 => [
        'name' => 'Standard',
        'image' => 'standard.jpg',
        'capacity' => '1-2',
        'beds' => '1 giường đôi',
        'rating' => 3,
        'price' => 400000,
        'description' => 'Phòng Standard mang đến sự tiện nghi cơ bản với thiết kế đơn giản nhưng ấm cúng, phù hợp cho các chuyến công tác hoặc du lịch tiết kiệm. Được trang bị WiFi, TV, và minibar, đây là lựa chọn lý tưởng cho khách cá nhân hoặc cặp đôi.'
    ],
    2 => [
        'name' => 'Deluxe',
        'image' => 'deluxe3.jpg',
        'capacity' => '2-4',
        'beds' => '1 giường lớn hoặc 2 giường đơn',
        'rating' => 4,
        'price' => 500000,
        'discount' => 10,
        'description' => 'Phòng Deluxe kết hợp thiết kế hiện đại và tiện nghi cao cấp, với ban công riêng nhìn ra cảnh sông nước miền Tây. Phù hợp cho cặp đôi hoặc gia đình nhỏ, phòng mang đến sự thoải mái và thư giãn tối đa.'
    ],
    3 => [
        'name' => 'Suite',
        'image' => 'suite3.jpg',
        'capacity' => '2-4',
        'beds' => '1 giường lớn',
        'rating' => 5,
        'price' => 700000,
        'discount' => 15,
        'description' => 'Phòng Suite sang trọng với bồn tắm riêng và không gian rộng rãi, lý tưởng cho những ai tìm kiếm sự thư giãn đẳng cấp. Ban công hướng sông và tiện nghi cao cấp mang đến trải nghiệm khó quên.'
    ],
    4 => [
        'name' => 'Family',
        'image' => 'f3.jpg',
        'capacity' => '4-8',
        'beds' => '2 giường lớn',
        'rating' => 5,
        'price' => 900000,
        'label' => 'HOT',
        'description' => 'Phòng Family rộng rãi với bếp nhỏ, phù hợp cho gia đình hoặc nhóm bạn. Không gian ấm cúng kết hợp tiện nghi hiện đại mang đến kỳ nghỉ thoải mái và vui vẻ.'
    ],
    5 => [
        'name' => 'Villa',
        'image' => 'villa.jpg',
        'capacity' => '4-6',
        'beds' => '2 giường lớn',
        'rating' => 5,
        'price' => 1500000,
        'label' => 'HOT',
        'description' => 'Villa riêng tư với hồ bơi riêng và không gian xanh mát, lý tưởng cho gia đình hoặc nhóm bạn tìm kiếm sự thư giãn đẳng cấp. Tiện nghi hiện đại đảm bảo kỳ nghỉ đáng nhớ.'
    ],
    6 => [
        'name' => 'Presidential Suite',
        'image' => 'presidential.jpg',
        'capacity' => '6-10',
        'beds' => '3 giường lớn',
        'rating' => 5,
        'price' => 2000000,
        'label' => 'VIP',
        'description' => 'Phòng Presidential Suite là đỉnh cao của sự sang trọng, với phòng khách riêng, bếp đầy đủ tiện nghi, và không gian rộng lớn. Phù hợp cho các gia đình lớn hoặc khách VIP.'
    ]
];

// Hàm hiển thị đánh giá sao
function renderStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= '<i class="' . ($i <= $rating ? 'fas' : 'far') . ' fa-star" style="color: #f1c40f;"></i>';
    }
    return $stars;
}

// Hàm định dạng giá tiền
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VNĐ';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="author" content="TonDucThangUniversity">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    <title><?php echo $page_title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,400;0,600;0,700;1,200;1,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/vegas.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/style.css" rel="stylesheet">
    <link rel="canonical" href="<?php echo $canonical_url; ?>">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Hotel",
        "name": "Cửu Long Resort",
        "description": "Cửu Long Resort cung cấp các loại phòng nghỉ dưỡng sang trọng tại Vĩnh Long, bao gồm Standard, Deluxe, Suite, Family, Villa và Presidential Suite.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "10/10 Nguyễn Văn Thiệt, Phường 3",
            "addressLocality": "Vĩnh Long",
            "addressCountry": "VN"
        },
        "telephone": "+84987654321",
        "url": "https://cuulongresort.com/room",
        "image": "https://cuulongresort.com/img/Room/deluxe3.jpg",
        "offers": [
            {"@type": "Offer", "name": "Standard Room", "price": "400000", "priceCurrency": "VND", "availability": "https://schema.org/InStock"},
            {"@type": "Offer", "name": "Deluxe Room", "price": "500000", "priceCurrency": "VND", "availability": "https://schema.org/InStock"},
            {"@type": "Offer", "name": "Suite Room", "price": "700000", "priceCurrency": "VND", "availability": "https://schema.org/InStock"},
            {"@type": "Offer", "name": "Family Room", "price": "900000", "priceCurrency": "VND", "availability": "https://schema.org/InStock"},
            {"@type": "Offer", "name": "Villa", "price": "1500000", "priceCurrency": "VND", "availability": "https://schema.org/InStock"},
            {"@type": "Offer", "name": "Presidential Suite", "price": "2000000", "priceCurrency": "VND", "availability": "https://schema.org/InStock"}
        ]
    }
    </script>
    <style>
        /* Responsive fixes */
        @media (max-width: 768px) {
            .room-section {
                flex-direction: column !important;
                padding: 25px !important;
                margin: 20px !important;
            }
            .room-section img {
                margin-bottom: 20px;
            }
            .combo-section {
                margin: 20px !important;
            }
        }
        
        /* Custom Buttons & Links */
        .custom-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px;
            background-color: #41554d;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .custom-button:hover {
            background-color: #2a3a33;
            transform: translateY(-2px);
            color: white;
        }
        
        .custom-link {
            color: #41554d;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .custom-link:hover {
            color: #2a3a33;
            text-decoration: underline;
        }
        
        /* Rating and Facility Icons */
        .infor-wrap .infor-item {
            margin-right: 15px;
        }
        
        /* Cards for combos */
        .card {
            height: 100%;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        /* Social Icons */
        .social-icon-link {
            display: inline-block;
            margin-right: 10px;
            color: white;
            font-size: 18px;
        }
        
        .social-icon-link:hover {
            color: #f1c40f;
        }
    </style>
</head>
<body style="background-color: #41554d">
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

        <?php foreach ($rooms as $id => $room): ?>
            <!-- <?php echo $room['name']; ?> Room -->
            <section class="room-section" style="background-color: #ffffff; margin: <?php echo $id === 1 ? '150px' : '0'; ?> 60px 30px 60px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border-radius: 16px; overflow: hidden; display: flex; padding: 50px; gap: 30px;" data-aos="fade-left">
                <div class="col-lg-6 col-12" style="position: relative;">
                    <?php if (isset($room['discount'])): ?>
                        <div class="label" style="position: absolute; top: 10px; left: 10px; background: red; color: white; padding: 5px 10px; border-radius: 5px; font-size: 15px; font-weight: bold;">Giảm <?php echo $room['discount']; ?>%</div>
                    <?php elseif (isset($room['label'])): ?>
                        <div class="label" style="position: absolute; top: 10px; left: 10px; background: black; color: white; padding: 5px 10px; border-radius: 5px; font-size: 15px; font-weight: bold;"><?php echo $room['label']; ?></div>
                    <?php endif; ?>
                    <img src="<?php echo $BASE_PATH; ?>/layout/images/Room/<?php echo $room['image']; ?>" alt="Phòng <?php echo $room['name']; ?> tại Cửu Long Resort" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                </div>
                <div class="col-lg-6 col-12 px-4">
                    <div>
                        <h1 class="room-title" style="font-size: 32px; margin-bottom: 20px;">Phòng <?php echo $room['name']; ?></h1>
                        <div class="infor-wrap" style="font-size: 18px; margin-bottom: 15px;">
                            <span class="infor-item"><i class="fas fa-bed"></i> <?php echo $room['beds']; ?></span> 
                            <span class="infor-item"><i class="fas fa-user-friends"></i> <?php echo $room['capacity']; ?> khách</span>
                        </div>
                        <p style="font-size: 17px; margin-top: 10px; line-height: 1.6; font-weight: 300;"><?php echo $room['description']; ?></p>
                        <a class="custom-link" href="<?php echo strtolower($room['name']); ?>.php" style="font-size: 20px;">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                        <div class="rating" style="margin-top: 15px;">
                            <?php echo renderStars($room['rating']); ?>
                        </div>
                    </div>
                    <div style="text-align: right; margin-top: 100px;">
                        <a class="custom-button"><?php echo formatPrice($room['price']); ?> / Đêm</a>
                        <a class="custom-button" href="<?php echo isset($_SESSION['user_id']) ? $BASE_PATH . '/layout/booking.php?room=' . $id : $BASE_PATH . '/layout/login.php?redirect=room.php'; ?>">Đặt phòng ngay!</a>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>

        <!-- Combo Section -->
        <section class="combo-section" style="margin: 0 60px 60px 60px;" data-aos="fade-up">
            <h1 class="text-center" style="font-size: 2.5rem; color: #ffffff; margin-bottom: 40px;">Combo Ưu Đãi Đặc Biệt</h1>
            <div class="row">
                <?php foreach ($combos as $combo): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; height: 100%;">
                            <div class="card-body" style="padding: 20px;">
                                <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 15px;"><?php echo htmlspecialchars($combo['name']); ?></h3>
                                <p style="font-size: 16px; line-height: 1.6; margin-bottom: 15px;"><?php echo htmlspecialchars($combo['description'] ?? 'Combo tiết kiệm với nhiều ưu đãi đặc biệt'); ?></p>
                                <p style="font-size: 18px; color: #e74c3c; font-weight: bold; margin-bottom: 15px;">Giảm <?php echo htmlspecialchars($combo['discount_percent']); ?>%</p>
                                <a class="custom-button" href="<?php echo isset($_SESSION['user_id']) ? $BASE_PATH . '/layout/booking.php?combo=' . $combo['id'] : $BASE_PATH . '/layout/login.php?redirect=room.php'; ?>">Đặt ngay!</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
    <script>
        AOS.init({ duration: 1000, once: true });
    </script>
</body>
</html>