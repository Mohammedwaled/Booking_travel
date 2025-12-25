<?php

class BaseController {
    // لا نحتاج $db هنا - Controllers تستخدم Models فقط

    public function __construct() {
        // Constructor فارغ - Controllers تستخدم Models فقط
    }

    protected function requireAuth() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../../views/regester.html#login");
            exit();
        }
    }

    protected function requireRole($role) {
        session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $role) {
            header("Location: ../../views/regester.html#login");
            exit();
        }
    }

    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    protected function redirect($url) {
        header("Location: $url");
        exit();
    }
}

