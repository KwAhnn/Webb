<?php
// Start session if not already started
session_start();

// Define base path
$BASE_PATH = '/CUULONGRESORT';

// Check if user is logged in; redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header("Location: {$BASE_PATH}/layout/login.php?redirect=booking.php");
    exit;
}

// Database connection (replace with your actual credentials)
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=cuulong_resort;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Không thể kết nối với cơ sở dữ liệu. Vui lòng thử lại sau. Chi tiết lỗi: " . $e->getMessage());
}
// Get room and combo from query parameters
$room_id = isset($_GET['room']) ? (int)$_GET['room'] : 0; 
$combo_id = isset($_GET['combo']) ? (int)$_GET['combo'] : 0;

// Fetch room details from database
$rooms = [];
$stmt = $pdo->prepare("SELECT id, name, capacity, price, original_price, facilities FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
if ($room) {
    $rooms[$room['id']] = [
        'name' => $room['name'],
        'image' => '', // Will fetch from room_images
        'capacity' => "1-{$room['capacity']}",
        'beds' => '1 giường lớn', // Simplified for demo; adjust based on actual data
        'rating' => 4, // Static for now; can be dynamic if stored in DB
        'price' => $room['price'],
        'original_price' => $room['original_price'],
        'description' => $room['facilities']
    ];
    // Fetch primary image
    $img_stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE room_id = ? AND is_primary = 1");
    $img_stmt->execute([$room_id]);
    $image = $img_stmt->fetch(PDO::FETCH_ASSOC);
    if ($image) {
        $rooms[$room['id']]['image'] = $image['image_url'];
    }
}

// Fetch combo details from database
$combos = [];
if ($combo_id > 0) {
    $stmt = $pdo->prepare("SELECT id, name, room_id, discount_percent FROM combos WHERE id = ?");
    $stmt->execute([$combo_id]);
    $combo = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($combo) {
        $combos[$combo['id']] = [
            'name' => $combo['name'],
            'room_id' => $combo['room_id'],
            'discount_percent' => $combo['discount_percent'],
            'services' => '' // Will fetch services
        ];
        // Fetch services for the combo
        $svc_stmt = $pdo->prepare("SELECT s.name FROM services s JOIN combo_services cs ON s.id = cs.service_id WHERE cs.combo_id = ?");
        $svc_stmt->execute([$combo_id]);
        $services = $svc_stmt->fetchAll(PDO::FETCH_COLUMN);
        $combos[$combo['id']]['services'] = implode(', ', $services);
    }
}

// Validate room or combo selection
$selected_room = null;
$selected_combo = null;
$additional_services = [];
$final_price = 0;

if ($combo_id > 0 && isset($combos[$combo_id])) {
    $selected_combo = $combos[$combo_id];
    $room_id = $selected_combo['room_id'];
    $selected_room = $rooms[$room_id] ?? null;
    if ($selected_room) {
        $discount = $selected_combo['discount_percent'];
        $final_price = $selected_room['price'] * (100 - $discount) / 100;
        $additional_services[] = $selected_combo['services'];
    }
} elseif ($room_id > 0 && isset($rooms[$room_id])) {
    $selected_room = $rooms[$room_id];
    $discount = 0; // No discount if no combo
    $final_price = $selected_room['price'];
} else {
    header("Location: {$BASE_PATH}/view/room.php");
    exit;
}

// Handle form submission
$booking_success = false;
$booking_error = '';

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
        // Ưu tiên lấy từ POST, nếu không có thì lấy từ $user (session)
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
                // Call stored procedure to book room
                $stmt = $pdo->prepare("CALL book_room(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @booking_id)");
                $stmt->execute([
                    $_SESSION['user_id'],
                    $room_id,
                    $combo_id > 0 ? $combo_id : null,
                    $check_in,
                    $check_out,
                    $guests,
                    0, // Assuming no children for simplicity; adjust if needed
                    $payment_method,
                    $notes,
                    $emergency_contact
                ]);

                // Retrieve booking ID
                $stmt = $pdo->query("SELECT @booking_id AS booking_id");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $booking_id = $result['booking_id'];

                // Handle additional services
                $selected_services = $_POST['services'] ?? [];
                $service_prices = [
                    'breakfast' => 100000,
                    'spa' => 300000,
                    'airport' => 200000,
                    'tour' => 400000 // Adjust as per services table
                ];

                if (!empty($selected_services)) {
                    $svc_stmt = $pdo->prepare("SELECT id, name FROM services WHERE name = ?");
                    $insert_svc = $pdo->prepare("INSERT INTO booking_services (booking_id, service_id, quantity) VALUES (?, ?, 1)");
                    foreach ($selected_services as $service) {
                        if (isset($service_prices[$service])) {
                            $svc_stmt->execute([ucfirst($service)]);
                            $service_data = $svc_stmt->fetch(PDO::FETCH_ASSOC);
                            if ($service_data) {
                                $insert_svc->execute([$booking_id, $service_data['id']]);
                                $additional_services[] = $service_data['name'];
                            }
                        }
                    }
                }

                // Cập nhật lại session user nếu có thông tin mới (chỉ cập nhật nếu chưa có)
                if (empty($user['full_name']) && $full_name) $_SESSION['user_name'] = $full_name;
                if (empty($user['email']) && $email) $_SESSION['user_email'] = $email;
                if (empty($user['phone']) && $phone) $_SESSION['user_phone'] = $phone;
                if (empty($user['cccd']) && $cccd) $_SESSION['user_cccd'] = $cccd;

                // Đánh dấu thành công
                $booking_success = true;

                // Lưu thông báo thành công vào session để profile.php có thể hiển thị
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
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link href="css/vegas.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <body style="background-color: #41554d;">
            <main>
                <nav class="navbar navbar-expand-lg">                
                    <div class="container">
                        <a class="navbar-brand d-flex align-items-center" href="<?php echo $BASE_PATH; ?>/index.php">
                            <img src="<?php echo $BASE_PATH; ?>/layout/images/LOGO.png" style="width: 50px" class="navbar-brand-image img-fluid" alt="Logo Cuu Long Resort">C|L RESORT</a>
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
                                        <a href="profile.php" class="custom-button">Xem đơn đặt phòng</a>
                                        <a href="Room.html" class="custom-button">Quay lại trang phòng</a>
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
                                            value="<?php echo htmlspecialchars($form_data['full_name'] ?? $user['full_name']); ?>"
                                            <?php echo $user['full_name'] ? 'readonly' : 'required'; ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($form_data['email'] ?? $user['email']); ?>"
                                            <?php echo $user['email'] ? 'readonly' : 'required'; ?>>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            value="<?php echo htmlspecialchars($form_data['phone'] ?? $user['phone']); ?>"
                                            <?php echo $user['phone'] ? 'readonly' : 'required'; ?>>
                                    </div>
                                    <?php if (empty($user['cccd'])): ?>
                                    <div class="mb-3">
                                        <label for="cccd" class="form-label">CCCD</label>
                                        <input type="text" class="form-control" id="cccd" name="cccd"
                                            pattern="[0-9]{9,12}" maxlength="12"
                                            value="<?php echo htmlspecialchars($form_data['cccd'] ?? ''); ?>" required>
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
                                                // Thêm ảnh phòng bổ sung nếu có
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
                                            <?php if (isset($selected_room['discount'])): ?>
                                                <span class="badge bg-danger">Giảm <?php echo $selected_room['discount']; ?>%</span>
                                            <?php elseif (isset($selected_room['label'])): ?>
                                                <span class="badge bg-dark"><?php echo $selected_room['label']; ?></span>
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
                                            <li><i class="fa-solid fa-concierge-bell icon"></i> <?php echo implode(', ', $additional_services); ?></li>
                                            <?php endif; ?>
                                        </ul>
                                        
                                        <!-- Dịch vụ bổ sung -->
                                        <div class="my-4">
                                            <h4>Dịch vụ bổ sung:</h4>
                                            <div class="row">
                                                <?php if (!in_array('Bữa sáng', $additional_services)): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="breakfast" id="breakfast" form="bookingForm">
                                                        <label class="form-check-label" for="breakfast">Bữa sáng (+150.000 VNĐ)</label>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!in_array('Đưa đón sân bay', $additional_services)): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="airport" id="airport" form="bookingForm">
                                                        <label class="form-check-label" for="airport">Đưa đón sân bay (+300.000 VNĐ)</label>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!in_array('Spa', $additional_services)): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="spa" id="spa" form="bookingForm">
                                                        <label class="form-check-label" for="spa">Dịch vụ spa (+500.000 VNĐ)</label>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input service-checkbox" type="checkbox" name="services[]" value="tour" id="tour" form="bookingForm">
                                                        <label class="form-check-label" for="tour">Tour địa phương (+400.000 VNĐ)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="text-align: right; margin-top: 50px;">
                                            <span class="custom-button"><?php echo formatPrice($final_price); ?> / Đêm</span>
                                        </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Form ẩn để xử lý các dịch vụ bổ sung -->
                    <form id="bookingForm" action="" method="post" style="display: none;">
                        <input type="hidden" name="full_name" id="hidden_full_name">
                        <input type="hidden" name="email" id="hidden_email">
                        <input type="hidden" name="phone" id="hidden_phone">
                        <input type="hidden" name="check_in" id="hidden_check_in">
                        <input type="hidden" name="check_out" id="hidden_check_out">
                        <input type="hidden" name="guests" id="hidden_guests">
                        <input type="hidden" name="special_requests" id="hidden_special_requests">
                        <!-- Các trường dịch vụ bổ sung sẽ được thêm bằng JavaScript -->
                    </form>

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
                    <!-- ...existing code... -->
                </footer>
            </main>
            <script src="js/jquery.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/jquery.sticky.js"></script>
            <script src="js/click-scroll.js"></script>
            <script src="js/vegas.min.js"></script>
            <script src="js/script.js"></script>
            <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
            <script src="js/room.js"></script>
            <script src="js/feedback.js"></script>
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
                // Đóng gallery khi click ra ngoài vùng ảnh
                document.getElementById('galleryLightbox').addEventListener('click', function(e) {
                    if (e.target === this) hideGallery();
                });
            </script>
        </body>
</html>