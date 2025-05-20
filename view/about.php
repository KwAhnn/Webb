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
$canonical_url = "http://www.cuulongresort.com/gioi-thieu";
?>
<!doctype html>
<html lang="en">
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
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/vegas.min.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/style.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/about.css" rel="stylesheet">
    <link href="<?php echo $BASE_PATH; ?>/layout/css/service.css" rel="stylesheet">
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

        <section class="intro-service-section" style="margin: 150px 60px 30px 60px; padding: 50px; gap: 30px; text-align: center; background-color: #41554d;">
            <div class="container text-center">
                <h2 class="text-white mb-3">Chào mừng đến với Cửu Long Resort !</h2>
                <p class="text-white">
                    Cửu Long Resort được sáng lập bởi nhóm doanh nhân giàu kinh nghiệm trong lĩnh vực du lịch và dịch vụ, với tầm nhìn xây dựng khu nghỉ dưỡng cao cấp kết hợp nét đẹp thiên nhiên và văn hóa miền Tây Nam Bộ, góp phần phát triển du lịch bền vững tại Vĩnh Long.
                </p>
            </div>
        </section>

        <section class="founders-section">
            <div class="founder-card founder-1">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8a6W_1jQirJZNw5keWp_iRVTP6rrqEEOAnqi_Xh7Pjff9ZFJB5gp0IKas5ytqLpo7Zio&usqp=CAU" alt="Lê Thị Mỹ Phụng" style="background-color: #ffcc7f;">
                <h3>Lê Thị Mỹ Phụng</h3>
                <p>Bà Lê Thị Mỹ Phụng là người sáng lập và cũng là linh hồn của Cửu Long Resort với hơn 20 năm kinh nghiệm trong lĩnh vực quản lý du lịch và khách sạn. Bà đã dành nhiều năm xây dựng và phát triển các hệ thống quản lý vận hành chuyên nghiệp, tạo nên một môi trường làm việc hiệu quả và thân thiện.</p>
                <p>Dưới sự dẫn dắt của bà, resort đã từng bước khẳng định vị thế hàng đầu trong ngành nghỉ dưỡng cao cấp, với những tiêu chuẩn dịch vụ vượt trội và trải nghiệm khách hàng đẳng cấp. Bà luôn đặt sự hài lòng và trải nghiệm của khách hàng lên hàng đầu, đồng thời không ngừng đổi mới và nâng cao chất lượng dịch vụ thông qua việc áp dụng các công nghệ hiện đại và phương pháp quản lý tiên tiến.</p>
            </div>

            <div class="founder-card founder-2">
                <img src="data:image/jpeg;base64,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" alt="Đặng Kim Anh" style="background-color: #ae7956;">
                <h3>Đặng Kim Anh</h3>
                <p>Bà Đặng Kim Anh đóng vai trò chiến lược trong việc xây dựng và phát triển thương hiệu Cửu Long Resort. Với hơn 15 năm kinh nghiệm trong lĩnh vực marketing và quản lý trải nghiệm khách hàng, bà đã xây dựng thành công các chiến dịch quảng bá mang tính đột phá.</p>
                <p>Những chiến dịch này giúp nâng cao nhận diện thương hiệu trên thị trường trong nước và quốc tế. Bà luôn chú trọng vào việc tạo ra giá trị lâu dài cho khách hàng thông qua các dịch vụ cá nhân hóa và chăm sóc tận tâm, đồng thời xây dựng văn hóa phục vụ chuyên nghiệp trong toàn hệ thống.</p>
                <p>Những sáng kiến của bà đã góp phần quan trọng giúp resort giữ vững vị thế cạnh tranh trong ngành du lịch ngày càng khốc liệt.</p>
            </div>

            <div class="founder-card founder-3">
            <div class="founder-card founder-3">
                <img src="data:image/jpeg;base64,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" alt="Nguyễn Lại Vũ Phong" style="background-color: #ffc8a8eb;">
                <h3>Nguyễn Lại Vũ Phong</h3>
                <p>Ông Nguyễn Lại Vũ Phong là kiến trúc sư trưởng chịu trách nhiệm thiết kế và phát triển không gian xanh tại Cửu Long Resort, với hơn 18 năm kinh nghiệm trong lĩnh vực kiến trúc nghỉ dưỡng. Ông đã tạo nên những thiết kế hài hòa giữa thiên nhiên và kiến trúc hiện đại.</p>
                <p>Những thiết kế này giúp du khách tận hưởng sự thư giãn tối đa trong một không gian cân bằng và tràn đầy sức sống. Ông không ngừng nghiên cứu và áp dụng các giải pháp bền vững nhằm bảo vệ môi trường và nâng cao trải nghiệm nghỉ dưỡng.</p>
                <p>Đóng góp của ông đã giúp resort không chỉ nổi bật về mặt thẩm mỹ mà còn trở thành biểu tượng của sự kết hợp giữa truyền thống và hiện đại trong ngành du lịch miền Tây.</p>
            </div>
        </section>

        <!-- Tin tức -->
        <section class="intro-service-section" style="background-color: #41554d">
            <div class="container text-center">
                <h2 class="text-white mb-3">Danh mục các bài báo về Cửu Long Resort</h2>
                <p class="text-white">
                    Chào mừng quý khách đến với kho bài viết đặc biệt về Cửu Long Resort — nơi mang đến trải nghiệm nghỉ dưỡng đẳng cấp và sự thư giãn tuyệt vời giữa thiên nhiên miền Tây. Tại đây, quý khách sẽ tìm thấy những chia sẻ chân thật về dịch vụ, phòng nghỉ sang trọng, ẩm thực phong phú cùng nhiều hoạt động thú vị khác. Hãy cùng khám phá và cảm nhận vì sao Cửu Long Resort luôn là điểm đến yêu thích của nhiều du khách trong và ngoài nước!
                </p>
            </div>
        </section>

        <section class="news-wrapper">
            <div class="news-section">
                <div class="news-row">
                    <div class="news-card" onclick="openNews('news1')">
                        <img src="<?php echo $BASE_PATH; ?>./layout/images/hero1.jpg" alt="News 1">
                        <div class="news-info">
                            <h2>30 <small>Tháng 4, 2025</small></h2>
                            <h3 style="text-align: justify;">Cửu Long Resort – Đỉnh Cao Chất Lượng Dịch Vụ Nghỉ Dưỡng Tại Vĩnh Long</h3>
                            <p>Tọa lạc tại vùng đất trù phú của Vĩnh Long, nơi giao thoa hài hòa giữa thiên nhiên sông nước miền Tây và văn hóa đặc sắc bản địa...</p>
                        </div>
                    </div>
                    <div class="news-card" onclick="openNews('news2')">
                        <img src="<?php echo $BASE_PATH; ?>./layout/images/hero2.jpg" alt="News 2">
                        <div class="news-info">
                            <h2>21 <small>Tháng 3, 2025</small></h2>
                            <h3>Phòng Nghỉ Cửu Long Resort – Không Gian Sang Trọng, Tiện Nghi Hiện Đại</h3>
                            <p>Trong ngành du lịch nghỉ dưỡng cao cấp, chất lượng phòng nghỉ đóng vai trò then chốt quyết định sự hài lòng và trải nghiệm của khách hàng....</p>
                        </div>
                    </div>
                    <div class="news-card" onclick="openNews('news3')">
                        <img src="<?php echo $BASE_PATH; ?>./layout/images/service/cook.jpg" alt="News 3">
                        <div class="news-info">
                            <h2>15 <small>Tháng 02, 2025</small></h2>
                            <h3>Ẩm Thực Tại Cửu Long Resort – Tinh Hoa Miền Tây Giao Thoa Phong Vị Quốc Tế</h3>
                            <p>Ẩm thực không chỉ đơn thuần là dịch vụ đi kèm, mà tại Cửu Long Resort, đó là một hành trình trải nghiệm văn hóa...</p>
                        </div>
                    </div>
                </div>

                <div class="news-row">
                    <div class="news-card" onclick="openNews('news4')">
                        <img src="<?php echo $BASE_PATH; ?>./layout/images/service/cheo.jpg" alt="News 4">
                        <div class="news-info">
                            <h2>10 <small>Tháng 01, 2025</small></h2>
                            <h3>Tour Trải Nghiệm Tại Cửu Long Resort – Khám Phá Trọn Vẹn Miền Tây Trong Một Chạm</h3>
                            <p>Bên cạnh chất lượng dịch vụ nghỉ dưỡng 5 sao, Cửu Long Resort còn nổi bật với các chương trình tour trải nghiệm...</p>
                        </div>
                    </div>
                    <div class="news-card" onclick="openNews('news5')">
                        <img src="<?php echo $BASE_PATH; ?>./layout/images/service/gym2.jpg" alt="News 5">
                        <div class="news-info">
                            <h2>20 <small>Tháng 12, 2024</small></h2>
                            <h3>Cửu Long Resort Đột Phá Với Ứng Dụng Công Nghệ Trong Nâng Cao Chất Lượng Dịch Vụ</h3>
                            <p>Trong xu thế cách mạng công nghiệp 4.0, Cửu Long Resort không chỉ dừng lại ở việc cung cấp dịch vụ nghỉ dưỡng....</p>
                        </div>
                    </div>
                    <div class="news-card" onclick="openNews('news6')">
                        <img src="<?php echo $BASE_PATH; ?>./layout/images/room/suite3.jpg" alt="News 6">
                        <div class="news-info">
                            <h2>05 <small>Tháng 11, 2024</small></h2>
                            <h3>Chất Lượng Phòng Nghỉ Tại Cửu Long Resort – Tiện Nghi Đẳng Cấp 5 Sao</h3>
                            <p>Cửu Long Resort luôn chú trọng đầu tư mạnh mẽ vào chất lượng phòng nghỉ để mang lại sự thoải mái và tiện nghi...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chi tiết tin tức -->
        <div id="news1" class="news-box">
            <button class="close-btn" onclick="closeNews('news1')">Đóng</button>
            <img src="<?php echo $BASE_PATH; ?>./layout/images/hero1.jpg" alt="News 1" style="width: 550px; height: 300px; padding: 40px 20px 20px 20px; display: block; margin: 0 auto;">
            <h2>30 <small>Tháng 4, 2025</small></h2>
            <h3>Cửu Long Resort – Đỉnh Cao Chất Lượng Dịch Vụ Nghỉ Dưỡng</h3>
            <p>Tọa lạc tại vùng đất trù phú của Vĩnh Long, nơi giao thoa hài hòa giữa thiên nhiên sông nước miền Tây và văn hóa đặc sắc bản địa, Cửu Long Resort từ khi thành lập năm 2010 đã không ngừng khẳng định vị thế hàng đầu trong ngành du lịch nghỉ dưỡng cao cấp tại khu vực Đồng bằng sông Cửu Long. Qua hơn một thập kỷ phát triển, resort đã trở thành biểu tượng của sự chuyên nghiệp và chất lượng dịch vụ đẳng cấp 5 sao.</p>
            <p>Theo báo cáo độc lập do Viện Nghiên cứu Du lịch Việt Nam thực hiện trong năm 2023, Cửu Long Resort đạt mức độ hài lòng trung bình của khách hàng lên tới 95,6%, một con số ấn tượng vượt xa mức trung bình của các khu nghỉ dưỡng cùng phân khúc. Kết quả này phản ánh sự chăm chút tỉ mỉ trong từng khâu phục vụ từ lễ tân, quản lý phòng, dịch vụ ăn uống đến các hoạt động giải trí. Đặc biệt, đội ngũ nhân viên hơn 200 người của resort đều được tuyển chọn kỹ lưỡng, đào tạo bài bản theo các tiêu chuẩn quốc tế, không chỉ thành thạo nghiệp vụ mà còn có thái độ tận tâm, nhiệt huyết với khách hàng.</p>
            <p>Năm 2023 đánh dấu bước ngoặt lớn khi Cửu Long Resort chính thức được Bộ Văn hóa, Thể thao và Du lịch cấp chứng nhận “Khu nghỉ dưỡng 5 sao tiêu chuẩn Quốc gia”. Đây là sự công nhận xứng đáng cho những nỗ lực không ngừng trong việc nâng cao chất lượng dịch vụ và đầu tư cơ sở vật chất hiện đại. Bộ trưởng Bộ Văn hóa, Thể thao và Du lịch trong buổi lễ trao giải đã nhấn mạnh: “Cửu Long Resort là tấm gương điển hình về sự phát triển bền vững trong lĩnh vực du lịch, nơi không chỉ mang lại trải nghiệm đẳng cấp mà còn góp phần thúc đẩy phát triển kinh tế địa phương.”</p>
            <p>Không dừng lại ở đó, resort còn chú trọng xây dựng văn hóa phục vụ khách hàng dựa trên giá trị cốt lõi “Tận tâm – Chuyên nghiệp – Sáng tạo”. Khách hàng khi đến Cửu Long Resort không chỉ nhận được dịch vụ tiêu chuẩn quốc tế mà còn cảm nhận được sự ấm áp, thân thiện và gần gũi như ở chính ngôi nhà của mình. Việc áp dụng công nghệ hiện đại như hệ thống quản lý khách sạn (PMS), đặt phòng trực tuyến thông minh và hỗ trợ đa ngôn ngữ cũng giúp cải thiện trải nghiệm và sự tiện lợi cho du khách quốc tế.</p>
            <p>Ngoài ra, theo khảo sát của Sở Du lịch Vĩnh Long, 87% khách hàng quay trở lại Cửu Long Resort ít nhất một lần trong vòng 2 năm, chứng minh độ tin cậy và sự yêu thích của khách hàng dành cho dịch vụ nơi đây. Những phản hồi tích cực về thái độ phục vụ, sự nhanh chóng và hiệu quả trong xử lý yêu cầu được xem là điểm cộng lớn giúp resort giữ vững thương hiệu trong thị trường cạnh tranh khốc liệt hiện nay.</p>
            <p>Tổng giám đốc Cửu Long Resort chia sẻ: “Chúng tôi luôn đặt khách hàng làm trung tâm, lấy sự hài lòng của họ làm thước đo thành công. Việc được công nhận 5 sao không chỉ là vinh dự mà còn là trách nhiệm lớn lao để chúng tôi tiếp tục đổi mới và phát triển dịch vụ ngày một hoàn thiện hơn.”</p>
            <p>Với nền tảng vững chắc về chất lượng dịch vụ, Cửu Long Resort không chỉ góp phần nâng tầm du lịch Vĩnh Long mà còn là niềm tự hào của ngành du lịch miền Tây, hứa hẹn là điểm đến hấp dẫn trong hành trình khám phá vẻ đẹp sông nước và văn hóa bản địa của du khách trong nước và quốc tế.</p>
            <p><em>Nguồn: Báo Vĩnh Long</em></p>
        </div>

        <div id="news2" class="news-box">
            <button class="close-btn" onclick="closeNews('news2')">Đóng</button>
            <img src="<?php echo $BASE_PATH; ?>./layout/images/hero2.jpg" alt="News 2" style="width: 550px; height: 300px; padding: 40px 20px 20px 20px; display: block; margin: 0 auto;">
            <h2>25 <small>Tháng 03, 2025</small></h2>
            <h3>Phòng Nghỉ Cửu Long Resort – Không Gian Sang Trọng, Tiện Nghi Hiện Đại</h3>
            <p>Trong ngành du lịch nghỉ dưỡng cao cấp, chất lượng phòng nghỉ đóng vai trò then chốt quyết định sự hài lòng và trải nghiệm của khách hàng. Cửu Long Resort, với hơn 150 phòng nghỉ được thiết kế theo tiêu chuẩn 5 sao quốc tế, đã đầu tư bài bản và không ngừng nâng cấp để mang đến không gian nghỉ dưỡng đẳng cấp, hòa hợp với thiên nhiên sông nước đặc trưng của Vĩnh Long.</p>
            <p>Hệ thống phòng nghỉ của resort bao gồm đa dạng các loại phòng, từ phòng tiêu chuẩn, phòng deluxe đến các phòng suite cao cấp với diện tích từ 35 đến 80 mét vuông. Tất cả các phòng đều được trang bị các tiện nghi hiện đại như hệ thống điều hòa nhiệt độ tự động, TV màn hình phẳng, minibar, đèn điều khiển thông minh, wifi miễn phí và ban công riêng hướng ra cảnh quan thiên nhiên.</p>
            <p>Theo báo cáo kiểm định chất lượng dịch vụ phòng nghỉ năm 2023 của Tổng cục Du lịch Việt Nam, Cửu Long Resort đạt điểm đánh giá trung bình 9.3/10 về tiêu chí tiện nghi và sự hài lòng của khách hàng. Khảo sát nội bộ với hơn 5.000 khách lưu trú trong năm cho thấy 94% đánh giá phòng nghỉ vượt mong đợi, đặc biệt là độ sạch sẽ và không gian yên tĩnh.</p>
            <p>Một điểm nổi bật là kiến trúc phòng nghỉ tại resort được kết hợp hài hòa giữa thiết kế hiện đại và thiên nhiên miền Tây. Khu nghỉ dưỡng bao bọc bởi vườn cây xanh, hồ nước và những con kênh nhỏ, tạo nên cảm giác thư giãn tuyệt đối. Ngoài ra, các vật liệu xây dựng được chọn lọc kỹ lưỡng nhằm đảm bảo cách âm tốt, thân thiện với môi trường và giữ được nét gần gũi bản địa.</p>
            <p>Resort còn được Hội đồng Kiểm định Xanh – Sáng tạo trao chứng nhận “Khu nghỉ dưỡng xanh tiêu chuẩn quốc tế” năm 2022 nhờ việc sử dụng năng lượng tiết kiệm và quy trình quản lý thân thiện với môi trường. Đây là minh chứng cho định hướng phát triển bền vững, văn minh mà ban quản lý resort theo đuổi.</p>
            <p>Để đáp ứng nhu cầu khách hàng cao cấp, dịch vụ phục vụ phòng 24/7, giặt ủi nhanh, đặt tour, vận chuyển và hỗ trợ khách hàng quốc tế đều được triển khai đồng bộ. Các nhân viên buồng phòng và kỹ thuật được đào tạo chuyên nghiệp, đảm bảo mỗi chi tiết trong phòng luôn chỉn chu trước khi khách bước vào.</p>
            <p>Giám đốc Cửu Long Resort chia sẻ: “Chúng tôi xác định chất lượng phòng nghỉ là trái tim của trải nghiệm nghỉ dưỡng. Mỗi không gian phòng đều được chúng tôi chăm chút như một tác phẩm nghệ thuật, để du khách luôn cảm thấy an toàn, tiện nghi và thực sự thư giãn.”</p>
            <p>Với hệ thống phòng nghỉ đạt chuẩn quốc tế, phong cách phục vụ chuyên nghiệp và sự kết nối độc đáo với thiên nhiên miền Tây, Cửu Long Resort xứng đáng là lựa chọn hàng đầu cho du khách tìm kiếm một kỳ nghỉ vừa sang trọng vừa đậm đà bản sắc địa phương.</p>
            <p><em>Nguồn: Báo Vĩnh Long</em></p>
        </div>

        <div id="news3" class="news-box">
            <button class="close-btn" onclick="closeNews('news3')">Đóng</button>
            <img src="<?php echo $BASE_PATH; ?>./layout/images/service/cook.jpg" alt="News 3" style="width: 550px; height: 300px; padding: 40px 20px 20px 20px; display: block; margin: 0 auto;">
            <h2>15 <small>Tháng 02, 2025</small></h2>
            <h3>Ẩm Thực Tại Cửu Long Resort – Tinh Hoa Miền Tây Giao Thoa Phong Vị Quốc Tế</h3>
            <p>Ẩm thực không chỉ đơn thuần là dịch vụ đi kèm, mà tại Cửu Long Resort, đó là một hành trình trải nghiệm văn hóa và cảm xúc. Với hệ thống nhà hàng cao cấp đạt chuẩn 5 sao, resort tự hào mang đến cho du khách những món ăn tinh túy nhất từ vùng đất chín rồng kết hợp cùng kỹ thuật chế biến hiện đại mang đậm tính quốc tế.</p>
            <p>Nhà hàng “Cửu Long Heritage” – trái tim ẩm thực của khu nghỉ dưỡng – được thiết kế với không gian mở, hướng nhìn ra sông và khu vườn nhiệt đới. Thực đơn tại đây là sự pha trộn khéo léo giữa các món ăn truyền thống miền Tây như cá lóc nướng trui, lẩu mắm, bánh xèo, cho đến các món Âu – Á đa dạng được thực hiện bởi đội ngũ đầu bếp từng làm việc tại các chuỗi khách sạn quốc tế.</p>
            <p>Theo thống kê từ Sở Du lịch Vĩnh Long năm 2024, hơn 90% khách hàng đánh giá nhà hàng của Cửu Long Resort ở mức “xuất sắc”, đặc biệt trong các tiêu chí về hương vị, chất lượng nguyên liệu và sự đa dạng trong thực đơn. Ngoài ra, ẩm thực tại đây còn được Viện Văn hóa Ẩm thực Việt Nam ghi nhận là “Không gian ẩm thực vùng sông nước tiêu biểu năm 2023”.</p>
            <p>Các món ăn được chế biến từ nguyên liệu tươi sống, lấy trực tiếp từ chợ nổi Vĩnh Long mỗi sáng, kết hợp cùng kỹ năng điêu luyện của các đầu bếp giúp giữ trọn hương vị nguyên bản. Nhà hàng còn phục vụ các suất ăn chay theo yêu cầu, các thực đơn detox và cả món châu Âu dành cho khách quốc tế lưu trú dài ngày.</p>
            <p>Điểm đặc biệt khiến thực khách ghi nhớ chính là không gian thưởng thức – nơi mà từng bàn ăn được đặt tại những vị trí lý tưởng nhất để tận hưởng hoàng hôn trên sông, tiếng chim chiều trong vườn cây và làn gió mát của miền Tây. Hệ thống đèn vàng ấm áp, âm nhạc dân ca nhẹ nhàng giúp mọi bữa ăn trở thành một kỷ niệm đáng nhớ.</p>
            <p>Định kỳ mỗi tháng, resort tổ chức sự kiện “Đêm Ẩm Thực Miền Tây” với các gian hàng ẩm thực truyền thống, biểu diễn đờn ca tài tử và giao lưu nấu ăn cùng bếp trưởng – một hoạt động thu hút đông đảo du khách quốc tế và các gia đình trong nước.</p>
            <p>Ông Nguyễn Văn Duy – Bếp trưởng điều hành chia sẻ: “Ẩm thực không chỉ là món ăn ngon mà còn là cầu nối văn hóa. Chúng tôi mong muốn mỗi thực khách đến đây không chỉ được ăn no, ăn ngon mà còn hiểu hơn về vùng đất và con người miền Tây thông qua từng món ăn.”</p>
            <p>Với tâm huyết và sự đầu tư nghiêm túc, hệ thống nhà hàng tại Cửu Long Resort không chỉ làm hài lòng vị giác mà còn để lại ấn tượng sâu sắc về một miền Tây trọn vẹn trong từng bữa ăn, xứng đáng là điểm đến ẩm thực hàng đầu trong hành trình khám phá khu vực Đồng bằng sông Cửu Long.</p>
            <p><em>Nguồn: Báo Vĩnh Long</em></p>
        </div>

        <div id="news4" class="news-box">
            <button class="close-btn" onclick="closeNews('news4')">Đóng</button>
            <img src="<?php echo $BASE_PATH; ?>./layout/images/service/cheo.jpg" alt="News 4" style="width: 550px; height: 300px; padding: 40px 20px 20px 20px; display: block; margin: 0 auto;">
            <h2>10 <small>Tháng 01, 2025</small></h2>
            <h3>Tour Trải Nghiệm Tại Cửu Long Resort – Khám Phá Trọn Vẹn Miền Tây Trong Một Chạm</h3>
            <p>Bên cạnh chất lượng dịch vụ nghỉ dưỡng 5 sao, Cửu Long Resort còn nổi bật với các chương trình tour trải nghiệm được thiết kế riêng biệt, đưa du khách đi sâu vào đời sống và văn hóa đặc sắc của vùng đất Cửu Long. Đây chính là một trong những điểm nhấn giúp resort trở thành lựa chọn hàng đầu cho khách du lịch trong và ngoài nước.</p>
            <p>Theo thống kê của Trung tâm Xúc tiến Du lịch Vĩnh Long (2024), hơn 68% khách lưu trú tại Cửu Long Resort đã đăng ký ít nhất một tour trải nghiệm trong thời gian nghỉ dưỡng tại đây. Từ chợ nổi, làng nghề, vườn cây trái đến các khu sinh thái nguyên sơ, mỗi hành trình đều được thiết kế với lộ trình hợp lý, an toàn và giàu tính tương tác, phù hợp với cả người lớn lẫn trẻ em.</p>
            <p>Một trong những tour nổi bật nhất là hành trình “Sáng trên chợ nổi – Chiều về làng nghề”, kéo dài 6 tiếng, khởi hành bằng thuyền từ resort đi dọc theo sông Cổ Chiên. Du khách sẽ được trực tiếp trải nghiệm cảnh buôn bán tấp nập tại chợ nổi Cái Bè, thưởng thức bữa sáng đậm chất miền Tây trên sông, rồi tiếp tục ghé thăm làng nghề làm kẹo dừa, bánh tráng và đan lục bình tại huyện Long Hồ.</p>
            <p>Không chỉ là chuyến đi, mỗi tour còn là một lớp học văn hóa di động. Du khách được giới thiệu về lịch sử vùng đất, phong tục tập quán địa phương và thậm chí có cơ hội tham gia vào các hoạt động dân gian như đờn ca tài tử, đan lát, làm bánh dân gian. Những trải nghiệm này đã góp phần gìn giữ và lan tỏa giá trị văn hóa bản địa đến gần hơn với du khách bốn phương.</p>
            <p>Trưởng phòng điều hành tour của resort chia sẻ: “Chúng tôi không muốn khách chỉ nghỉ ngơi, mà còn muốn họ mang về những ký ức văn hóa đặc biệt. Từ một buổi câu cá tại ao sen cho đến cùng người dân nấu một bữa cơm quê – đó là những điều khiến du khách nhớ mãi.”</p>
            <p>Resort hiện cung cấp hơn 12 loại tour khác nhau, bao gồm cả tour nửa ngày, tour theo nhóm gia đình, tour sinh thái, tour nông nghiệp trải nghiệm và tour dành riêng cho khách quốc tế có hướng dẫn viên song ngữ. Tất cả đều do đội ngũ hướng dẫn viên bản địa chuyên nghiệp, được đào tạo về kỹ năng hướng dẫn và sơ cấp cứu du lịch phụ trách.</p>
            <p>Đặc biệt, Cửu Long Resort đã được Sở Văn hóa – Thể thao – Du lịch Vĩnh Long công nhận là “Đơn vị tổ chức tour văn hóa tiêu biểu năm 2023” và là đối tác chính trong chương trình phát triển du lịch cộng đồng của tỉnh giai đoạn 2022–2025. Đây là minh chứng rõ ràng cho chất lượng và tầm ảnh hưởng tích cực của các hoạt động tour tại đây.</p>
            <p>Với phương châm “Mỗi chuyến đi là một hành trình văn hóa”, Cửu Long Resort đã và đang khẳng định vai trò tiên phong trong mô hình du lịch nghỉ dưỡng kết hợp trải nghiệm văn hóa, góp phần tạo nên giá trị bền vững cho du lịch Vĩnh Long nói riêng và du lịch miền Tây nói chung.</p>
            <p><em>Nguồn: Báo Du Lịch TP.HCM – Chuyên mục Du lịch sinh thái số tháng 01/2025</em></p>
        </div>

        <div id="news5" class="news-box">
            <button class="close-btn" onclick="closeNews('news5')">Đóng</button>
            <img src="<?php echo $BASE_PATH; ?>./layout/images/service/gym2.jpg" alt="News 5" style="width: 550px; height: 300px; padding: 40px 20px 20px 20px; display: block; margin: 0 auto;">
            <h2>20 <small>Tháng 12, 2024</small></h2>
            <h3>Cửu Long Resort Đột Phá Với Ứng Dụng Công Nghệ Trong Nâng Cao Chất Lượng Dịch Vụ</h3>
            <p>Trong xu thế cách mạng công nghiệp 4.0, Cửu Long Resort không chỉ dừng lại ở việc cung cấp dịch vụ nghỉ dưỡng cao cấp mà còn tiên phong áp dụng các giải pháp công nghệ hiện đại nhằm tối ưu trải nghiệm khách hàng và nâng cao hiệu quả vận hành. Những bước tiến này đã giúp resort duy trì và phát triển vị thế 5 sao trong bối cảnh cạnh tranh ngày càng gay gắt.</p>
            <p>Với việc đầu tư hệ thống quản lý khách sạn (PMS) thế hệ mới, Cửu Long Resort cho phép khách đặt phòng, quản lý dịch vụ và thanh toán trực tuyến một cách nhanh chóng và tiện lợi. Hệ thống đặt phòng thông minh tích hợp công nghệ AI giúp cá nhân hóa các ưu đãi dựa trên sở thích và lịch sử đặt phòng của khách hàng, góp phần tăng tỷ lệ khách quay lại lên đến 85%, theo báo cáo nội bộ năm 2024.</p>
            <p>Không chỉ vậy, resort còn ứng dụng công nghệ IoT trong quản lý phòng nghỉ với hệ thống điều khiển ánh sáng, điều hòa và an ninh thông minh, mang lại sự tiện nghi và an toàn tuyệt đối cho khách lưu trú. Khách hàng có thể sử dụng ứng dụng trên điện thoại để điều chỉnh các thiết bị trong phòng hoặc liên hệ nhân viên hỗ trợ ngay tức thì.</p>
            <p>Theo khảo sát của Tạp chí Công nghệ Du lịch Việt Nam năm 2024, Cửu Long Resort được đánh giá là một trong những khu nghỉ dưỡng áp dụng công nghệ số hiệu quả nhất khu vực Đồng bằng sông Cửu Long, tạo ra sự khác biệt rõ rệt so với các đối thủ cùng phân khúc.</p>
            <p>Bên cạnh đó, hệ thống quản lý nguồn nhân lực (HRM) cũng được triển khai toàn diện, giúp giám sát chất lượng phục vụ và hiệu suất làm việc của hơn 200 nhân viên. Điều này giúp nâng cao mức độ chuyên nghiệp và phản ứng nhanh với các yêu cầu của khách hàng.</p>
            <p>Resort cũng chú trọng phát triển kênh tương tác đa nền tảng, bao gồm website, ứng dụng di động, fanpage mạng xã hội, tạo điều kiện thuận lợi cho khách hàng dễ dàng tiếp cận thông tin và dịch vụ. Những chiến dịch marketing dựa trên dữ liệu lớn (Big Data) đã giúp tăng cường khả năng quảng bá và thu hút khách hàng mục tiêu.</p>
            <p>Giám đốc Công nghệ thông tin của Cửu Long Resort chia sẻ: “Công nghệ không chỉ giúp chúng tôi nâng cao chất lượng dịch vụ mà còn tạo ra lợi thế cạnh tranh bền vững. Chúng tôi hướng tới xây dựng môi trường nghỉ dưỡng hiện đại, thân thiện và dễ dàng tương tác cho khách hàng mọi lứa tuổi.”</p>
            <p>Với chiến lược ứng dụng công nghệ toàn diện, Cửu Long Resort đang ghi dấu ấn là khu nghỉ dưỡng thông minh đầu tiên tại Vĩnh Long, góp phần đưa du lịch địa phương lên một tầm cao mới, đồng thời mở ra cơ hội hợp tác và phát triển trong tương lai.</p>
            <p><em>Nguồn: Tạp chí Công nghệ Du lịch Việt Nam, Báo Vĩnh Long</em></p>
        </div>

        <div id="news6" class="news-box">
            <button class="close-btn" onclick="closeNews('news6')">Đóng</button>
            <img src="<?php echo $BASE_PATH; ?>./layout/images/room/suite3.jpg" alt="News 6" style="width: 550px; height: 300px; padding: 40px 20px 20px 20px; display: block; margin: 0 auto;">
            <h2>05 <small>Tháng 11, 2024</small></h2>
            <h3>Chất Lượng Phòng Nghỉ Tại Cửu Long Resort – Tiện Nghi Đẳng Cấp 5 Sao</h3>
            <p>Cửu Long Resort luôn chú trọng đầu tư mạnh mẽ vào chất lượng phòng nghỉ để mang lại sự thoải mái và tiện nghi tối ưu cho khách lưu trú. Với tổng số hơn 150 phòng nghỉ đa dạng từ phòng tiêu chuẩn đến phòng suite hạng sang, tất cả đều được thiết kế hiện đại, hài hòa với không gian thiên nhiên và văn hóa bản địa.</p>
            <p>Theo báo cáo khảo sát khách hàng do Viện Nghiên cứu Du lịch Việt Nam công bố năm 2024, 97,3% khách hàng đánh giá mức độ hài lòng về phòng nghỉ tại Cửu Long Resort ở mức “rất tốt” và “xuất sắc”. Đây là minh chứng rõ ràng cho sự đầu tư bài bản về cơ sở vật chất và dịch vụ phòng.</p>
            <p>Phòng nghỉ tại resort được trang bị đầy đủ các tiện nghi hiện đại như hệ thống điều hòa nhiệt độ đa vùng, tivi màn hình phẳng, minibar, két sắt điện tử, cùng với mạng Wi-Fi tốc độ cao phủ sóng toàn khu vực. Hệ thống cách âm và ánh sáng được tối ưu hóa nhằm tạo không gian yên tĩnh và thư giãn tuyệt đối.</p>
            <p>Đặc biệt, nhiều phòng suite cao cấp còn sở hữu ban công rộng rãi với tầm nhìn hướng ra sông hoặc vườn cây xanh mát, mang đến cho khách những trải nghiệm nghỉ dưỡng hòa mình với thiên nhiên. Nội thất trong phòng được chọn lựa tỉ mỉ từ các chất liệu cao cấp, kết hợp phong cách thiết kế hiện đại và truyền thống miền Tây.</p>
            <p>Cửu Long Resort còn trang bị hệ thống an ninh 24/7 với camera giám sát và đội ngũ bảo vệ chuyên nghiệp, đảm bảo an toàn tuyệt đối cho khách lưu trú. Dịch vụ dọn phòng và phục vụ tại phòng hoạt động liên tục với tiêu chuẩn chất lượng cao, đáp ứng kịp thời mọi nhu cầu của khách.</p>
            <p>Giám đốc bộ phận phòng nghỉ cho biết: “Chúng tôi luôn lấy sự hài lòng và thoải mái của khách làm mục tiêu hàng đầu. Việc đầu tư không ngừng nghỉ vào cơ sở vật chất và dịch vụ phòng giúp Cửu Long Resort giữ vững uy tín và thương hiệu 5 sao.”</p>
            <p>Với những tiêu chuẩn khắt khe và sự chăm chút tỉ mỉ, phòng nghỉ tại Cửu Long Resort không chỉ là nơi nghỉ chân mà còn là không gian thư giãn, trải nghiệm đẳng cấp, xứng đáng là lựa chọn hàng đầu của khách du lịch khi đến với Vĩnh Long.</p>
            <p><em>Nguồn: Báo Du Lịch Việt Nam – Chuyên đề Nghỉ dưỡng cao cấp, 2024</em></p>
        </div>

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
    <script>
        function openNews(id) {
            document.getElementById(id).classList.add('active');
        }
        function closeNews(id) {
            document.getElementById(id).classList.remove('active');
        }
    </script>
</body>
</html>