<?php
class ComboDAO {
    public static function getComboById($pdo, $combo_id) {
        $stmt = $pdo->prepare("SELECT id, name, room_id, discount_percent FROM combos WHERE id = ?");
        $stmt->execute([$combo_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getServicesByComboId($pdo, $combo_id) {
        $svc_stmt = $pdo->prepare("SELECT s.name FROM services s JOIN combo_services cs ON s.id = cs.service_id WHERE cs.combo_id = ?");
        $svc_stmt->execute([$combo_id]);
        return $svc_stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>