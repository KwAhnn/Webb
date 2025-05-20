<?php
class RoomDAO {
    public static function getRoomById($pdo, $room_id) {
        $stmt = $pdo->prepare("SELECT id, name, capacity, price, original_price, facilities FROM rooms WHERE id = ?");
        $stmt->execute([$room_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPrimaryImage($pdo, $room_id) {
        $stmt = $pdo->prepare("SELECT image_url FROM room_images WHERE room_id = ? AND is_primary = 1");
        $stmt->execute([$room_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>