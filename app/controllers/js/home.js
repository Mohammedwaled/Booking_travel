        // 1. UML: User Session Logic (محاكاة تسجيل الدخول)
      function simulateLogin() {
    const userEmail = prompt("ادخل البريد الإلكتروني:");
    const password = prompt("ادخل كلمة المرور:");

    if (userEmail && password) {
        if (userEmail.includes("@") && password.length >= 6) {
            document.getElementById('authSection').style.display = 'none';
            document.getElementById('userProfile').style.display = 'flex';
            alert("أهلاً بك مجدداً يا أحمد!");
        } else {
            alert("خطأ: تأكد من كتابة إيميل صحيح وكلمة مرور لا تقل عن 6 رموز");
        }
    }
}

        function simulateLogout() {
            if(confirm("Do you want to logout?")) {
                document.getElementById('authSection').style.display = 'flex';
                document.getElementById('userProfile').style.display = 'none';
            }
        }

        // 2. UML: Wishlist Logic (إضافة للمفضلة)
        function toggleWishlist(element) {
            element.classList.toggle('active');
            const icon = element.querySelector('i');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');
            // هنا يتم إرسال طلب للـ Database في النظام الحقيقي
        }

        // 3. UML: Filter Logic (نظام الفلترة)
document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.cat-item').forEach(item => {
            item.addEventListener('click', () => {
                // تغيير الـ UI
            const activeItem = document.querySelector('.cat-item.active');
            if (activeItem) {
                activeItem.classList.remove('active');
            }
                item.classList.add('active');

                // الفلترة الفعلية للكروت
                const selectedCat = item.getAttribute('data-cat');
                document.querySelectorAll('.tour-card').forEach(card => {
                    if (selectedCat === 'all' || card.getAttribute('data-category') === selectedCat) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
            });
                });
            });
        });

        // 4. UML: Search Logic
window.handleSearch = function() {
    console.log('handleSearch called');
    const destInput = document.getElementById('destInput');
    if (!destInput) {
        console.error('destInput not found');
        alert('خطأ: لم يتم العثور على حقل البحث / Error: Search field not found');
        return;
    }
    const dest = destInput.value.trim();
    const dateInput = document.getElementById('travelDate');
    const date = dateInput ? dateInput.value : '';
    const travelersInput = document.getElementById('travelerCount');
    const travelers = travelersInput ? parseInt(travelersInput.value, 10) : 1;
    
    console.log('Search params:', { dest, date, travelers });

    // التحقق من التاريخ (يجب أن يكون على الأقل 2026)
    if (date) {
        const selectedDate = new Date(date);
        const minDate = new Date('2026-01-01');
        if (selectedDate < minDate) {
            alert("التاريخ يجب أن يكون 2026 أو بعد ذلك / Date must be 2026 or later");
            return;
        }
    }

    // التحقق من عدد المسافرين
    if (isNaN(travelers) || travelers < 1) {
        alert("عدد المسافرين يجب أن يكون 1 على الأقل / Number of travelers must be at least 1");
        return;
    }

    // إذا كان هناك وجهة مكتوبة، تحقق من أسماء المدن
    if (dest) {
    // منع البحث برقم فقط
    if (/^\d+$/.test(dest)) {
        destInput.style.border = "2px solid #e74c3c";
            alert("الوجهة لا يمكن أن تكون أرقام فقط / Destination cannot be numbers only");
            return;
        }

        const destLower = dest.toLowerCase();
        
        // التحقق من أسماء المدن وتوجيه مباشر لصفحة التفاصيل
        if (destLower.includes('قاهرة') || destLower.includes('cairo') || destLower === 'القاهرة' || destLower === 'قاهرة') {
            window.location.href = 'cairo-details.html';
            return;
        } else if (destLower.includes('جيزة') || destLower.includes('giza') || destLower === 'الجيزة' || destLower === 'جيزة') {
            window.location.href = 'details-giza.html';
            return;
        } else if (destLower.includes('إسكندرية') || destLower.includes('alexandria') || destLower === 'الإسكندرية' || destLower === 'اسكندرية' || destLower === 'إسكندرية') {
            window.location.href = 'details-alex.html';
            return;
        } else if (destLower.includes('دهب') || destLower.includes('dahab')) {
            window.location.href = 'details-dahab.html';
            return;
        } else if (destLower.includes('أقصر') || destLower.includes('luxor') || destLower === 'الأقصر' || destLower === 'أقصر') {
            window.location.href = 'details-luxor.html';
            return;
        }
    }

    destInput.style.border = "";
    
    // إذا لم يكن هناك وجهة، اعرض جميع الرحلات
    if (!dest) {
        if (!date) {
            alert("برجاء اختيار تاريخ الرحلة / Please select a travel date");
            return;
        }
        // جلب جميع الرحلات المتاحة
        fetch('../app/controllers/public/index.php/api/packages')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Packages data:', data);
                if (data && data.length > 0) {
                    displaySearchResults(data);
                    alert(`تم العثور على ${data.length} رحلة متاحة / Found ${data.length} available trips`);
                } else {
                    alert('لا توجد رحلات متاحة حالياً / No trips available at the moment');
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                alert('حدث خطأ أثناء البحث / Error occurred during search');
            });
        return;
    }

    // البحث في قاعدة البيانات
    console.log('Searching for:', dest);
    fetch('../app/controllers/public/index.php/api/search?q=' + encodeURIComponent(dest))
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Search results:', data);
            // التحقق من أن البيانات ليست رسالة خطأ
            if (data.error) {
                alert('لا توجد رحلات في هذا الموقع / No trips available at this location');
                return;
            }
            
            if (data && Array.isArray(data) && data.length > 0) {
                displaySearchResults(data);
                alert(`تم العثور على ${data.length} رحلة متاحة / Found ${data.length} available trips`);
            } else {
                alert('لا توجد رحلات في هذا الموقع / No trips available at this location');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            alert('لا توجد رحلات في هذا الموقع / No trips available at this location');
        });
};

// دالة لعرض نتائج البحث
function displaySearchResults(data) {
    console.log('Displaying search results:', data);
    const resultsContainer = document.getElementById('toursGrid');
    if (!resultsContainer) {
        console.error('toursGrid container not found');
        alert('خطأ: لم يتم العثور على حاوية النتائج / Error: Results container not found');
        return;
    }

    resultsContainer.innerHTML = '';
    
    if (!Array.isArray(data) || data.length === 0) {
        resultsContainer.innerHTML = '<p style="text-align: center; padding: 20px;">لا توجد نتائج / No results found</p>';
        return;
    }

    data.forEach(item => {
        const card = document.createElement('div');
        card.className = 'tour-card';
        card.setAttribute('data-category', 'all');
        
        // تحديد رابط التفاصيل حسب المدينة
        let detailLink = 'cairo-details.html';
        const titleLower = (item.title || '').toLowerCase();
        if (titleLower.includes('أقصر') || titleLower.includes('luxor')) {
            detailLink = 'details-luxor.html';
        } else if (titleLower.includes('جيزة') || titleLower.includes('giza')) {
            detailLink = 'details-giza.html';
        } else if (titleLower.includes('دهب') || titleLower.includes('dahab')) {
            detailLink = 'details-dahab.html';
        } else if (titleLower.includes('إسكندرية') || titleLower.includes('alexandria')) {
            detailLink = 'details-alex.html';
        }
        
        // تحديد الصورة
        let imgName = 'giza';
        if (titleLower.includes('قاهرة') || titleLower.includes('cairo')) {
            imgName = 'cairo';
        } else if (titleLower.includes('أقصر') || titleLower.includes('luxor')) {
            imgName = 'luxor';
        } else if (titleLower.includes('جيزة') || titleLower.includes('giza')) {
            imgName = 'giza';
        } else if (titleLower.includes('دهب') || titleLower.includes('dahab')) {
            imgName = 'dahab';
        } else if (titleLower.includes('إسكندرية') || titleLower.includes('alexandria')) {
            imgName = 'alex';
        }
        
        const currency = localStorage.getItem('selectedCurrency') || 'EGP';
        const priceNum = parseFloat(item.price || 0);
        const symbol = currency === 'USD' ? '$' : 'ج.م';
        const convertedPrice = currency === 'USD' ? (priceNum / 50).toFixed(2) : priceNum;
        
        card.innerHTML = `
            <div class="wishlist-heart" onclick="toggleWishlist(this)"><i class="far fa-heart"></i></div>
            <div class="img-container">
                <img src="assets/img/${imgName}.jpeg" alt="${item.title || 'رحلة'}" onerror="this.src='assets/img/giza.jpeg'">
            </div>
            <div class="card-content">
                <div class="rating"><i class="fas fa-star"></i> 4.8</div>
                <h3>${item.title || 'رحلة سياحية'}</h3>
                <p style="color:#777; font-size: 0.85rem;">${(item.details || item.title || '').substring(0, 80)}...</p>
                <div class="card-footer">
                    <span class="price-tag" data-price-egp="${priceNum}">${convertedPrice} ${symbol}</span>
                    <a href="${detailLink}" class="details-link">استكشف المعالم <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        `;
        resultsContainer.appendChild(card);
    });
    
    console.log('Results displayed successfully');
}
  