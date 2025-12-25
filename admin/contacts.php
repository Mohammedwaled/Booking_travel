<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/regester.html#login");
    exit;
}

require_once __DIR__ . '/../app/controllers/php/Contact.php';

$contactModel = new Contact();

// تحديث حالة الرسالة
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if (in_array($action, ['read', 'replied'])) {
        $contactModel->updateStatus($id, $action);
        header("Location: contacts.php");
        exit();
    }
}

// جلب جميع الرسائل
$contacts = $contactModel->getAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الرسائل | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../views/css/admin-style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { padding: 12px; text-align: right; border: 1px solid #ddd; }
        table th { background: var(--primary); color: white; }
        .status-new { background: #e8f5e9; color: #2e7d32; padding: 5px 10px; border-radius: 5px; }
        .status-read { background: #fff3e0; color: #e65100; padding: 5px 10px; border-radius: 5px; }
        .status-replied { background: #e3f2fd; color: #1565c0; padding: 5px 10px; border-radius: 5px; }
        .action-link { padding: 5px 10px; margin: 0 3px; text-decoration: none; border-radius: 5px; color: white; }
        .mark-read { background: #ff9800; }
        .mark-replied { background: #2196f3; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>إدارة النظام</h2>
    <a href="super admin.php" class="nav-item"><i class="fas fa-box"></i> الطلبات الجديدة</a>
    <a href="contacts.php" class="nav-item active"><i class="fas fa-envelope"></i> الرسائل الواردة</a>
    <a href="../views/index.html" class="nav-item"><i class="fas fa-home"></i> العودة للموقع</a>
    <a href="../auth.php?logout=1" class="nav-item" style="color:var(--danger)"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
</div>

<div class="content" style="margin-right: 280px; padding: 40px;">
    <h1>الرسائل الواردة 📧</h1>
    
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الموضوع</th>
                    <th>الرسالة</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>الإجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 30px;">
                            لا توجد رسائل واردة حالياً
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($contacts as $contact): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contact['name']); ?></td>
                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                        <td><?php echo htmlspecialchars($contact['subject'] ?? 'بدون موضوع'); ?></td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo htmlspecialchars(substr($contact['message'], 0, 100)) . '...'; ?>
                        </td>
                        <td><?php echo date('Y-m-d H:i', strtotime($contact['created_at'])); ?></td>
                        <td>
                            <?php 
                            $statusClass = 'status-' . $contact['status'];
                            $statusText = $contact['status'] == 'new' ? 'جديدة' : ($contact['status'] == 'read' ? 'مقروءة' : 'تم الرد');
                            ?>
                            <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                        </td>
                        <td>
                            <?php if ($contact['status'] == 'new'): ?>
                                <a href="contacts.php?id=<?php echo $contact['id']; ?>&action=read" class="action-link mark-read">✅ قراءة</a>
                            <?php endif; ?>
                            <a href="contacts.php?id=<?php echo $contact['id']; ?>&action=replied" class="action-link mark-replied">📧 تم الرد</a>
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


