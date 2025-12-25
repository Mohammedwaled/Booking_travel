<?php

require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/php/Contact.php';

class ContactController extends BaseController {
    private $contactModel;

    public function __construct() {
        parent::__construct();
        $this->contactModel = new Contact();
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "<script>alert('طلب غير صحيح'); window.location.href='../../views/contact.html';</script>";
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // التحقق من البيانات
        if (empty($name) || empty($email) || empty($message)) {
            echo "<script>alert('يرجى ملء جميع الحقول المطلوبة'); window.location.href='../../views/contact.html';</script>";
            exit();
        }

        // التحقق من صحة الإيميل
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('البريد الإلكتروني غير صحيح'); window.location.href='../../views/contact.html';</script>";
            exit();
        }

        // التحقق من طول الرسالة
        if (strlen($message) < 10) {
            echo "<script>alert('الرسالة قصيرة جداً، يرجى توضيح استفسارك'); window.location.href='../../views/contact.html';</script>";
            exit();
        }

        // حفظ الرسالة
        $contact_id = $this->contactModel->create($name, $email, $subject, $message);

        if ($contact_id) {
            echo "<script>alert('شكراً لك! تم إرسال رسالتك بنجاح وسنتواصل معك قريباً.'); window.location.href='../../views/contact.html';</script>";
        } else {
            echo "<script>alert('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى'); window.location.href='../../views/contact.html';</script>";
        }
        exit();
    }

    public function index() {
        $this->requireRole('admin');
        
        $contacts = $this->contactModel->getAll();
        return $contacts;
    }
}


