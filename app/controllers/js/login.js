
        document.getElementById('bookingLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // جلب القيم
            const name = document.getElementById('userName').value;
            const email = document.getElementById('userEmail').value;
            const pass = document.getElementById('userPass').value;

            // ضبط رسائل الخطأ للوضع الافتراضي
            document.querySelectorAll('.error-msg').forEach(el => el.style.display = 'none');

            let isValid = true;

            // 1. فحص الاسم (يمنع الأرقام)
            const nameRegex = /^[a-zA-Z\s\u0600-\u06FF]+$/; // يدعم العربي والإنجليزي حروف فقط
            if (!nameRegex.test(name)) {
                document.getElementById('nameError').style.display = 'block';
                isValid = false;
            }

            // 2. فحص الإيميل (وجود @)
            if (!email.includes('@')) {
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }

            // 3. فحص الباسورد (أقل من 8 حروف)
            if (pass.length < 8) {
                document.getElementById('passError').style.display = 'block';
                isValid = false;
            }

            // النتيجة النهائية
            if (isValid) {
                alert("✅ تم التحقق بنجاح! جاري الانتقال لصفحة الحجوزات...");
                // هنا ممكن تضيف كود الـ fetch بتاع الـ PHP لو حبيت
            }
        });
    