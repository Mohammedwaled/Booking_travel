// JavaScript منفصل لنماذج تسجيل الدخول

function toggleAuth() {
    const loginSec = document.getElementById('login-section');
    const registerSec = document.getElementById('register-section');
    
    if (loginSec && registerSec) {
        loginSec.classList.toggle('hidden');
        registerSec.classList.toggle('hidden');
    }
}

// اظهار تب تسجيل الدخول مباشرة عند استدعاء الصفحة بـ #login
if (window.location.hash === '#login') {
    toggleAuth();
}

