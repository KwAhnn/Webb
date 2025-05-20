<?php
session_start();

$BASE_PATH = '/CUULONGRESORT';

// Include DAO files
require_once __DIR__ . '/../DAO/TourDAO.php';
require_once __DIR__ . '/../DAO/TourBookingDAO.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: {$BASE_PATH}/layout/login.php?redirect=booktour.php");
    exit;
}

// Kết nối database
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=cuulong_resort;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Không thể kết nối với cơ sở dữ liệu. Vui lòng thử lại sau.");
}

// Ánh xạ tên tour sang tour_id
$tour_map = [
    'luc-tinh' => 1,
    'mien-tay-xanh' => 2,
    'mien-tay-mot-ngay' => 3
];

// Lấy tour từ query
$tour_key = isset($_GET['tour']) ? $_GET['tour'] : '';
$tour_id = isset($tour_map[$tour_key]) ? $tour_map[$tour_key] : 0;

// Lấy thông tin tour qua DAO
$tour = null;
if ($tour_id > 0) {
    $tour = TourDAO::getTourById($pdo, $tour_id);
}
if (!$tour) {
    header("Location: {$BASE_PATH}/view/tour.php");
    exit;
}

// Lấy thông tin user từ session
$user = [
    'full_name' => $_SESSION['user_name'] ?? '',
    'email' => $_SESSION['user_email'] ?? '',
    'phone' => $_SESSION['user_phone'] ?? '',
    'cccd' => $_SESSION['user_cccd'] ?? ''
];

// Xử lý duration để tính end_date
$duration_days = 1; // Mặc định 1 ngày
if (strpos($tour['duration'], '4N3Đ') !== false) {
    $duration_days = 3;
} elseif (strpos($tour['duration'], '3N2Đ') !== false) {
    $duration_days = 2;
} elseif (strpos($tour['duration'], '1 Ngày') !== false) {
    $duration_days = 1;
}

// Lưu lại dữ liệu đã nhập khi có lỗi
$form_data = [
    'full_name' => $_POST['full_name'] ?? $user['full_name'],
    'email' => $_POST['email'] ?? $user['email'],
    'phone' => $_POST['phone'] ?? $user['phone'],
    'cccd' => $_POST['cccd'] ?? $user['cccd'],
    'start_date' => $_POST['start_date'] ?? '',
    'guests' => $_POST['guests'] ?? 1,
    'children' => $_POST['children'] ?? 0,
    'special_requests' => $_POST['special_requests'] ?? '',
    'emergency_contact' => $_POST['emergency_contact'] ?? '',
    'payment_method' => $_POST['payment_method'] ?? ''
];

// Xử lý đặt tour
$booking_success = false;
$booking_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = [
        'full_name', 'email', 'phone', 'start_date', 'guests', 'payment_method', 'emergency_contact'
    ];
    if (empty($user['cccd'])) {
        $required_fields[] = 'cccd';
    }
    $missing_fields = [];
    foreach ($required_fields as $field) {
        $value = isset($_POST[$field]) ? trim($_POST[$field]) : (isset($user[$field]) ? trim($user[$field]) : '');
        if ($value === '') {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        $booking_error = 'Vui lòng điền đầy đủ thông tin trong các trường bắt buộc.';
    } else {
        $full_name = $_POST['full_name'] ?? $user['full_name'];
        $email = $_POST['email'] ?? $user['email'];
        $phone = $_POST['phone'] ?? $user['phone'];
        $cccd = $_POST['cccd'] ?? $user['cccd'];
        $start_date = $_POST['start_date'];
        $guests = (int)$_POST['guests'];
        $children = (int)$_POST['children'];
        $payment_method = $_POST['payment_method'];
        $special_requests = $_POST['special_requests'] ?? '';
        $emergency_contact = $_POST['emergency_contact'];

        // Tính end_date dựa trên start_date và duration
        $start = new DateTime($start_date);
        $end_date = $start->modify("+$duration_days days")->format('Y-m-d');

        if ($start >= new DateTime($end_date)) {
            $booking_error = 'Ngày bắt đầu không hợp lệ.';
        } else {
            try {
                // Ánh xạ payment_method
                $payment_method_map = [
                    'cash' => 'tại quầy',
                    'bank' => 'trực tuyến',
                    'credit' => 'trực tuyến'
                ];
                $db_payment_method = $payment_method_map[$payment_method] ?? 'tại quầy';

                // Đặt tour qua DAO
                $tour_booking_id = TourBookingDAO::bookTour(
                    $pdo,
                    $_SESSION['user_id'],
                    $tour_id,
                    $start_date,
                    $end_date,
                    $guests,
                    $children,
                    $db_payment_method,
                    $special_requests,
                    $emergency_contact
                );

                // Cập nhật thông tin user trong session
                if (empty($user['full_name']) && $full_name) $_SESSION['user_name'] = $full_name;
                if (empty($user['email']) && $email) $_SESSION['user_email'] = $email;
                if (empty($user['phone']) && $phone) $_SESSION['user_phone'] = $phone;
                if (empty($user['cccd']) && $cccd) $_SESSION['user_cccd'] = $cccd;

                $booking_success = true;
                $_SESSION['booking_success'] = [
                    'message' => 'Đặt tour thành công! Cảm ơn bạn đã đặt tour tại Cửu Long Resort.'
                ];
            } catch (PDOException $e) {
                error_log("Tour booking error: " . $e->getMessage());
                $booking_error = 'Có lỗi xảy ra trong quá trình đặt tour. Vui lòng thử lại sau.';
            }
        }
    }
}

// Format giá
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VNĐ';
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt tour <?php echo htmlspecialchars($tour['name']); ?> - Cửu Long Resort</title>
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
<body style="background-color: #41554d;">
    <main>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="<?php echo $BASE_PATH; ?>/index.php">
                    <img src="<?php echo $BASE_PATH; ?>/layout/images/LOGO.png" style="width: 50px" class="navbar-brand-image img-fluid" alt="Logo Cuu Long Resort">C|L RESORT
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
                        <a class="btn custom-btn custom-border-btn" href="<?php echo $BASE_PATH; ?>/layout/profile.php">Hồ sơ<i class="bi-person ms-2"></i></a>
                    </div>
                </div>
            </div>
        </nav>

        <section class="about-section section-padding" style="margin-top: 100px; padding: 80px 0; background-color: #41554d;" data-aos="fade-left">
            <div class="container">
                <?php if ($booking_success): ?>
                <div class="row">
                    <div class="col-lg-12 col-12 px-3">
                        <div style="background-color: #fff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 400px; text-align: center;">
                            <i class="fa fa-check-circle" style="font-size: 80px; color: #41554d; margin-bottom: 20px;"></i>
                            <h1 class="mb-4">Đặt tour thành công!</h1>
                            <p class="mb-4" style="font-size: 18px;">Cảm ơn bạn đã chọn Cửu Long Resort. Chúng tôi đã nhận được đơn đặt tour của bạn và sẽ xử lý trong thời gian sớm nhất.</p>
                            <p class="mb-4" style="font-size: 16px;">Một email xác nhận sẽ được gửi đến địa chỉ email của bạn với chi tiết đặt tour.</p>
                            <div class="mt-5">
                                <a href="<?php echo $BASE_PATH; ?>/layout/profile.php?page=bookings" class="custom-button">Xem đơn đặt tour</a>
                                <a href="<?php echo $BASE_PATH; ?>/view/tour.php" class="custom-button">Quay lại trang tour</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <div class="col-lg-4 col-12 px-3">
                        <div style="background-color: #fff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 600px;">
                            <h3 style="margin-bottom: 25px;">Đặt tour: <?php echo htmlspecialchars($tour['name']); ?></h3>
                            <?php if (!empty($booking_error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $booking_error; ?>
                            </div>
                            <?php endif; ?>
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                        value="<?php echo htmlspecialchars($form_data['full_name']); ?>"
                                        <?php echo $user['full_name'] ? 'readonly' : 'required'; ?>>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?php echo htmlspecialchars($form_data['email']); ?>"
                                        <?php echo $user['email'] ? 'readonly' : 'required'; ?>>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?php echo htmlspecialchars($form_data['phone']); ?>"
                                        <?php echo $user['phone'] ? 'readonly' : 'required'; ?>>
                                </div>
                                <?php if (empty($user['cccd'])): ?>
                                <div class="mb-3">
                                    <label for="cccd" class="form-label">CCCD</label>
                                    <input type="text" class="form-control" id="cccd" name="cccd"
                                        pattern="[0-9]{9,12}" maxlength="12"
                                        value="<?php echo htmlspecialchars($form_data['cccd']); ?>" required>
                                </div>
                                <?php else: ?>
                                <input type="hidden" name="cccd" value="<?php echo htmlspecialchars($user['cccd']); ?>">
                                <?php endif; ?>
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Ngày bắt đầu tour</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        min="<?php echo date('Y-m-d'); ?>"
                                        value="<?php echo htmlspecialchars($form_data['start_date']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="guests" class="form-label">Số người lớn</label>
                                    <input type="number" class="form-control" id="guests" name="guests" min="1" max="50"
                                        value="<?php echo htmlspecialchars($form_data['guests']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="children" class="form-label">Số trẻ em</label>
                                    <input type="number" class="form-control" id="children" name="children" min="0" max="50"
                                        value="<?php echo htmlspecialchars($form_data['children']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Chọn phương thức...</option>
                                        <option value="cash" <?php if($form_data['payment_method']=='cash') echo 'selected'; ?>>Tiền mặt (Tại quầy)</option>
                                        <option value="bank" <?php if($form_data['payment_method']=='bank') echo 'selected'; ?>>Chuyển khoản</option>
                                        <option value="credit" <?php if($form_data['payment_method']=='credit') echo 'selected'; ?>>Thẻ tín dụng</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="emergency_contact" class="form-label">Người liên hệ khẩn cấp</label>
                                    <input type="text" class="form-control" id="emergency_contact" name="emergency_contact"
                                        value="<?php echo htmlspecialchars($form_data['emergency_contact']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="special_requests" class="form-label">Yêu cầu đặc biệt (nếu có)</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="2"><?php echo htmlspecialchars($form_data['special_requests']); ?></textarea>
                                </div>
                                <div style="text-align: center; margin-top: 40px;">
                                    <button type="submit" class="custom-button">Đặt tour ngay!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-8 col-12">
                        <div style="background-color: #ffffff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 600px;">
                            <h2><?php echo htmlspecialchars($tour['name']); ?></h2>
                            <img src="<?php echo $BASE_PATH; ?>/layout/images/service/TOUR<?php echo $tour_id; ?>.jpg" class="img-fluid rounded mb-3" alt="<?php echo htmlspecialchars($tour['name']); ?>">
                            <p><?php echo nl2br(htmlspecialchars($tour['description'])); ?></p>
                            <p><strong>Giá tour:</strong> <?php echo formatPrice($tour['price']); ?> / người lớn</p>
                            <p><strong>Thời gian:</strong> <?php echo htmlspecialchars($tour['duration']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
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
        AOS.init({
            duration: 1000,
            once: true,
        });
    </script>
</body>
</html>