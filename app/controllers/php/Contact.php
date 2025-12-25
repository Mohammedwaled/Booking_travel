<?php

require_once __DIR__ . '/../../config/Database.php';

class Contact {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($name, $email, $subject, $message) {
        $name = mysqli_real_escape_string($this->db, $name);
        $email = mysqli_real_escape_string($this->db, $email);
        $subject = mysqli_real_escape_string($this->db, $subject);
        $message = mysqli_real_escape_string($this->db, $message);

        $sql = "INSERT INTO contacts (name, email, subject, message, status) 
                VALUES ('$name', '$email', '$subject', '$message', 'new')";
        
        if (mysqli_query($this->db, $sql)) {
            return mysqli_insert_id($this->db);
        }
        
        error_log("Contact creation error: " . mysqli_error($this->db));
        return false;
    }

    public function getAll($status = null) {
        $sql = "SELECT * FROM contacts";
        
        if ($status) {
            $status = mysqli_real_escape_string($this->db, $status);
            $sql .= " WHERE status = '$status'";
        }
        
        $sql .= " ORDER BY created_at DESC";
        $result = mysqli_query($this->db, $sql);
        $contacts = [];
        while($row = mysqli_fetch_assoc($result)) {
            $contacts[] = $row;
        }
        return $contacts;
    }

    public function findById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM contacts WHERE id = $id";
        $result = mysqli_query($this->db, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function updateStatus($id, $status) {
        $id = intval($id);
        $status = mysqli_real_escape_string($this->db, $status);
        $sql = "UPDATE contacts SET status = '$status' WHERE id = $id";
        return mysqli_query($this->db, $sql);
    }
}


