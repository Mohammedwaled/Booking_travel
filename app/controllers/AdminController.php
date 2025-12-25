<?php

require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/php/Package.php';

class AdminController extends BaseController {
    private $packageModel;

    public function __construct() {
        parent::__construct();
        $this->packageModel = new Package();
    }

    public function addPackage() {
        $this->requireRole('agent');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['add_package_btn'])) {
            return;
        }

        session_start();
        $agent_id = $_SESSION['user_id'];
        $city_id = intval($_POST['city_id'] ?? 0);
        $title = $_POST['title'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $details = $_POST['details'] ?? '';

        if (empty($title) || $price <= 0) {
            echo "<script>alert('يرجى ملء جميع الحقول بشكل صحيح'); window.location.href='../../admin/dashboard.php';</script>";
            exit();
        }

        $package_id = $this->packageModel->create($agent_id, $city_id, $title, $price, $details);

        if ($package_id) {
            echo "<script>alert('تم إرسال العرض بنجاح!'); window.location.href='../../admin/dashboard.php';</script>";
        } else {
            echo "<script>alert('حدث خطأ'); window.location.href='../../admin/dashboard.php';</script>";
        }
    }

    public function approvePackage($id, $action) {
        $this->requireRole('admin');
        
        $id = intval($id);
        $action = in_array($action, ['approved', 'rejected']) ? $action : 'pending';
        
        if ($this->packageModel->updateStatus($id, $action)) {
            $this->redirect('../../admin/super admin.php');
        } else {
            echo "<script>alert('حدث خطأ'); history.back();</script>";
        }
    }

    public function pendingPackages() {
        $this->requireRole('admin');
        
        $packages = $this->packageModel->findPending();
        return $packages;
    }
}

