<?php
require_once 'database.php';

class BillDao {
    private $conn;
    private $table_name = "bills";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả hóa đơn
    public function getAllBills() {
        $query = "SELECT b.*, bk.user_id, u.first_name, u.last_name, u.email
                  FROM " . $this->table_name . " b
                  JOIN bookings bk ON b.booking_id = bk.id
                  JOIN users u ON bk.user_id = u.id
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Lấy hóa đơn theo ID
    public function getBillById($id) {
        $query = "SELECT b.*, bk.user_id, bk.room_id, bk.combo_id, bk.checkin, bk.checkout, 
                         bk.adults, bk.children, bk.notes, r.name as room_name, 
                         u.first_name, u.last_name, u.email, u.phone
                  FROM " . $this->table_name . " b
                  JOIN bookings bk ON b.booking_id = bk.id
                  LEFT JOIN rooms r ON bk.room_id = r.id
                  JOIN users u ON bk.user_id = u.id
                  WHERE b.id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy hóa đơn theo booking ID
    public function getBillByBookingId($booking_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE booking_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $booking_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy hóa đơn theo User ID
    public function getBillsByUserId($user_id) {
        $query = "SELECT b.*, bk.checkin, bk.checkout, bk.status as booking_status, r.name as room_name
                  FROM " . $this->table_name . " b
                  JOIN bookings bk ON b.booking_id = bk.id
                  LEFT JOIN rooms r ON bk.room_id = r.id
                  WHERE bk.user_id = ?
                  ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tạo hóa đơn mới (thường được gọi tự động khi tạo booking)
    public function createBill($booking_id, $total_amount, $discount, $final_amount) {
        $query = "INSERT INTO " . $this->table_name . " (booking_id, total_amount, discount, final_amount) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $booking_id);
        $stmt->bindParam(2, $total_amount);
        $stmt->bindParam(3, $discount);
        $stmt->bindParam(4, $final_amount);
        
        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($id, $payment_status) {
        $query = "UPDATE " . $this->table_name . " SET payment_status = ? WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $payment_status);
        $stmt->bindParam(2, $id);
        
        return $stmt->execute();
    }
}
?>