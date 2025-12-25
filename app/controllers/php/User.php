<?php

require_once __DIR__ . '/../../config/Database.php';

class User {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        if (!$database->isConnected()) {
            error_log("User Model: Database not connected");
        }
        $this->db = $database->getConnection();
        
        if (!$this->db) {
            error_log("User Model: Failed to get database connection");
        }
    }

    public function findByEmail($email) {
        $email = mysqli_real_escape_string($this->db, $email);
        $result = mysqli_query($this->db, "SELECT * FROM users WHERE email = '$email'");
        return mysqli_fetch_assoc($result);
    }

    public function findById($id) {
        $id = intval($id);
        $result = mysqli_query($this->db, "SELECT * FROM users WHERE id = $id");
        return mysqli_fetch_assoc($result);
    }

    public function create($full_name, $email, $password, $role = 'user') {
        // التحقق من صحة البيانات قبل المعالجة
        if (empty($full_name) || empty($email) || empty($password)) {
            error_log("User creation error: Missing required fields");
            return false;
        }
        
        $full_name = mysqli_real_escape_string($this->db, $full_name);
        $email = mysqli_real_escape_string($this->db, $email);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // التحقق من نجاح التشفير
        if ($hashedPassword === false) {
            error_log("User creation error: Password hashing failed");
            return false;
        }
        
        $role = mysqli_real_escape_string($this->db, $role);

        $sql = "INSERT INTO users (full_name, email, password, role) VALUES ('$full_name', '$email', '$hashedPassword', '$role')";
        
        if (mysqli_query($this->db, $sql)) {
            return mysqli_insert_id($this->db);
        }
        
        // في حالة الخطأ، سجل الخطأ ولكن لا ترمي Exception
        $error = mysqli_error($this->db);
        error_log("User creation error: " . $error);
        
        // إرجاع false بدلاً من رمي Exception
        return false;
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}

