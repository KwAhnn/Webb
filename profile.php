<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id']) || !is_array($_SESSION['user']) || empty($_SESSION['user']['email'])) {
    error_log("Authentication failed in profile.php: Invalid or missing user session data - " . json_encode($_SESSION['user'] ?? 'not set'));
    header("Location: ./login.php");
    exit;
}

// Kết nối cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=localhost;dbname=Cuulong_Resort", "root", ""); // Cập nhật username và password
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection error in profile.php: " . $e->getMessage());
    die('<div class="container mt-5"><h3 class="text-center text-danger">Lỗi kết nối cơ sở dữ liệu. Vui lòng thử lại sau.</h3></div>');
}

$email = $_SESSION['user']['email'];
$user_id = $_SESSION['user_id'];

// Lấy thông tin người dùng, bao gồm profile_pic
$stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, cccd, address, profile_pic FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    error_log("profile.php: User not found for ID: $user_id");
    echo '<div class="container mt-5"><h3 class="text-center text-danger">Không tìm thấy thông tin người dùng. Vui lòng đăng nhập lại.</h3></div>';
    exit;
}

// Lấy danh sách đặt phòng và tour với chi tiết tên phòng/tour
$stmt = $pdo->prepare("SELECT b.id, b.room_id, b.combo_id, NULL as tour_id, b.checkin, b.checkout, 
                      b.total_price, b.payment_method, b.status, b.payment_status, r.name as room_name, 
                      NULL as tour_name, c.name as combo_name
                      FROM bookings b 
                      LEFT JOIN rooms r ON b.room_id = r.id
                      LEFT JOIN combos c ON b.combo_id = c.id
                      WHERE b.user_id = ?
                      UNION
                      SELECT tb.id, NULL as room_id, NULL as combo_id, tb.tour_id, tb.start_date as checkin, 
                      tb.end_date as checkout, tb.total_price, tb.payment_method, tb.status, tb.payment_status, 
                      NULL as room_name, t.name as tour_name, NULL as combo_name
                      FROM tour_bookings tb
                      LEFT JOIN tours t ON tb.tour_id = t.id
                      WHERE tb.user_id = ?
                      ORDER BY checkin DESC");
$stmt->execute([$user_id, $user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách hóa đơn với chi tiết
$stmt = $pdo->prepare("SELECT b.id, b.total_amount, b.discount, b.final_amount, b.payment_status, b.created_at, 
                      r.name as room_name, c.name as combo_name, bk.id as booking_id
                      FROM bills b
                      LEFT JOIN bookings bk ON b.booking_id = bk.id
                      LEFT JOIN rooms r ON bk.room_id = r.id
                      LEFT JOIN combos c ON bk.combo_id = c.id
                      WHERE bk.user_id = ?
                      ORDER BY b.created_at DESC");
$stmt->execute([$user_id]);
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách tin nhắn
$stmt = $pdo->prepare("SELECT id, subject, content, sent_at, is_read FROM messages WHERE user_id = ? ORDER BY sent_at DESC");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'personal';
$successMessage = $_SESSION['booking_success']['message'] ?? null;
if ($successMessage) {
    unset($_SESSION['booking_success']);
}

// Xử lý cập nhật hồ sơ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'settings') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $cccd = $_POST['cccd'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';
    $current_password = $_POST['current_password'] ?? '';

    // Kiểm tra mật khẩu hiện tại nếu người dùng muốn đổi mật khẩu
    $password_valid = true;
    if ($password) {
        // Lấy mật khẩu hiện tại từ DB
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $current_hash = $stmt->fetchColumn();
        if (!$current_password || !password_verify($current_password, $current_hash)) {
            $password_valid = false;
            $error_message = "Mật khẩu hiện tại không chính xác!";
        }
    }

    // Xử lý upload ảnh đại diện
    $profile_pic = $user['profile_pic'];
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_pic']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $new_filename = uniqid('avatar_') . '.' . $filetype;
            $upload_dir = 'layout/images/avatars/';
            
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $new_filename)) {
                $profile_pic = $new_filename;
            }
        }
    }

    if ($password_valid) {
        $updates = [];
        $params = [];
        
        if ($first_name) {
            $updates[] = "first_name = ?";
            $params[] = $first_name;
        }
        if ($last_name) {
            $updates[] = "last_name = ?";
            $params[] = $last_name;
        }
        if ($phone) {
            $updates[] = "phone = ?";
            $params[] = $phone;
        }
        if ($cccd) {
            $updates[] = "cccd = ?";
            $params[] = $cccd;
        }
        if ($address) {
            $updates[] = "address = ?";
            $params[] = $address;
        }
        if ($password && $password_valid) {
            $updates[] = "password = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($profile_pic != $user['profile_pic']) {
            $updates[] = "profile_pic = ?";
            $params[] = $profile_pic;
        }

        if ($updates) {
            $params[] = $user_id;
            $stmt = $pdo->prepare("UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?");
            $stmt->execute($params);
            $successMessage = "Cập nhật thông tin thành công!";
            
            // Cập nhật session
            $_SESSION['user']['first_name'] = $first_name ?: $user['first_name'];
            $_SESSION['user']['last_name'] = $last_name ?: $user['last_name'];
            
            header("Location: profile.php?page=settings");
            exit;
        }
    }
}

$BASE_PATH = './layout/'; // Đường dẫn tới thư mục layout
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Thông tin cá nhân, đặt phòng, đặt tour, hóa đơn và tin nhắn tại Cửu Long Resort">
    <title>Thông tin cá nhân - Cửu Long Resort</title>
    <link rel="stylesheet" href="<?php echo $BASE_PATH; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Poppins", sans-serif; background-color: #f8f9fa; }
        .profile-container { max-width: 1200px; margin: 2rem auto; padding: 0 15px; }
        h2, h4 { font-family: "Playfair Display", serif; }
        .nav-tabs { border-bottom: 2px solid #2f4f4f; }
        .nav-tabs .nav-link { color: #333; font-weight: 600; padding: 0.75rem 1.5rem; transition: all 0.3s ease; }
        .nav-tabs .nav-link:hover { color: #2f4f4f; }
        .nav-tabs .nav-link.active { color: #2f4f4f; border-bottom: 3px solid #2f4f4f; background-color: transparent; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .card-body { padding: 1.5rem; }
        .card-title { font-size: 1.5rem; margin-bottom: 1rem; }
        .table-responsive { margin-top: 1rem; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 0.75rem; vertical-align: middle; }
        .table th { background-color: #2f4f4f; color: white; font-weight: 600; }
        .table td img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .form-control { margin-bottom: 1rem; }
        .avatar-img { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-bottom: 1rem; }
        .badge { font-size: 0.85rem; padding: 0.35em 0.65em; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-primary { background-color: #007bff; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        .unread-badge { 
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 0.25em 0.5em;
            font-size: 0.75rem;
        }
        .message-item {
            position: relative;
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .message-item:hover {
            background-color: #f8f9fa;
        }
        .message-unread {
            font-weight: bold;
        }
        .message-date {
            font-size: 0.85rem;
            color: #6c757d;
        }
        @media (max-width: 768px) {
            .table th, .table td { font-size: 0.9rem; }
            .table td img { width: 40px; height: 40px; }
            .nav-tabs .nav-link { padding: 0.5rem 1rem; font-size: 0.9rem; }
            .avatar-img { width: 80px; height: 80px; }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Thanh thông tin người dùng và logout -->
    <div class="container mt-3 mb-4">
        <div class="d-flex justify-content-end align-items-center">
            <span class="me-3">
                <i class="fa fa-user-circle me-1"></i>
                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?> (<?php echo htmlspecialchars($user['email']); ?>)
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">
                <i class="fa fa-sign-out-alt me-1"></i> Đăng xuất
            </a>
        </div>
    </div>

    <div class="container profile-container">
        <div class="text-center">
            <!-- Hiển thị avatar -->
            <?php $avatarPath = $user['profile_pic'] ? 'layout/images/avatars/' . $user['profile_pic'] : 'layout/images/avatars/default-avatar.png'; ?>
            <img src="<?php echo $avatarPath; ?>" alt="Avatar" class="avatar-img">
            <h2 class="mb-4"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
        </div>

        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item"><a class="nav-link <?php echo $page == 'personal' ? 'active' : ''; ?>" href="profile.php?page=personal">Hồ sơ</a></li>
            <li class="nav-item"><a class="nav-link <?php echo $page == 'bookings' ? 'active' : ''; ?>" href="profile.php?page=bookings">Đặt phòng & Tour</a></li>
            <li class="nav-item"><a class="nav-link <?php echo $page == 'bills' ? 'active' : ''; ?>" href="profile.php?page=bills">Hóa đơn</a></li>
            <li class="nav-item position-relative">
                <a class="nav-link <?php echo $page == 'inbox' ? 'active' : ''; ?>" href="profile.php?page=inbox">
                    Tin nhắn
                    <?php
                    $unread_count = 0;
                    foreach ($messages as $message) {
                        if (!$message['is_read']) $unread_count++;
                    }
                    if ($unread_count > 0): ?>
                        <span class="badge bg-danger"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item"><a class="nav-link <?php echo $page == 'settings' ? 'active' : ''; ?>" href="profile.php?page=settings">Cài đặt</a></li>
        </ul>

        <div class="tab-content">
            <?php if ($page == 'personal'): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Thông tin cá nhân</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($user['phone'] ?: 'Chưa cung cấp'); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p><strong>CCCD:</strong> <?php echo htmlspecialchars($user['cccd'] ?: 'Chưa cung cấp'); ?></p>
                                <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($user['address'] ?: 'Chưa cung cấp'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Thống kê ngắn gọn -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Đặt phòng & Tour</h5>
                                <h2 class="mb-0"><?php echo count($bookings); ?></h2>
                                <p class="text-muted">Lượt đặt</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Hóa đơn</h5>
                                <h2 class="mb-0"><?php echo count($bills); ?></h2>
                                <p class="text-muted">Hóa đơn</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Tin nhắn</h5>
                                <h2 class="mb-0"><?php echo count($messages); ?></h2>
                                <p class="text-muted"><?php echo $unread_count; ?> chưa đọc</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php elseif ($page == 'bookings'): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Lịch sử đặt phòng & Tour</h4>
                        
                        <?php if (empty($bookings)): ?>
                            <div class="alert alert-info">
                                Bạn chưa có lịch sử đặt phòng hoặc tour nào.
                                <a href="rooms.php" class="alert-link">Đặt phòng ngay</a> hoặc
                                <a href="tours.php" class="alert-link">khám phá tour du lịch</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Loại</th>
                                            <th>Tên</th>
                                            <th>Ngày nhận/bắt đầu</th>
                                            <th>Ngày trả/kết thúc</th>
                                            <th>Tổng tiền</th>
                                            <th>Thanh toán</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bookings as $booking): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($booking['room_id']): ?>
                                                        <span class="badge bg-primary">Phòng</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Tour</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($booking['room_id']) {
                                                        echo htmlspecialchars($booking['room_name'] ?: 'Không xác định');
                                                        if ($booking['combo_name']) {
                                                            echo ' <span class="badge bg-info">Combo: ' . htmlspecialchars($booking['combo_name']) . '</span>';
                                                        }
                                                    } else {
                                                        echo htmlspecialchars($booking['tour_name'] ?: 'Không xác định');
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($booking['checkin'])); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($booking['checkout'])); ?></td>
                                                <td><?php echo number_format($booking['total_price'], 0, ',', '.') . ' đ'; ?></td>
                                                <td>
                                                    <?php if ($booking['payment_status'] == 'đã thanh toán'): ?>
                                                        <span class="badge bg-success">Đã thanh toán</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    switch ($booking['status']) {
                                                        case 'chờ xác nhận':
                                                            echo '<span class="badge bg-warning text-dark">Chờ xác nhận</span>';
                                                            break;
                                                        case 'đã xác nhận':
                                                            echo '<span class="badge bg-success">Đã xác nhận</span>';
                                                            break;
                                                        case 'đã hủy':
                                                            echo '<span class="badge bg-danger">Đã hủy</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="badge bg-secondary">Không xác định</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="booking_detail.php?id=<?php echo $booking['id']; ?>&type=<?php echo $booking['room_id'] ? 'room' : 'tour'; ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                                    
                                                    <?php if ($booking['status'] == 'chờ xác nhận'): ?>
                                                        <a href="cancel_booking.php?id=<?php echo $booking['id']; ?>&type=<?php echo $booking['room_id'] ? 'room' : 'tour'; ?>" 
                                                           class="btn btn-sm btn-outline-danger" 
                                                           onclick="return confirm('Bạn có chắc chắn muốn hủy đặt phòng/tour này?');">Hủy</a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($booking['payment_status'] == 'chưa thanh toán'): ?>
                                                        <a href="payment.php?id=<?php echo $booking['id']; ?>&type=<?php echo $booking['room_id'] ? 'room' : 'tour'; ?>" 
                                                           class="btn btn-sm btn-success">Thanh toán</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php elseif ($page == 'bills'): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Hóa đơn</h4>
                        
                        <?php if (empty($bills)): ?>
                            <div class="alert alert-info">
                                Bạn chưa có hóa đơn nào.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Mã hóa đơn</th>
                                            <th>Ngày tạo</th>
                                            <th>Loại dịch vụ</th>
                                            <th>Tổng tiền</th>
                                            <th>Giảm giá</th>
                                            <th>Thành tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($bills as $bill): ?>
                                            <tr>
                                                <td>#<?php echo $bill['id']; ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($bill['created_at'])); ?></td>
                                                <td>
                                                    <?php
                                                    if ($bill['room_name']) {
                                                        echo 'Phòng: ' . htmlspecialchars($bill['room_name']);
                                                        if ($bill['combo_name']) {
                                                            echo ' (Combo: ' . htmlspecialchars($bill['combo_name']) . ')';
                                                        }
                                                    } else {
                                                        echo 'Tour du lịch';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo number_format($bill['total_amount'], 0, ',', '.') . ' đ'; ?></td>
                                                <td><?php echo number_format($bill['discount'], 0, ',', '.') . ' đ'; ?></td>
                                                <td><strong><?php echo number_format($bill['final_amount'], 0, ',', '.') . ' đ'; ?></strong></td>
                                                <td>
                                                    <?php if ($bill['payment_status'] == 'đã thanh toán'): ?>
                                                        <span class="badge bg-success">Đã thanh toán</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="bill_detail.php?id=<?php echo $bill['id']; ?>" class="btn btn-sm btn-outline-primary">Xem</a>
                                                    <a href="download_bill.php?id=<?php echo $bill['id']; ?>" class="btn btn-sm btn-outline-secondary">Tải PDF</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php elseif ($page == 'inbox'): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tin nhắn</h4>
                        
                        <?php if (empty($messages)): ?>
                            <div class="alert alert-info">
                                Bạn chưa có tin nhắn nào.
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($messages as $message): ?>
                                    <?php 
                                    // Đánh dấu tin nhắn đã đọc khi người dùng xem
                                    if (!$message['is_read'] && isset($_GET['message_id']) && $_GET['message_id'] == $message['id']) {
                                        $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
                                        $stmt->execute([$message['id']]);
                                        $message['is_read'] = 1;
                                    }
                                    
                                    $message_class = $message['is_read'] ? '' : 'message-unread';
                                    $active_class = (isset($_GET['message_id']) && $_GET['message_id'] == $message['id']) ? 'active' : '';
                                    ?>
                                    
                                    <a href="profile.php?page=inbox&message_id=<?php echo $message['id']; ?>" 
                                       class="list-group-item list-group-item-action <?php echo $active_class . ' ' . $message_class; ?>">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h5 class="mb-1"><?php echo htmlspecialchars($message['subject']); ?></h5>
                                            <small class="message-date"><?php echo date('d/m/Y H:i', strtotime($message['sent_at'])); ?></small>
                                        </div>
                                        <p class="mb-1"><?php echo htmlspecialchars(mb_strimwidth($message['content'], 0, 80, '...')); ?></p>
                                        <?php if (!$message['is_read']): ?>
                                            <span class="unread-badge">Mới</span>
                                        <?php endif; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php 
                            // Hiển thị nội dung tin nhắn nếu có message_id
                            if (isset($_GET['message_id'])):
                                $msg_id = (int)$_GET['message_id'];
                                foreach ($messages as $msg) {
                                    if ($msg['id'] == $msg_id) {
                                        ?>
                                        <div class="card mt-4">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($msg['subject']); ?></h5>
                                                <p class="card-text"><?php echo nl2br(htmlspecialchars($msg['content'])); ?></p>
                                                <p class="text-end text-muted"><small>Gửi lúc: <?php echo date('d/m/Y H:i', strtotime($msg['sent_at'])); ?></small></p>
                                            </div>
                                        </div>
                                        <?php
                                        break;
                                    }
                                }
                            endif;
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php elseif ($page == 'settings'): ?>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Cài đặt tài khoản</h4>
                        <form action="profile.php?page=settings" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">Họ</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Tên</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="cccd" class="form-label">CCCD</label>
                                    <input type="text" class="form-control" id="cccd" name="cccd" value="<?php echo htmlspecialchars($user['cccd']); ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="profile_pic" class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*">
                            </div>
                            <hr>
                            <h5 class="mt-3">Đổi mật khẩu</h5>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="<?php echo $BASE_PATH; ?>js/bootstrap.bundle.min.js"></script>
</body>
</html>