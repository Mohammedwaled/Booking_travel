<?php

require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/php/Booking.php';
require_once __DIR__ . '/php/Package.php';

class BookingController extends BaseController {
    private $bookingModel;
    private $packageModel;

    public function __construct() {
        parent::__construct();
        $this->bookingModel = new Booking();
        $this->packageModel = new Package();
    }

    public function store() {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['confirm_booking'])) {
            echo "<script>alert('طلب غير صحيح'); history.back();</script>";
            exit();
        }

        $package_id = intval($_POST['package_id'] ?? 0);

        // جلب بيانات الرحلة
        $package = $this->packageModel->findById($package_id);

        if (!$package) {
            echo "<script>alert('العرض غير موجود'); history.back();</script>";
            exit();
        }

        $title = $package['title'];
        $price = $package['price'];

        // بيانات الحجز
        $customer_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : "ضيف";
        $customer_phone = "01xxxxxxxxx";
        $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

        // إنشاء الحجز
        $booking_id = $this->bookingModel->create($package_id, $user_id, $customer_name, $customer_phone, $price);

        if ($booking_id) {
            // تحديد المدينة من العنوان
            $city = 'الأقصر'; // default
            if (stripos($title, 'قاهرة') !== false || stripos($title, 'cairo') !== false) {
                $city = 'القاهرة';
            } elseif (stripos($title, 'أقصر') !== false || stripos($title, 'luxor') !== false) {
                $city = 'الأقصر';
            } elseif (stripos($title, 'جيزة') !== false || stripos($title, 'giza') !== false) {
                $city = 'الجيزة';
            } elseif (stripos($title, 'دهب') !== false || stripos($title, 'dahab') !== false) {
                $city = 'دهب';
            } elseif (stripos($title, 'إسكندرية') !== false || stripos($title, 'alexandria') !== false) {
                $city = 'الإسكندرية';
            }
            
            echo "<script>
                    alert('تم تسجيل طلبك بنجاح');
                    window.location.href = '../../views/booking-confirm.html?title=" . urlencode($title) . "&price=" . $price . "&city=" . urlencode($city) . "';
                  </script>";
        } else {
            echo "<script>alert('حدث خطأ أثناء الحجز'); history.back();</script>";
        }
    }
}

