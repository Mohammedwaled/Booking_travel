<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/regester.html#login");
    exit;
}

require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/controllers/php/Package.php';

$packageModel = new Package();

// جلب الطلبات المنتظرة
$packages = $packageModel->findPending();

// كود معالجة الموافقة أو الرفض لو ضغط على زرار
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if (in_array($action, ['approved', 'rejected'])) {
        $packageModel->updateStatus($id, $action);
        header("Location: super admin.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير العام | Super Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../views/css/admin-style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { padding: 12px; text-align: right; border: 1px solid #ddd; }
        table th { background: var(--primary); color: white; }
        table tr:nth-child(even) { background: #f9f9f9; }
        .action-link { 
            display: inline-block; 
            padding: 5px 15px; 
            margin: 0 5px; 
            text-decoration: none; 
            border-radius: 5px; 
            color: white;
        }
        .approve-link { background: #27ae60; }
        .reject-link { background: #e74c3c; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>إدارة النظام</h2>
    <a href="#" class="nav-item active"><i class="fas fa-chart-line"></i> الطلبات الجديدة</a>
    <a href="#" class="nav-item"><i class="fas fa-user-tie"></i> إدارة الوكلاء</a>
    <a href="../views/index.html" class="nav-item"><i class="fas fa-home"></i> العودة للموقع</a>
    <a href="/public/index.php/auth/logout" class="nav-item" style="color:var(--danger)"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content" style="margin-right: 280px; padding: 40px;">
    <h1>مرحباً أيها المدير! 👋</h1>
    
    <div class="table-card">
        <h3>الطلبات المنتظرة للمراجعة</h3>
        <table>
            <thead>
                <tr>
                    <th>الوكيل</th>
                    <th>العرض</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($packages)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px;">
                            لا توجد طلبات منتظرة حالياً
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($packages as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo number_format($row['price'], 2); ?> ج.م</td>
                        <td><span class="badge badge-pending">بانتظار المراجعة</span></td>
                        <td>
                            <a href="super admin.php?id=<?php echo $row['id']; ?>&action=approved" class="action-link approve-link">✅ موافقة</a>
                            <a href="super admin.php?id=<?php echo $row['id']; ?>&action=rejected" class="action-link reject-link">❌ رفض</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
