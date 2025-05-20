<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Lấy trang hiện tại từ URL
$current_page = basename($_SERVER['PHP_SELF']);

function displayAuthButton() {
    // Xử lý đăng xuất
    if (isset($_GET['logout'])) {
        session_unset();
        session_destroy();
        // Sử dụng đường dẫn tuyệt đối để trỏ về index.php trong thư mục gốc
        header("Location: /CuuLongResort/index.php");
        exit();
    }

    $output = '<div class="dropdown ms-3">';
    if (isset($_SESSION['user']) && is_array($_SESSION['user']) && !empty($_SESSION['user']['name'])) {
        $username = htmlspecialchars($_SESSION['user']['name']); // Hiển thị tên người dùng
        $output .= '<a href="#" class="btn-login dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">';
        $output .= '<i class="fas fa-user-circle me-2"></i>' . $username;
        $output .= '</a>';
        $output .= '<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="userDropdown">';
        $output .= '<li><a class="dropdown-item" href="profile.php?page=personal"><i class="fas fa-user me-2"></i>Hồ sơ</a></li>';
        $output .= '<li><a class="dropdown-item" href="profile.php?page=bookings"><i class="fas fa-ticket-alt me-2"></i>Đặt phòng</a></li>';
        $output .= '<li><a class="dropdown-item" href="profile.php?page=inbox"><i class="fas fa-bell me-2"></i>Tin nhắn <span class="badge bg-danger rounded-pill ms-2">3</span></a></li>';
        $output .= '<li><a class="dropdown-item" href="profile.php?page=settings"><i class="fas fa-cog me-2"></i>Cài đặt</a></li>';
        $output .= '<li><hr class="dropdown-divider"></li>';
        $output .= '<li><a class="dropdown-item" href="?logout=true"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>';
        $output .= '</ul>';
    } else {
        $output .= '<a href="../layout/login.php" class="btn btn-primary btn-login ms-3">Đăng nhập</a>';
    }
    $output .= '</div>';

    return $output;
}
// Định nghĩa đường dẫn cơ sở
$BASE_PATH = '/CUULONGRESORT';
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/vegas.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/style.css" rel="stylesheet">
</head>
<body>
<body style="background-color: #41554d">
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
                    <li class="nav-item"><a class="nav-link active" href="<?php echo $BASE_PATH; ?>/index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/about.php">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/service.php">Dịch vụ</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/tour.php">Tour du lịch</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/room.php">Đặt phòng</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $BASE_PATH; ?>/view/contact.php">Liên hệ</a></li>
                </ul>
            </div>
        </div>
    </nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>
