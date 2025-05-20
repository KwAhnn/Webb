<?php
require_once __DIR__ . '/Database.php';

class ReservationDAO {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getReservationsByGuestId($guestId) {
        try {
            $sql = "
                SELECT 
                    id, room_id, combo_id, checkin, checkout, total_price, 
                    payment_method, status
                FROM bookings
                WHERE user_id = (
                    SELECT id FROM users WHERE email = (
                        SELECT email FROM Guest WHERE id = ?
                    )
                )
                ORDER BY checkin DESC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$guestId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ReservationDAO::getReservationsByGuestId error: " . $e->getMessage());
            return [];
        }
    }
}
?>