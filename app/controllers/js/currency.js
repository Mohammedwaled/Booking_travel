// نظام تحويل العملة
// سعر الصرف: 1 USD = 50 EGP (يمكن تحديثه)

const EXCHANGE_RATE = 50; // 1 دولار = 50 جنيه مصري

// دالة لتحويل السعر
function convertPrice(price, fromCurrency, toCurrency) {
    if (!price || isNaN(price)) return 0;
    
    let priceInEGP = parseFloat(price);
    
    // إذا كان السعر بالدولار، حوله للجنيه أولاً
    if (fromCurrency === 'USD') {
        priceInEGP = priceInEGP * EXCHANGE_RATE;
    }
    
    // إذا كان المطلوب بالدولار، حوله من الجنيه
    if (toCurrency === 'USD') {
        return (priceInEGP / EXCHANGE_RATE).toFixed(2);
    }
    
    // إذا كان المطلوب بالجنيه، أرجع القيمة كما هي
    return priceInEGP.toFixed(2);
}

// دالة لتحديث جميع الأسعار في الصفحة
function updateAllPrices(currency) {
    const currencySymbol = currency === 'USD' ? '$' : 'ج.م';
    
    // تحديث جميع عناصر السعر
    document.querySelectorAll('.price-tag, .price, [id*="price"], [class*="price"]').forEach(element => {
        const text = element.innerText || element.textContent;
        const priceMatch = text.match(/(\d+(?:\.\d+)?)/);
        
        if (priceMatch) {
            const originalPrice = parseFloat(priceMatch[1]);
            const convertedPrice = convertPrice(originalPrice, 'EGP', currency);
            element.innerHTML = element.innerHTML.replace(priceMatch[1], convertedPrice);
            element.innerHTML = element.innerHTML.replace('ج.م', currencySymbol);
            element.innerHTML = element.innerHTML.replace('$', currencySymbol);
        }
    });
    
    // تحديث الأسعار في الكروت
    document.querySelectorAll('.card-footer .price-tag, .detail-row span:last-child').forEach(element => {
        const text = element.innerText || element.textContent;
        const priceMatch = text.match(/(\d+(?:\.\d+)?)/);
        
        if (priceMatch) {
            const originalPrice = parseFloat(priceMatch[1]);
            const convertedPrice = convertPrice(originalPrice, 'EGP', currency);
            const newText = text.replace(priceMatch[1], convertedPrice).replace(/ج\.م|\$/, currencySymbol);
            element.textContent = newText;
        }
    });
}

// Event listener على currency selector
document.addEventListener('DOMContentLoaded', function() {
    const currencySelect = document.getElementById('currency');
    
    if (currencySelect) {
        // حفظ العملة المختارة في localStorage
        const savedCurrency = localStorage.getItem('selectedCurrency') || 'EGP';
        currencySelect.value = savedCurrency;
        
        // تحديث الأسعار بعد تحميل الصفحة
        setTimeout(() => {
            updateAllPrices(savedCurrency);
        }, 500);
        
        currencySelect.addEventListener('change', function() {
            const selectedCurrency = this.value;
            localStorage.setItem('selectedCurrency', selectedCurrency);
            updateAllPrices(selectedCurrency);
        });
    } else {
        // إذا لم يكن هناك currency selector، استخدم العملة المحفوظة
        const savedCurrency = localStorage.getItem('selectedCurrency') || 'EGP';
        setTimeout(() => {
            updateAllPrices(savedCurrency);
        }, 500);
    }
});

// دالة عامة لتحويل سعر واحد
function formatPrice(price, currency = 'EGP') {
    const converted = convertPrice(price, 'EGP', currency);
    const symbol = currency === 'USD' ? '$' : 'ج.م';
    return converted + ' ' + symbol;
}

