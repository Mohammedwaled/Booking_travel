// JavaScript منفصل لصفحات تفاصيل المدن

function loadCityInfo(cityId, elementIds) {
    if (!cityId) return;
    
    const { titleId, agentId, priceId, detailsId, pkgId } = elementIds;
    
    // تعيين package_id في hidden input
    if (pkgId) {
        const pkgInput = document.getElementById(pkgId);
        if (pkgInput) {
            pkgInput.value = cityId;
        }
    }

    fetch('../app/controllers/public/index.php/api/cities/' + cityId)
        .then(response => response.json())
        .then(pkg => {
            if (pkg) {
                if (titleId) {
                    const titleEl = document.getElementById(titleId);
                    if (titleEl) {
                        titleEl.innerText = "استكشف " + pkg.title;
                    }
                }
                
                if (agentId) {
                    const agentEl = document.getElementById(agentId);
                    if (agentEl) {
                        agentEl.innerText = pkg.agent_name || 'غير محدد';
                    }
                }
                
                if (priceId) {
                    const priceEl = document.getElementById(priceId);
                    if (priceEl) {
                        const price = pkg.price || 0;
                        const currency = localStorage.getItem('selectedCurrency') || 'EGP';
                        const symbol = currency === 'USD' ? '$' : 'ج.م';
                        const convertedPrice = currency === 'USD' ? (price / 50).toFixed(2) : price;
                        priceEl.innerText = convertedPrice + ' ' + symbol;
                        priceEl.setAttribute('data-price-egp', price); // حفظ السعر الأصلي
                    }
                }
                
                if (detailsId) {
                    const detailsEl = document.getElementById(detailsId);
                    if (detailsEl) {
                        detailsEl.innerText = pkg.details || 'لا توجد تفاصيل متاحة';
                    }
                }
            }
        })
        .catch(err => {
            console.error('Error loading city info:', err);
            const detailsEl = document.getElementById(detailsId);
            if (detailsEl) {
                detailsEl.innerText = "خطأ في تحميل البيانات.";
            }
        });
}

// دالة خاصة بالقاهرة
function loadCairoInfo() {
    loadCityInfo(1, {
        titleId: 'title',
        agentId: 'agent',
        priceId: 'price',
        detailsId: 'details',
        pkgId: 'pkg_id'
    });
}

// دالة عامة لتحميل بيانات المدينة
function loadCityData(cityId, elementIds) {
    loadCityInfo(cityId, elementIds);
}

