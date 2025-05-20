class GuestDAO {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getGuestByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM Guest WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createGuest($firstName, $lastName, $email, $phone, $cccd, $address) {
        $stmt = $this->pdo->prepare("INSERT INTO Guest (first_name, last_name, email, phone, cccd, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$firstName, $lastName, $email, $phone, $cccd, $address]);
        return $this->pdo->lastInsertId();
    }
}