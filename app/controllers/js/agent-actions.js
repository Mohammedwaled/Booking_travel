// assets/js/agent-actions.js

// 1. معالجة الفورم عند الإرسال
document.getElementById('uploadPackageForm').onsubmit = function(e) {
    e.preventDefault();
    alert("تم إرسال العرض للأدمن. سيظهر للجمهور بمجرد الموافقة عليه.");
    this.reset();
};

// 2. محاكاة الإشعارات
function simulateNewBooking() {
    setTimeout(() => {
        const notifBadge = document.getElementById('notifBadge');
        const bookingCount = document.getElementById('bookingCount');
        
        if(notifBadge) notifBadge.innerText = "1";
        if(bookingCount) bookingCount.innerText = "1";
        
        alert("🔔 إشعار جديد: عميل قام بحجز عرضك!");
    }, 5000); 
}

simulateNewBooking();