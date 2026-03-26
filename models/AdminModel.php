<?php


class AdminModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function getPendingUsers() {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE is_approved = 0 AND role = 'user' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllEvents() {
        $stmt = $this->db->prepare("SELECT * FROM events ORDER BY date ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getApprovedMusicians() {
        $stmt = $this->db->prepare("SELECT id, name, instrument FROM users WHERE is_approved = 1 AND role = 'user' ORDER BY instrument ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingPayments() {
        $stmt = $this->db->prepare("
            SELECT eu.id as reg_id, e.title, e.date, u.name, u.instrument, e.base_price, eu.price_modifier
            FROM event_user eu
            INNER JOIN events e ON eu.event_id = e.id
            INNER JOIN users u ON eu.user_id = u.id
            WHERE e.is_paid = 1 AND eu.is_paid = 0
            ORDER BY e.date DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}