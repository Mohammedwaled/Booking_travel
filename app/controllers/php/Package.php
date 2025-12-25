<?php

require_once __DIR__ . '/../../config/Database.php';

class Package {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $id = intval($id);
        $sql = "SELECT packages.*, users.full_name as agent_name 
                FROM packages 
                JOIN users ON packages.agent_id = users.id 
                WHERE packages.id = $id";
        $result = mysqli_query($this->db, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function findApproved() {
        $sql = "SELECT packages.*, users.full_name as agent_name 
                FROM packages 
                JOIN users ON packages.agent_id = users.id 
                WHERE status = 'approved' 
                ORDER BY packages.id DESC";
        $result = mysqli_query($this->db, $sql);
        $packages = [];
        while($row = mysqli_fetch_assoc($result)) {
            $packages[] = $row;
        }
        return $packages;
    }

    public function create($agent_id, $city_id, $title, $price, $details) {
        $agent_id = intval($agent_id);
        $city_id = intval($city_id);
        $title = mysqli_real_escape_string($this->db, $title);
        $price = floatval($price);
        $details = mysqli_real_escape_string($this->db, $details);

        $sql = "INSERT INTO packages (agent_id, city_id, title, price, details, status) 
                VALUES ($agent_id, $city_id, '$title', $price, '$details', 'pending')";
        
        if (mysqli_query($this->db, $sql)) {
            return mysqli_insert_id($this->db);
        }
        
        return false;
    }

    public function updateStatus($id, $status) {
        $id = intval($id);
        $status = mysqli_real_escape_string($this->db, $status);
        $sql = "UPDATE packages SET status = '$status' WHERE id = $id";
        return mysqli_query($this->db, $sql);
    }

    public function findPending() {
        $sql = "SELECT packages.*, users.full_name 
                FROM packages 
                JOIN users ON packages.agent_id = users.id 
                WHERE status = 'pending'";
        $result = mysqli_query($this->db, $sql);
        $packages = [];
        while($row = mysqli_fetch_assoc($result)) {
            $packages[] = $row;
        }
        return $packages;
    }

    public function search($query) {
        $query = mysqli_real_escape_string($this->db, $query);
        $sql = "SELECT packages.*, users.full_name as agent_name 
                FROM packages 
                JOIN users ON packages.agent_id = users.id 
                WHERE (packages.title LIKE '%$query%' OR packages.details LIKE '%$query%') 
                AND status = 'approved'
                ORDER BY packages.id DESC";
        $result = mysqli_query($this->db, $sql);
        $packages = [];
        while($row = mysqli_fetch_assoc($result)) {
            $packages[] = $row;
        }
        return $packages;
    }
}

