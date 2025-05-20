<?php
class TourBookingDAO {
    public static function bookTour(
        $pdo,
        $user_id,
        $tour_id,
        $start_date,
        $end_date,
        $guests,
        $children,
        $payment_method,
        $special_requests,
        $emergency_contact
    ) {
        // Gọi stored procedure book_tour
        $stmt = $pdo->prepare("CALL book_tour(?, ?, ?, ?, ?, ?, ?, ?, @tour_booking_id)");
        $stmt->execute([
            $user_id,
            $tour_id,
            $start_date,
            $end_date,
            $guests,
            $children,
            $payment_method,
            $special_requests
        ]);
        // Lấy tour_booking_id
        $stmt = $pdo->query("SELECT @tour_booking_id AS tour_booking_id");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $tour_booking_id = $result['tour_booking_id'];

        // Lưu thông tin liên hệ khẩn cấp vào tour_bookings
        $update_stmt = $pdo->prepare("UPDATE tour_bookings SET emergency_contact = ? WHERE id = ?");
        $update_stmt->execute([$emergency_contact, $tour_booking_id]);

        return $tour_booking_id;
    }
}
?>
