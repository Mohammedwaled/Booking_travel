<?php
// ملف بسيط للتعامل مع رسائل اتصل بنا
require_once __DIR__ . '/php/Contact.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $contactModel = new Contact();
    
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

    // حفظ الرسالة في قاعدة البيانات
    $contact_id = $contactModel->create($name, $email, $subject, $message);

    if ($contact_id) {
        echo "<script>
                alert('شكراً لك! تم إرسال رسالتك بنجاح وسنتواصل معك قريباً.');
                window.location.href='../../views/contact.html';
              </script>";
    } else {
        echo "<script>alert('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى'); window.location.href='../../views/contact.html';</script>";
    }
    exit();
}

// إذا لم يكن هناك POST، ارجع للصفحة
header("Location: ../../views/contact.html");
exit();


