<?php
require_once 'database.php';

class TourDAO {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createTour($title, $location, $price, $startDate, $endDate) {
        $stmt = $this->pdo->prepare("INSERT INTO tours (title, location, price, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $location, $price, $startDate, $endDate]);
    }

    public function getAllTours() {
        $stmt = $this->pdo->query("SELECT * FROM tours");
        return $stmt->fetchAll();
    }

    public function bookTour($userId, $tourId) {
        $stmt = $this->pdo->prepare("INSERT INTO tour_bookings (user_id, tour_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $tourId]);
    }

    public static function getTourById($pdo, $tour_id) {
        $stmt = $pdo->prepare("SELECT id, name, description, price, duration FROM tours WHERE id = ?");
        $stmt->execute([$tour_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
