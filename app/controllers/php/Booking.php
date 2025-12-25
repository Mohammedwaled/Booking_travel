<?php

require_once __DIR__ . '/../../config/Database.php';

class Booking {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($package_id, $user_id, $customer_name, $customer_phone, $price) {
        $package_id = intval($package_id);
        $user_id = $user_id ? intval($user_id) : null;
        $customer_name = mysqli_real_escape_string($this->db, $customer_name);
        $customer_phone = mysqli_real_escape_string($this->db, $customer_phone);
        $price = floatval($price);

        $sql = "INSERT INTO bookings (package_id, user_id, customer_name, customer_phone, price, status) 
                VALUES (?, ?, ?, ?, ?, 'قيد الانتظار')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iissd", $package_id, $user_id, $customer_name, $customer_phone, $price);
        
        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;
            $stmt->close();
            return $booking_id;
        }
        
        $stmt->close();
        return false;
    }

    public function findByUserId($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT bookings.*, packages.title as package_title 
                FROM bookings 
                JOIN packages ON bookings.package_id = packages.id 
                WHERE bookings.user_id = $user_id 
                ORDER BY bookings.created_at DESC";
        $result = mysqli_query($this->db, $sql);
        $bookings = [];
        while($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
        return $bookings;
    }
}

