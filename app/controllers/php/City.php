<?php

require_once __DIR__ . '/../../config/Database.php';

class City {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $id = intval($id);
        $sql = "SELECT id, title, details, price, agent_name FROM cities WHERE id = $id";
        $result = mysqli_query($this->db, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function getAll() {
        $sql = "SELECT * FROM cities ORDER BY id ASC";
        $result = mysqli_query($this->db, $sql);
        $cities = [];
        while($row = mysqli_fetch_assoc($result)) {
            $cities[] = $row;
        }
        return $cities;
    }

    public function search($query) {
        $query = mysqli_real_escape_string($this->db, $query);
        $sql = "SELECT * FROM cities WHERE title LIKE '%$query%' OR details LIKE '%$query%' ORDER BY id ASC";
        $result = mysqli_query($this->db, $sql);
        $cities = [];
        while($row = mysqli_fetch_assoc($result)) {
            $cities[] = $row;
        }
        return $cities;
    }
}

