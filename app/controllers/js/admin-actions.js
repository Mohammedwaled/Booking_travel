/* assets/js/admin-actions.js */
function approve(btn) {
    if(confirm("هل أنت متأكد من الموافقة؟")) {
        const row = btn.closest('tr');
        row.style.transition = "0.5s";
        row.style.background = "#e8f8f5";
        row.innerHTML = `<td colspan="5" style="text-align:center; color:green; padding:20px;">✅ تمت الموافقة بنجاح</td>`;
        setTimeout(() => row.remove(), 2000);
    }
}

function reject(btn) {
    let reason = prompt("ما هو سبب الرفض؟");
    if(reason) {
        const row = btn.closest('tr');
        row.style.background = "#fdedec";
        row.innerHTML = `<td colspan="5" style="text-align:center; color:red; padding:20px;">❌ تم الرفض: ${reason}</td>`;
        setTimeout(() => row.remove(), 2000);
    }
}