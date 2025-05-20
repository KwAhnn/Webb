<?php
class ServiceDAO {
    public static function getAllServices($pdo) {
        $stmt = $pdo->query("SELECT id, name, price FROM services");
        $services = [];
        while ($service = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $services[$service['name']] = [
                'id' => $service['id'],
                'price' => $service['price']
            ];
        }
        return $services;
    }
}
?>
