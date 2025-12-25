<?php

require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/php/User.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function register() {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register_btn'])) {
            echo "<script>alert('خطأ: طلب غير صحيح'); window.location.href='../../views/regester.html';</script>";
            exit();
        }

        $full_name = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        // التحقق من البيانات
        if (empty($full_name) || empty($email) || empty($password)) {
            echo "<script>alert('يرجى ملء جميع الحقول'); window.location.href='../../views/regester.html';</script>";
            exit();
        }

        // التحقق من صحة الإيميل
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('البريد الإلكتروني غير صحيح'); window.location.href='../../views/regester.html';</script>";
            exit();
        }

        // التحقق من وجود الإيميل
        if ($this->userModel->findByEmail($email)) {
            echo "<script>alert('البريد الإلكتروني مسجل بالفعل'); window.location.href='../../views/regester.html#login';</script>";
            exit();
        }

        // إنشاء المستخدم
        $user_id = $this->userModel->create($full_name, $email, $password, $role);

        if ($user_id) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = $role;

            if ($role == 'agent') {
                header("Location: ../../../admin/dashboard.php");
            } elseif ($role == 'admin') {
                header("Location: ../../../admin/Super Admin Dashboard.html");
            } else {
                header("Location: ../../../views/index.html");
            }
            exit();
        } else {
            echo "<script>alert('حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى'); window.location.href='../../views/regester.html';</script>";
            exit();
        }
    }

    public function login() {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['login_btn'])) {
            echo "<script>alert('خطأ: طلب غير صحيح'); window.location.href='../../views/regester.html#login';</script>";
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo "<script>alert('يرجى إدخال البريد الإلكتروني وكلمة المرور'); window.location.href='../../views/regester.html#login';</script>";
            exit();
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: ../../../admin/Super Admin Dashboard.html");
            } elseif ($user['role'] == 'agent') {
                header("Location: ../../../admin/dashboard.php");
            } else {
                header("Location: ../../../views/index.html");
            }
            exit();
        } else {
            echo "<script>alert('بيانات الدخول غير صحيحة'); window.location.href='../../views/regester.html#login';</script>";
            exit();
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../../../views/index.html");
        exit();
    }
}
