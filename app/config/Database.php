<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $this->conn = @mysqli_connect("localhost", "root", "", "booking_travel_db");
        
        if (!$this->conn) {
            $error = mysqli_connect_error();
            error_log("Database connection error: " . $error);
            // لا نوقف التنفيذ هنا، بل نترك للكود أن يتعامل مع الخطأ
            // die("فشل الاتصال: " . $error);
        } else {
            mysqli_set_charset($this->conn, "utf8mb4");
        }
    }
    
    public function isConnected() {
        return $this->conn !== null && $this->conn !== false;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    // منع الاستنساخ
    private function __clone() {}
    
    // منع unserialize
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

