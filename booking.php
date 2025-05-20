<?php
session_start();

$BASE_PATH = '/CUULONGRESORT';

// Include DAO files
require_once __DIR__ . '/../DAO/RoomDAO.php';
require_once __DIR__ . '/../DAO/ComboDAO.php';
require_once __DIR__ . '/../DAO/ServiceDAO.php';
require_once __DIR__ . '/../DAO/BookingDAO.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: {$BASE_PATH}/layout/login.php?redirect=booking.php");
    exit;
}

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=cuulong_resort;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Không thể kết nối với cơ sở dữ liệu. Vui lòng thử lại sau.");
}

// Get room and combo from query parameters
$room_id = isset($_GET['room']) ? (int)$_GET['room'] : 0;
$combo_id = isset($_GET['combo']) ? (int)$_GET['combo'] : 0;

// Fetch room details
$rooms = [];
if ($combo_id > 0) {
    // Lấy room_id từ combo qua DAO
    $combo = ComboDAO::getComboById($pdo, $combo_id);
    if ($combo) {
        $room_id = $combo['room_id'];
    } else {
        header("Location: {$BASE_PATH}/view/room.php");
        exit;
    }
}

// Lấy thông tin phòng qua DAO
$room = RoomDAO::getRoomById($pdo, $room_id);
if ($room) {
    $rooms[$room['id']] = [
        'name' => $room['name'],
        'image' => '',
        'capacity' => "1-{$room['capacity']}",
        'beds' => '1 giường lớn',
        'rating' => 4,
        'price' => $room['price'],
        'original_price' => $room['original_price'],
        'description' => $room['facilities']
    ];
    // Lấy ảnh chính qua DAO
    $image = RoomDAO::getPrimaryImage($pdo, $room_id);
    if ($image) {
        $rooms[$room['id']]['image'] = $image['image_url'];
    }
} else {
    header("Location: {$BASE_PATH}/view/room.php");
    exit;
}

// Lấy thông tin combo qua DAO
$combos = [];
$combo_services = [];
if ($combo_id > 0) {
    $combo = ComboDAO::getComboById($pdo, $combo_id);
    if ($combo) {
        $combos[$combo['id']] = [
            'name' => $combo['name'],
            'room_id' => $combo['room_id'],
            'discount_percent' => $combo['discount_percent'],
            'services' => ''
        ];
        // Lấy dịch vụ của combo qua DAO
        $services = ComboDAO::getServicesByComboId($pdo, $combo_id);
        $combos[$combo['id']]['services'] = implode(', ', $services);
        $combo_services = $services;
    }
}

// Lấy tất cả dịch vụ qua DAO
$all_services = ServiceDAO::getAllServices($pdo);

// Validate room or combo selection
$selected_room = $rooms[$room_id] ?? null;
$selected_combo = $combo_id > 0 ? ($combos[$combo_id] ?? null) : null;
$additional_services = $combo_services;
$base_price = 0;
$discount = 0;

if ($selected_room) {
    $base_price = $selected_room['price'];
    if ($selected_combo) {
        $discount = $selected_combo['discount_percent'];
        $base_price = $base_price * (100 - $discount) / 100;
    }
} else {
    header("Location: {$BASE_PATH}/view/room.php");
    exit;
}

// Handle form submission
$booking_success = false;
$booking_error = '';
$total_price = 0; // Initialize total_price

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form inputs
    $required_fields = [
        'full_name', 'email', 'phone',
        'check_in', 'check_out', 'guests', 'payment_method', 'emergency_contact'
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
        $check_in = $_POST['check_in'];
        $check_out = $_POST['check_out'];
        $guests = (int)$_POST['guests'];
        $payment_method = $_POST['payment_method'];
        $notes = $_POST['special_requests'] ?? '';
        $emergency_contact = $_POST['emergency_contact'];
        $full_name = $_POST['full_name'] ?? $user['full_name'];
        $email = $_POST['email'] ?? $user['email'];
        $phone = $_POST['phone'] ?? $user['phone'];
        $cccd = $_POST['cccd'] ?? $user['cccd'];

        // Validate dates
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $days = $check_in_date->diff($check_out_date)->days;

        if ($days <= 0) {
            $booking_error = 'Ngày nhận phòng phải trước ngày trả phòng.';
        } elseif ($guests > (int)explode('-', $selected_room['capacity'])[1]) {
            $booking_error = 'Số khách vượt quá sức chứa của phòng.';
        } else {
            try {
                // Tính toán giá
                $total_price = $base_price * $days;
                $selected_services = $_POST['services'] ?? [];
                $additional_service_cost = 0;
                foreach ($selected_services as $service) {
                    if (isset($all_services[$service]) && !in_array($service, $combo_services)) {
                        $additional_service_cost += $all_services[$service]['price'];
                        $additional_services[] = $service;
                    }
                }
                $total_price += $additional_service_cost * $days;

                $payment_method_map = [
                    'cash' => 'tại quầy',
                    'bank' => 'trực tuyến',
                    'credit' => 'trực tuyến'
                ];
                $db_payment_method = $payment_method_map[$payment_method] ?? 'tại quầy';

                // Đặt phòng qua DAO
                $booking_id = BookingDAO::bookRoom(
                    $pdo,
                    $_SESSION['user_id'],
                    $room_id,
                    $combo_id > 0 ? $combo_id : null,
                    $check_in,
                    $check_out,
                    $guests,
                    $db_payment_method,
                    $notes,
                    $emergency_contact,
                    $additional_services,
                    $all_services,
                    $total_price
                );

                // Update session user info
                if (empty($user['full_name']) && $full_name) $_SESSION['user_name'] = $full_name;
                if (empty($user['email']) && $email) $_SESSION['user_email'] = $email;
                if (empty($user['phone']) && $phone) $_SESSION['user_phone'] = $phone;
                if (empty($user['cccd']) && $cccd) $_SESSION['user_cccd'] = $cccd;

                // Mark success
                $booking_success = true;
                $_SESSION['booking_success'] = [
                    'message' => 'Đặt phòng thành công! Cảm ơn bạn đã đặt phòng tại Cửu Long Resort.'
                ];
            } catch (PDOException $e) {
                error_log("Booking error: " . $e->getMessage());
                $booking_error = $e->getMessage() === 'Không còn phòng trống trong khoảng thời gian này'
                    ? 'Không còn phòng trống trong khoảng thời gian này.'
                    : 'Có lỗi xảy ra trong quá trình đặt phòng. Vui lòng thử lại sau.';
            }
        }
    }
}

// Format price function
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' VNĐ';
}

// Render stars function
function renderStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= '<i class="' . ($i <= $rating ? 'fas' : 'far') . ' fa-star" style="color: #f1c40f;"></i>';
    }
    return $stars;
}

// User info from session
$user = [
    'full_name' => $_SESSION['user_name'] ?? '',
    'email' => $_SESSION['user_email'] ?? '',
    'phone' => $_SESSION['user_phone'] ?? '',
    'cccd' => $_SESSION['user_cccd'] ?? ''
];

// Form data persistence
$form_data = [
    'full_name' => $_POST['full_name'] ?? $user['full_name'],
    'email' => $_POST['email'] ?? $user['email'],
    'phone' => $_POST['phone'] ?? $user['phone'],
    'cccd' => $_POST['cccd'] ?? $user['cccd'],
    'check_in' => $_POST['check_in'] ?? '',
    'check_out' => $_POST['check_out'] ?? '',
    'guests' => $_POST['guests'] ?? '',
    'special_requests' => $_POST['special_requests'] ?? '',
    'emergency_contact' => $_POST['emergency_contact'] ?? '',
    'payment_method' => $_POST['payment_method'] ?? ''
];
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Đặt phòng tại Cửu Long Resort">
    <meta name="author" content="TonDucThangUniversity">
    <title>Đặt phòng <?php echo $selected_room['name']; ?> - Cửu Long Resort</title>
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
                <!-- Thông báo đặt phòng thành công -->
                <div class="row">
                    <div class="col-lg-12 col-12 px-3">
                        <div style="background-color: #fff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 400px; text-align: center;">
                            <i class="fa fa-check-circle" style="font-size: 80px; color: #41554d; margin-bottom: 20px;"></i>
                            <h1 class="mb-4">Đặt phòng thành công!</h1>
                            <p class="mb-4" style="font-size: 18px;">Cảm ơn bạn đã chọn Cửu Long Resort. Chúng tôi đã nhận được đơn đặt phòng của bạn và sẽ xử lý trong thời gian sớm nhất.</p>
                            <p class="mb-4" style="font-size: 16px;">Một email xác nhận sẽ được gửi đến địa chỉ email của bạn với chi tiết đặt phòng.</p>
                            <div class="mt-5">
                                <a href="<?php echo $BASE_PATH; ?>/layout/profile.php?page=bookings" class="custom-button">Xem đơn đặt phòng</a>
                                <a href="<?php echo $BASE_PATH; ?>/view/room.php" class="custom-button">Quay lại trang phòng</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <!-- Form đặt phòng -->
                    <div class="col-lg-3 col-6 px-3">
                        <div style="background-color: #fff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 600px;">
                            <h3 style="margin-bottom: 25px;">Đặt phòng:</h3>
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
                                    <label for="check_in" class="form-label">Ngày nhận phòng</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in"
                                        min="<?php echo date('Y-m-d'); ?>"
                                        value="<?php echo htmlspecialchars($form_data['check_in']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="check_out" class="form-label">Ngày trả phòng</label>
                                    <input type="date" class="form-control" id="check_out" name="check_out"
                                        min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                        value="<?php echo htmlspecialchars($form_data['check_out']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="guests" class="form-label">Số khách</label>
                                    <select class="form-select" id="guests" name="guests">
                                        <?php
                                        $max_guests = explode('-', $selected_room['capacity']);
                                        $max = end($max_guests);
                                        for ($i = 1; $i <= $max; $i++) {
                                            $selected = ($form_data['guests'] == $i) ? 'selected' : '';
                                            echo "<option value=\"$i\" $selected>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Chọn phương thức...</option>
                                        <option value="cash" <?php if($form_data['payment_method']=='cash') echo 'selected'; ?>>Tiền mặt</option>
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
                                    <button type="submit" class="custom-button">Đặt phòng ngay!</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Thông tin phòng -->
                    <div class="col-lg-9 col-12">
                        <div style="background-color: #ffffff; border-radius: 15px; padding: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-height: 600px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <img src="<?php echo $BASE_PATH; ?>/layout/images/Room/<?php echo $selected_room['image']; ?>" class="img-fluid rounded" alt="<?php echo $selected_room['name']; ?> Room">
                                        </div>
                                        <div class="col-6">
                                            <?php 
                                            $image_name = str_replace('3.jpg', '2.jpg', $selected_room['image']);
                                            ?>
                                            <img src="<?php echo $BASE_PATH; ?>/layout/images/Room/<?php echo $image_name; ?>" class="img-fluid rounded" alt="<?php echo $selected_room['name']; ?> Room 2">
                                        </div>
                                        <div class="col-6 position-relative" style="cursor: pointer;" id="showGalleryBtn">
                                            <?php 
                                            $image_name = str_replace('3.jpg', '1.jpg', $selected_room['image']);
                                            ?>
                                            <img src="<?php echo $BASE_PATH; ?>/layout/images/Room/<?php echo $image_name; ?>" class="img-fluid rounded img-zoom" alt="<?php echo $selected_room['name']; ?> Room 1" style="filter: brightness(50%);">
                                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 14px; pointer-events: none;">Xem thêm ảnh+
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h2 class="mb-3"><?php echo $selected_room['name']; ?> Room
                                        <?php if ($selected_combo): ?>
                                            <span class="badge bg-danger">Combo: <?php echo $selected_combo['name']; ?> (Giảm <?php echo $selected_combo['discount_percent']; ?>%)</span>
                                        <?php endif; ?>
                                    </h2>
                                    <p style="line-height: 1.6; color: black; font-size: 19px;"><?php echo $selected_room['description']; ?></p>
                                    <ul class="room-feature-list">
                                        <li><i class="fa-solid fa-user-group icon"></i> <?php echo $selected_room['capacity']; ?> khách</li>
                                        <li><i class="fa-solid fa-wifi icon"></i> Wi-Fi tốc độ cao miễn phí</li>
                                        <li><i class="fa-solid fa-tv icon"></i> TV màn hình phẳng</li>
                                        <li><i class="fa-solid fa-bed icon"></i> <?php echo $selected_room['beds']; ?></li>
                                        <li><i class="fa-solid fa-shower icon"></i> Phòng tắm hiện đại</li>
                                        <?php if (!empty($additional_services)): ?>
                                        <li><i class="fa-solid fa-concierge-bell icon"></i> Dịch vụ: <?php echo implode(', ', $additional_services); ?></li>
                                        <?php endif; ?>
                                    </ul>
                                    
                                    <!-- Dịch vụ bổ sung -->
                                    <div class="my-4">
                                        <h4>Dịch vụ bổ sung:</h4>
                                        <div class="row">
                                            <?php foreach ($all_services as $name => $service): ?>
                                                <?php if (!in_array($name, $combo_services)): ?>
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="<?php echo $name; ?>" id="<?php echo strtolower($name); ?>">
                                                            <label class="form-check-label" for="<?php echo strtolower($name); ?>">
                                                                <?php echo $name; ?> (+<?php echo formatPrice($service['price']); ?>)
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div style="text-align: right; margin-top: 50px;">
                                        <span class="custom-button"><?php echo formatPrice($base_price); ?> / Đêm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- ảnh toàn màn hình -->
            <div id="galleryLightbox" style="display:none; position: fixed; top:0; left:0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.8); z-index: 1050; overflow-y: auto;">
                <div class="container py-5">
                    <div class="row g-3 justify-content-center" id="galleryImages">
                        <!-- Ảnh sẽ được thêm bằng JS -->
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-light" onclick="hideGallery()">Đóng</button>
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

        // Gallery logic
        document.getElementById('showGalleryBtn').onclick = function() {
            document.getElementById('galleryLightbox').style.display = 'block';
        };
        function hideGallery() {
            document.getElementById('galleryLightbox').style.display = 'none';
        }
        document.getElementById('galleryLightbox').addEventListener('click', function(e) {
            if (e.target === this) hideGallery();
        });
    </script>
</body>
</html>