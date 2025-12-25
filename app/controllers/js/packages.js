// JavaScript منفصل لجلب وعرض العروض السياحية

function loadPackages() {
    fetch('../app/controllers/public/index.php/api/packages')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('packages-container');
            if (!container) return;
            
            container.innerHTML = ''; // مسح أي بيانات قديمة

            if (data.length === 0) {
                container.innerHTML = '<p>لا توجد رحلات متاحة حالياً.</p>';
                return;
            }

            data.forEach(pkg => {
                // رسم الكارت الخاص بكل رحلة
                container.innerHTML += `
                    <div class="package-card">
                        <h3>${pkg.title}</h3>
                        <p>الوكيل: ${pkg.agent_name}</p>
                        <p class="price" data-price-egp="${pkg.price}">السعر: ${formatPrice(pkg.price, localStorage.getItem('selectedCurrency') || 'EGP')}</p>
                        <a href="package-details.html?id=${pkg.id}" class="details-btn">عرض التفاصيل</a>
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error('Error loading packages:', error);
            const container = document.getElementById('packages-container');
            if (container) {
                container.innerHTML = '<p>حدث خطأ في تحميل البيانات.</p>';
            }
        });
}

// تشغيل الوظيفة أول ما الصفحة تحمل
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadPackages);
} else {
    loadPackages();
}

