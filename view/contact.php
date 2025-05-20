<?php
session_start();
$BASE_PATH = '/CUULONGRESORT';

// Thiết lập các biến cho SEO
$page_title = "Cửu Long Resort - Trang Chủ";
$page_description = "Khám phá Cửu Long Resort, điểm đến nghỉ dưỡng lý tưởng tại Vĩnh Long với không gian thiên nhiên, phòng nghỉ sang trọng và dịch vụ chuyên nghiệp.";
$page_keywords = "Cửu Long Resort, nghỉ dưỡng Vĩnh Long, khách sạn miền Tây, đặt phòng resort, du lịch miền Tây";
$canonical_url = "http://www.cuulongresort.com/lien-he";

// Kết nối CSDL
try {
    $conn = new PDO("mysql:host=localhost;port=3306;dbname=cuulong_resort;charset=utf8mb4", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && !empty($_POST['full_name'])
    && !empty($_POST['email'])
    && !empty($_POST['phone'])
    && !empty($_POST['subject'])
    && !empty($_POST['message'])
) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

    // Sử dụng đúng tên cột trong bảng messages
    $sql = "INSERT INTO messages (user_id, subject, content, sent_at)
            VALUES (:user_id, :subject, :content, NOW())";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':user_id' => $user_id,
        ':subject' => $subject,
        ':content' => $message
    ]);

    if ($result) {
        $_SESSION['contact_success'] = "Tin nhắn của bạn đã được gửi thành công!";
        header("Location: ../layout/profile.php");
        exit;
    } else {
        $_SESSION['contact_error'] = "Có lỗi xảy ra, vui lòng thử lại sau.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="trang-gioi-thieu-cua-Cuu-Long-Resort">
    <meta name="author" content="TonDucThangUniversity">
    <title>Liên Hệ</title>
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
    <!-- Canonical URL for SEO -->
    <link rel="canonical" href="https://cuulongresort.com/contact">

    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ContactPage",
        "name": "Liên Hệ Cửu Long Resort",
        "description": "Trang liên hệ của Cửu Long Resort, cung cấp thông tin liên lạc, form gửi tin nhắn, đăng ký bản tin và câu hỏi thường gặp.",
        "url": "https://cuulongresort.com/contact",
        "publisher": {
            "@type": "Organization",
            "name": "Cửu Long Resort",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "10/10 Nguyễn Văn Thiệt, Phường 3",
                "addressLocality": "Vĩnh Long",
                "addressCountry": "VN"
            },
            "telephone": "+84987654321",
            "email": "CuuLong@gmail.com"
        }
    }
    </script>
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
                        <li class="nav-item"><a class="nav-link" href="../index.php">Trang chủ</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">Giới thiệu</a></li>
                        <li class="nav-item"><a class="nav-link" href="service.php">Dịch vụ</a></li>
                        <li class="nav-item"><a class="nav-link" href="tour.php">Tour du lịch</a></li>
                        <li class="nav-item"><a class="nav-link" href="room.php">Đặt phòng</a></li>
                        <li class="nav-item"><a class="nav-link active" href="contact.php">Liên hệ</a></li>
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
        <!-- Contact Form and Newsletter Section -->
        <section class="container my-5">
            <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); padding: 10px 30px 0 30px; margin: 150px 0px 0px 0px;" data-aos="fade-bottom">
                <section class="contact-newsletter-section section-padding" style="color: var(--p-color);">
                    <?php if (isset($_SESSION['contact_error'])): ?>
                        <div class="alert alert-danger mt-3"><?php echo $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?></div>
                    <?php endif; ?>
                    <div class="row">
                        <!-- Contact Form -->
                        <div class="col-md-6 contact-form">
                            <h1 style="font-size: 2rem; font-weight: 1000; color: var(--primary-color);">Liên Hệ Với Chúng Tôi</h1>
                            <form action="contact.php" method="POST" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <input type="text" name="full_name" class="form-control" placeholder="Họ và Tên" required>
                                    <div class="invalid-feedback">Vui lòng nhập họ và tên.</div>
                                </div>
                                <div class="mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    <div class="invalid-feedback">Vui lòng nhập email hợp lệ.</div>
                                </div>
                                <div class="mb-3">
                                    <input type="tel" name="phone" class="form-control" placeholder="Số Điện Thoại" pattern="[0-9]{10}" required>
                                    <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ (10 số).</div>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="subject" class="form-control" placeholder="Tiêu Đề" required>
                                    <div class="invalid-feedback">Vui lòng nhập tiêu đề.</div>
                                </div>
                                <div class="mb-3">
                                    <textarea name="message" class="form-control" placeholder="Nội Dung Tin Nhắn" rows="5" required></textarea>
                                    <div class="invalid-feedback">Vui lòng nhập nội dung.</div>
                                </div>
                                <div class="social-icons mt-3 mb-4">
                                    <span>Kết Nối Với Chúng Tôi:</span><br>
                                    <a class="custom-link" href="https://facebook.com" target="_blank" class="me-2 text-decoration-none"><i class="fab fa-facebook-f"></i> Facebook</a>
                                    <a class="custom-link" href="https://instagram.com" target="_blank" class="me-2 text-decoration-none"><i class="fab fa-instagram"></i> Instagram</a>
                                    <a class="custom-link" href="https://twitter.com" target="_blank" class="text-decoration-none"><i class="fab fa-twitter"></i> Twitter</a>
                                </div>
                                <button type="submit" class="custom-button">Gửi Tin Nhắn</button>
                            </form>
                        </div>
                        <!-- Newsletter Form -->
                        <div class="col-md-6 newsletter">
                            <h2 style="font-size: 2rem; font-weight: 1000; color: var(--primary-color);">Đăng Ký Nhận Bản Tin</h2>
                            <p>Nhận thông tin mới nhất về các ưu đãi, sự kiện và trải nghiệm độc đáo tại Cửu Long Resort.</p>
                            <form action="subscribe_newsletter.php" method="POST">
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" placeholder="Nhập email của bạn" required>
                                    <button class="custom-button" type="submit">Đăng Ký Ngay</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <!-- FAQs Section -->
        <section class="container my-5">
            <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); padding: 10px 30px 0 30px; margin: 30px 0px 0px 0px;" data-aos="fade-bottom">
                <section class="faq-section section-padding">
                    <h1 class="text-center mb-4" style="font-size: 2rem; font-weight: 1000; color: var(--primary-color);">Câu Hỏi Thường Gặp</h1>
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ 1 -->
                        <div class="accordion-item">
                            <h4 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Làm thế nào để đặt phòng tại Cửu Long Resort?
                                </button>
                            </h4>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Bạn có thể đặt phòng trực tiếp trên website tại mục <a href="room.php" class="custom-link">"Phòng"</a>, chọn loại phòng và điền thông tin. Ngoài ra, hãy liên hệ qua số <a href="tel:0987654321" class="custom-link">0987 654 321</a> để được hỗ trợ nhanh chóng.
                                </div>
                            </div>
                        </div>
                        <!-- FAQ 2 -->
                        <div class="accordion-item">
                            <h4 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Chính sách hủy đặt phòng của resort là gì?
                                </button>
                            </h4>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Bạn có thể hủy miễn phí trước 48 giờ so với ngày nhận phòng. Sau thời gian này, phí hủy sẽ được áp dụng tùy theo loại phòng đã đặt.
                                </div>
                            </div>
                        </div>
                        <!-- FAQ 3 -->
                        <div class="accordion-item">
                            <h4 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Resort có cung cấp tour du lịch không?
                                </button>
                            </h4>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Có, chúng tôi cung cấp các tour khám phá Vĩnh Long và khu vực lân cận. Xem chi tiết tại <a href="tour.php" class="custom-link">Tour Du Lịch</a> hoặc email <a href="mailto:CuuLong@gmail.com" class="custom-link">CuuLong@gmail.com</a>.
                                </div>
                            </div>
                        </div>
                        <!-- FAQ 4 -->
                        <div class="accordion-item">
                            <h4 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Có cần đặt trước khi sử dụng dịch vụ spa không?
                                </button>
                            </h4>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Chúng tôi khuyến khích đặt trước để đảm bảo khung giờ phù hợp. Bạn có thể đặt qua <a href="service.php" class="custom-link">Dịch Vụ</a> hoặc gọi <a href="tel:0987654321" class="custom-link">0987 654 321</a>.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <!-- Contact Info and Map Section -->
        <section class="container my-5">
            <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 6px 20px rgba(0,0,0,0.1); padding: 10px 30px 0 30px; margin: 30px 0px 0px 0px;" data-aos="fade-bottom">
                <section class="contact-info-section section-padding" style="color: var(--p-color);">
                    <div class="row">
                        <!-- Contact Info -->
                        <div class="col-md-6 contact-details">
                            <h2 style="font-size: 2rem; font-weight: 1000; color: var(--primary-color);">Thông Tin Liên Hệ</h2>
                            <p><strong>Số Điện Thoại:</strong> <a href="tel:0987654321" class="custom-link">0987 654 321</a></p>
                            <p><strong>Địa Chỉ:</strong> 10/10 Nguyễn Văn Thiệt, Phường 3, Vĩnh Long, Việt Nam</p>
                            <p><strong>Email:</strong> <a href="mailto:CuuLong@gmail.com" class="custom-link">CuuLong@gmail.com</a></p>
                            <h3 class="mt-4" style="font-size: 1.5rem; font-weight: 1000; color: var(--primary-color);">Thông Tin Đặt Phòng</h3>
                            <p class="text-muted">Vui lòng <a href="login.php" class="custom-link">đăng nhập</a> để xem thông tin đặt phòng.</p>
                        </div>
                        <!-- Google Map -->
                        <div class="col-md-6 map">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3928.841518408584!2d105.95761497514777!3d10.246672089868888!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0885a1a1e9b73%3A0x40d7e58b6b1e9b73!2sV%C4%A9nh%20Long%2C%20Vietnam!5e0!3m2!1sen!2s!4v1697654321897!5m2!1sen!2s"
                                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" title="Bản đồ Cửu Long Resort tại Vĩnh Long"></iframe>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <!-- Footer -->
        <footer class="site-footer">
            <div class="container" data-aos="fade-bottom">
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

        // Form validation
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>