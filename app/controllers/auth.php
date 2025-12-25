<?php
// ملف بسيط للتعامل مع authentication مباشرة
require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/php/User.php';

session_start();

// Register
if (isset($_POST['register_btn'])) {
    $userModel = new User();
    
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
    if ($userModel->findByEmail($email)) {
        echo "<script>alert('البريد الإلكتروني مسجل بالفعل'); window.location.href='../../views/regester.html#login';</script>";
        exit();
    }

    // إنشاء المستخدم
    $user_id = $userModel->create($full_name, $email, $password, $role);

    if ($user_id) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['role'] = $role;

        if ($role == 'agent') {
            header("Location: ../../admin/dashboard.php");
        } elseif ($role == 'admin') {
            header("Location: ../../admin/Super Admin Dashboard.html");
        } else {
            header("Location: ../../views/index.html");
        }
        exit();
    } else {
        echo "<script>alert('حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى'); window.location.href='../../views/regester.html';</script>";
        exit();
    }
}

// Login
if (isset($_POST['login_btn'])) {
    $userModel = new User();
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "<script>alert('يرجى إدخال البريد الإلكتروني وكلمة المرور'); window.location.href='../../views/regester.html#login';</script>";
        exit();
    }

    $user = $userModel->findByEmail($email);

    if ($user && $userModel->verifyPassword($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: ../../admin/Super Admin Dashboard.html");
        } elseif ($user['role'] == 'agent') {
            header("Location: ../../admin/dashboard.php");
        } else {
            header("Location: ../../views/index.html");
        }
        exit();
    } else {
        echo "<script>alert('بيانات الدخول غير صحيحة'); window.location.href='../../views/regester.html#login';</script>";
        exit();
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../../views/index.html");
    exit();
}

// إذا لم يكن هناك action، ارجع للصفحة الرئيسية
header("Location: ../../views/regester.html");
exit();

