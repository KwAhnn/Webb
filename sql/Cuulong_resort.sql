-- Xóa và tạo lại cơ sở dữ liệu
DROP DATABASE IF EXISTS Cuulong_Resort;
CREATE DATABASE Cuulong_Resort CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE Cuulong_Resort;

-- Bảng roles: Danh sách vai trò
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên vai trò'
) COMMENT 'Danh sách các vai trò';

-- Thêm vài dữ liệu ví dụ cho roles
INSERT INTO roles (role_name) VALUES ('admin'), ('user'), ('khách hàng');

-- Bảng users: Lưu thông tin người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL COMMENT 'Tên',
    last_name VARCHAR(50) NOT NULL COMMENT 'Họ',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email',
    phone VARCHAR(15) COMMENT 'Số điện thoại',
    cccd VARCHAR(20) COMMENT 'CCCD/CMND',
    address TEXT COMMENT 'Địa chỉ',
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên đăng nhập',
    password VARCHAR(255) NOT NULL COMMENT 'Mật khẩu (đã mã hóa)',
    role_id INT NOT NULL DEFAULT 2 COMMENT 'ID vai trò',
    profile_pic VARCHAR(255) DEFAULT 'default-avatar.png' COMMENT 'Ảnh đại diện',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_cccd CHECK (cccd IS NULL OR cccd = '' OR cccd REGEXP '^[0-9]{12}$')) COMMENT 'Lưu thông tin người dùng';

-- Bảng rooms: Lưu thông tin loại phòng
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Tên loại phòng',
    capacity INT NOT NULL COMMENT 'Sức chứa (số người)',
    price DECIMAL(12,2) NOT NULL COMMENT 'Giá phòng mỗi đêm',
    original_price DECIMAL(12,2) NOT NULL COMMENT 'Giá gốc trước giảm',
    facilities TEXT COMMENT 'Tiện nghi phòng',
    total_rooms INT NOT NULL COMMENT 'Tổng số phòng',
    CHECK (capacity > 0),
    CHECK (price >= 0),
    CHECK (total_rooms >= 0)
) COMMENT 'Lưu thông tin loại phòng';

-- Bảng room_images: Lưu hình ảnh phòng
CREATE TABLE room_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL COMMENT 'Đường dẫn hình ảnh',
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Hình ảnh chính',
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
) COMMENT 'Lưu hình ảnh phòng';

-- Bảng services: Lưu thông tin dịch vụ
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Tên dịch vụ',
    description TEXT COMMENT 'Mô tả dịch vụ',
    price DECIMAL(12,2) NOT NULL COMMENT 'Giá dịch vụ',
    CHECK (price >= 0)
) COMMENT 'Lưu thông tin dịch vụ';

-- Bảng combos: Lưu thông tin combo
CREATE TABLE combos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL COMMENT 'ID phòng',
    name VARCHAR(100) NOT NULL COMMENT 'Tên combo',
    discount_percent INT NOT NULL COMMENT 'Phần trăm giảm giá',
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    CHECK (discount_percent BETWEEN 0 AND 100)
) COMMENT 'Lưu thông tin combo';

-- Bảng combo_services: Lưu dịch vụ thuộc combo
CREATE TABLE combo_services (
    combo_id INT,
    service_id INT,
    PRIMARY KEY (combo_id, service_id),
    FOREIGN KEY (combo_id) REFERENCES combos(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) COMMENT 'Lưu dịch vụ thuộc combo';

-- Bảng bookings: Lưu thông tin đặt phòng
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'ID người dùng',
    room_id INT COMMENT 'ID phòng',
    combo_id INT COMMENT 'ID combo',
    checkin DATE COMMENT 'Ngày nhận phòng',
    checkout DATE COMMENT 'Ngày trả phòng',
    adults INT NOT NULL COMMENT 'Số người lớn',
    children INT NOT NULL DEFAULT 0 COMMENT 'Số trẻ em',
    total_price DECIMAL(12,2) NOT NULL COMMENT 'Tổng giá',
    status ENUM('chờ xác nhận', 'đã xác nhận', 'đã hủy') DEFAULT 'chờ xác nhận' COMMENT 'Trạng thái',
    payment_status ENUM('chưa thanh toán', 'đã thanh toán') DEFAULT 'chưa thanh toán' COMMENT 'Trạng thái thanh toán',
    payment_method ENUM('trực tuyến', 'tại quầy') NOT NULL COMMENT 'Phương thức thanh toán',
    notes TEXT COMMENT 'Ghi chú (yêu cầu đặc biệt)',
    emergency_contact VARCHAR(100) COMMENT 'Liên hệ khẩn cấp',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (combo_id) REFERENCES combos(id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin đặt phòng';

-- Bảng booking_services: Lưu dịch vụ bổ sung cho đặt phòng
CREATE TABLE booking_services (
    booking_id INT,
    service_id INT,
    quantity INT DEFAULT 1 COMMENT 'Số lượng',
    PRIMARY KEY (booking_id, service_id),
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    CHECK (quantity >= 1)
) COMMENT 'Lưu dịch vụ bổ sung cho đặt phòng';

-- Bảng booking_combos: Lưu combo được chọn trong đặt phòng
CREATE TABLE booking_combos (
    booking_id INT,
    combo_id INT,
    PRIMARY KEY (booking_id, combo_id),
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (combo_id) REFERENCES combos(id) ON DELETE CASCADE
) COMMENT 'Lưu combo được chọn trong đặt phòng';

-- Bảng bills: Lưu thông tin hóa đơn
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL COMMENT 'ID đặt phòng',
    total_amount DECIMAL(10,2) COMMENT 'Tổng tiền trước giảm giá',
    discount DECIMAL(10,2) COMMENT 'Số tiền giảm giá',
    final_amount DECIMAL(10,2) COMMENT 'Tổng tiền sau giảm giá',
    payment_status ENUM('chưa thanh toán', 'đã thanh toán') DEFAULT 'chưa thanh toán' COMMENT 'Trạng thái thanh toán',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin hóa đơn';

-- Bảng tours: Lưu thông tin tour du lịch
CREATE TABLE tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Tên tour',
    description TEXT COMMENT 'Mô tả tour',
    price DECIMAL(12,2) NOT NULL COMMENT 'Giá tour',
    duration VARCHAR(50) NOT NULL COMMENT 'Thời gian tour (e.g., 4N3Đ)',
    CHECK (price >= 0)
) COMMENT 'Lưu thông tin tour du lịch';

-- Bảng tour_bookings: Lưu thông tin đặt tour
CREATE TABLE tour_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'ID người dùng',
    tour_id INT NOT NULL COMMENT 'ID tour',
    start_date DATE NOT NULL COMMENT 'Ngày bắt đầu',
    end_date DATE NOT NULL COMMENT 'Ngày kết thúc',
    adults INT NOT NULL COMMENT 'Số người lớn',
    children INT NOT NULL DEFAULT 0 COMMENT 'Số trẻ em',
    total_price DECIMAL(12,2) NOT NULL COMMENT 'Tổng giá',
    status ENUM('chờ xác nhận', 'đã xác nhận', 'đã hủy') DEFAULT 'chờ xác nhận' COMMENT 'Trạng thái',
    payment_status ENUM('chưa thanh toán', 'đã thanh toán') DEFAULT 'chưa thanh toán' COMMENT 'Trạng thái thanh toán',
    payment_method ENUM('trực tuyến', 'tại quầy') NOT NULL COMMENT 'Phương thức thanh toán',
    notes TEXT COMMENT 'Ghi chú',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin đặt tour';

-- Bảng messages: Lưu thông tin tin nhắn
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'ID người dùng nhận tin nhắn',
    subject VARCHAR(255) NOT NULL COMMENT 'Tiêu đề tin nhắn',
    content TEXT NOT NULL COMMENT 'Nội dung tin nhắn',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian gửi',
    is_read BOOLEAN DEFAULT FALSE COMMENT 'Trạng thái đọc',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) COMMENT 'Lưu thông tin tin nhắn';

-- Dữ liệu mẫu

INSERT INTO users (first_name, last_name, email, phone, cccd, address, username, password, role_id)
VALUES ('Admin', 'User', 'admin@cuulongresort.com', '0987654321', '123456789012', 'Vĩnh Long', 'admin', '$2y$10$examplehashedpassword', 1),
       ('Khách', 'Nguyễn', 'khach@cuulongresort.com', '0912345678', '987654321012', 'Cần Thơ', 'khach1', '$2y$10$examplehashedpassword', 2);

INSERT INTO rooms (name, capacity, price, original_price, facilities, total_rooms)
VALUES ('Standard', 2, 400000, 450000, 'WiFi, TV', 40),
       ('Deluxe', 4, 500000, 600000, 'WiFi, TV, Ban công', 20),
       ('Suite', 4, 700000, 800000, 'WiFi, TV, Bồn tắm, Phòng khách', 10),
       ('Family', 10, 900000, 1000000, 'WiFi, TV, Minibar, Bếp nhỏ', 12),
       ('Villa', 6, 1500000, 1700000, 'WiFi, TV, Minibar, Hồ bơi riêng', 5),
       ('Presidential Suite', 8, 2000000, 2300000, 'WiFi, TV, Minibar, Jacuzzi, Phòng họp', 3);

INSERT INTO room_images (room_id, image_url, is_primary)
VALUES (1, 'standard.jpg', TRUE),
       (2, 'deluxe3.jpg', TRUE),
       (3, 'suite3.jpg', TRUE),
       (4, 'f3.jpg', TRUE),
       (5, 'villa.jpg', TRUE),
       (6, 'presidential.jpg', TRUE);

INSERT INTO services (name, description, price)
VALUES ('Bữa sáng', 'Bữa sáng buffet', 100000),
       ('Spa', 'Dịch vụ spa thư giãn', 300000),
       ('Đưa đón sân bay', 'Dịch vụ đưa đón sân bay', 200000);

INSERT INTO combos (room_id, name, discount_percent)
VALUES (2, 'Deluxe + Bữa Sáng', 10),
       (3, 'Suite + Spa', 15),
       (4, 'Family + Đưa Đón Sân Bay', 12);

INSERT INTO combo_services (combo_id, service_id)
VALUES (1, 1),
       (2, 2),
       (3, 3);

INSERT INTO tours (name, description, price, duration)
VALUES ('Khám Phá Miền Tây 4N3Đ', 'Tiền Giang, Cần Thơ, Sóc Trăng, Cà Mau', 2590000, '4N3Đ'),
       ('Hành Trình Xanh Miền Tây 3N2Đ', 'Rừng tràm Trà Sư, làng nổi Tân Lập', 1990000, '3N2Đ'),
       ('Trải Nghiệm Miền Tây 1 Ngày', 'Tiền Giang, Bến Tre', 1190000, '1 Ngày');

-- Stored Procedure: Đặt phòng
DELIMITER //
CREATE PROCEDURE book_room(
    IN p_user_id INT,
    IN p_room_id INT,
    IN p_combo_id INT,
    IN p_checkin DATE,
    IN p_checkout DATE,
    IN p_adults INT,
    IN p_children INT,
    IN p_payment_method VARCHAR(20),
    IN p_notes TEXT,
    IN p_emergency_contact VARCHAR(100),
    OUT p_booking_id INT
)
BEGIN
    DECLARE v_total_price DECIMAL(12,2);
    DECLARE v_room_price DECIMAL(12,2);
    DECLARE v_discount_percent INT DEFAULT 0;
    DECLARE v_days INT;

    -- Tính số ngày
    SET v_days = DATEDIFF(p_checkout, p_checkin);

    -- Lấy giá phòng
    SELECT price INTO v_room_price FROM rooms WHERE id = p_room_id;

    -- Lấy phần trăm giảm giá từ combo (nếu có)
    IF p_combo_id IS NOT NULL THEN
        SELECT discount_percent INTO v_discount_percent FROM combos WHERE id = p_combo_id;
    END IF;

    -- Tính tổng giá
    SET v_total_price = v_room_price * v_days * (1 - v_discount_percent / 100);

    -- Thêm bản ghi vào bookings
    INSERT INTO bookings (user_id, room_id, combo_id, checkin, checkout, adults, children, total_price, payment_method, notes, emergency_contact)
    VALUES (p_user_id, p_room_id, p_combo_id, p_checkin, p_checkout, p_adults, p_children, v_total_price, p_payment_method, p_notes, p_emergency_contact);

    -- Lấy ID của booking vừa tạo
    SET p_booking_id = LAST_INSERT_ID();

    -- Thêm hóa đơn
    INSERT INTO bills (booking_id, total_amount, discount, final_amount)
    VALUES (p_booking_id, v_room_price * v_days, v_room_price * v_days * v_discount_percent / 100, v_total_price);
END //
DELIMITER ;

-- Stored Procedure: Đặt tour
DELIMITER //
CREATE PROCEDURE book_tour(
    IN p_user_id INT,
    IN p_tour_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE,
    IN p_adults INT,
    IN p_children INT,
    IN p_payment_method ENUM('trực tuyến', 'tại quầy'),
    IN p_notes TEXT,
    OUT p_tour_booking_id INT
)
BEGIN
    DECLARE v_tour_price DECIMAL(12,2);
    DECLARE v_total_price DECIMAL(12,2);

    -- Lấy giá tour
    SELECT price INTO v_tour_price FROM tours WHERE id = p_tour_id;

    -- Tính tổng giá (trẻ em tính 50%)
    SET v_total_price = v_tour_price * (p_adults + p_children * 0.5);

    -- Thêm bản ghi vào tour_bookings
    INSERT INTO tour_bookings (user_id, tour_id, start_date, end_date, adults, children, total_price, payment_method, notes)
    VALUES (p_user_id, p_tour_id, p_start_date, p_end_date, p_adults, p_children, v_total_price, p_payment_method, p_notes);

    -- Lấy ID đặt tour vừa tạo
    SET p_tour_booking_id = LAST_INSERT_ID();
END //
DELIMITER ;

-- Trigger: Cập nhật payment_status trong bookings khi bills thay đổi
DELIMITER //
CREATE TRIGGER after_bill_update
AFTER UPDATE ON bills
FOR EACH ROW
BEGIN
    UPDATE bookings
    SET payment_status = NEW.payment_status
    WHERE id = NEW.booking_id;
END //
DELIMITER ;

-- Trigger: Kiểm tra số lượng phòng trước khi đặt
DELIMITER //
CREATE TRIGGER before_booking_insert
BEFORE INSERT ON bookings
FOR EACH ROW
BEGIN
    DECLARE v_available_rooms INT;
    IF NEW.room_id IS NOT NULL THEN
        SELECT total_rooms - (
            SELECT COUNT(*) 
            FROM bookings b 
            WHERE b.room_id = NEW.room_id 
            AND b.status != 'đã hủy'
            AND (NEW.checkin <= b.checkout AND NEW.checkout >= b.checkin)
        ) INTO v_available_rooms
        FROM rooms
        WHERE id = NEW.room_id;

        IF v_available_rooms <= 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Không còn phòng trống trong khoảng thời gian này';
        END IF;
    END IF;
END;
DELIMITER ;

-- Chỉ mục để tối ưu truy vấn
CREATE INDEX idx_bookings_user_id ON bookings(user_id);
CREATE INDEX idx_bookings_room_id ON bookings(room_id);
CREATE INDEX idx_tour_bookings_user_id ON tour_bookings(user_id);
CREATE INDEX idx_tour_bookings_tour_id ON tour_bookings(tour_id);
CREATE INDEX idx_bills_booking_id ON bills(booking_id);