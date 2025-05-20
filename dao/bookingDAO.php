<?php
class BookingDAO {
    public static function bookRoom(
        $pdo,
        $user_id,
        $room_id,
        $combo_id,
        $check_in,
        $check_out,
        $guests,
        $payment_method,
        $notes,
        $emergency_contact,
        $additional_services,
        $all_services,
        $total_price
    ) {
        $stmt = $pdo->prepare("CALL book_room(?, ?, ?, ?, ?, ?, 0, ?, ?, ?, @booking_id)");
        $stmt->execute([
            $user_id,
            $room_id,
            $combo_id,
            $check_in,
            $check_out,
            $guests,
            $payment_method,
            $notes,
            $emergency_contact
        ]);
        $stmt = $pdo->query("SELECT @booking_id AS booking_id");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $booking_id = $result['booking_id'];

        if ($combo_id) {
            $combo_stmt = $pdo->prepare("INSERT INTO booking_combos (booking_id, combo_id) VALUES (?, ?)");
            $combo_stmt->execute([$booking_id, $combo_id]);
        }

        $insert_svc = $pdo->prepare("INSERT INTO booking_services (booking_id, service_id, quantity) VALUES (?, ?, 1)");
        foreach ($additional_services as $service) {
            if (isset($all_services[$service])) {
                $insert_svc->execute([$booking_id, $all_services[$service]['id']]);
            }
        }

        $update_price = $pdo->prepare("UPDATE bookings SET total_price = ? WHERE id = ?");
        $update_price->execute([$total_price, $booking_id]);

        return $booking_id;
    }
}
?>