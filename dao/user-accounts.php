<?php
session_start();
require_once 'pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'login') {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        // Kiểm tra email và mật khẩu không rỗng
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = "Vui lòng nhập email và mật khẩu.";
            header("Location: ../login.php");
            exit();
        }

        try {
            // Truy vấn kiểm tra email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Đăng nhập thành công, lưu thông tin vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                header("Location: ../view/index.php");
                exit();
            } else {
                // Sai email hoặc mật khẩu
                $_SESSION['login_error'] = "Email hoặc mật khẩu không đúng.";
                header("Location: ../login.php");
                exit();
            }
        } catch (PDOException $e) {
            $_SESSION['login_error'] = "Đã có lỗi xảy ra. Vui lòng thử lại sau.";
            header("Location: ../login.php");
            exit();
        }
    }
}
?>