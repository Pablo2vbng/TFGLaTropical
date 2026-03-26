<?php


class UserModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    /* OBTENEMOS LOS EVENTOS QUE TIENE EL USUARIO */
    public function getUserEvents($user_id) {
        $stmt = $this->db->prepare("
            SELECT e.*, eu.is_paid as user_paid, eu.has_car, eu.price_modifier 
            FROM events e
            INNER JOIN event_user eu ON e.id = eu.event_id
            WHERE eu.user_id = ? 
            AND (e.date >= CURDATE() OR eu.is_paid = 0)
            ORDER BY e.date ASC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}