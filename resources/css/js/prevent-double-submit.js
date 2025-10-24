/**
 * منع Double Submit للنماذج
 * ضع هذا الكود في ملف public/js/prevent-double-submit.js
 */

document.addEventListener('DOMContentLoaded', function() {

    // ========================================
    // 1. منع Double Submit للنماذج العادية
    // ========================================
    const forms = document.querySelectorAll('form[data-prevent-double-submit]');

    forms.forEach(form => {
        let isSubmitting = false;

        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                console.warn('⚠️ Form already submitting, prevented double submit');
                return false;
            }

            isSubmitting = true;

            // تعطيل زر الإرسال
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;

                // حفظ النص الأصلي
                const originalText = btn.innerHTML || btn.value;
                btn.dataset.originalText = originalText;

                // تغيير النص
                if (btn.tagName === 'BUTTON') {
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري الإرسال...';
                } else {
                    btn.value = 'جاري الإرسال...';
                }
            });

            // إعادة التفعيل بعد 5 ثواني (Safety)
            setTimeout(() => {
                isSubmitting = false;
                submitButtons.forEach(btn => {
                    btn.disabled = false;
                    if (btn.tagName === 'BUTTON') {
                        btn.innerHTML = btn.dataset.originalText;
                    } else {
                        btn.value = btn.dataset.originalText;
                    }
                });
            }, 5000);
        });
    });

    // ========================================
    // 2. منع Double Click على الأزرار
    // ========================================
    const buttons = document.querySelectorAll('[data-prevent-double-click]');

    buttons.forEach(button => {
        let isClicked = false;

        button.addEventListener('click', function(e) {
            if (isClicked) {
                e.preventDefault();
                e.stopPropagation();
                console.warn('⚠️ Button already clicked, prevented double click');
                return false;
            }

            isClicked = true;
            button.disabled = true;

            // إعادة التفعيل بعد 3 ثواني
            setTimeout(() => {
                isClicked = false;
                button.disabled = false;
            }, 3000);
        });
    });

    // ========================================
    // 3. منع Double Submit للـ AJAX
    // ========================================
    window.preventDoubleAjax = (function() {
        const pendingRequests = new Map();

        return {
            check: function(key) {
                if (pendingRequests.has(key)) {
                    console.warn('⚠️ Request already pending:', key);
                    return false;
                }
                pendingRequests.set(key, Date.now());
                return true;
            },

            clear: function(key) {
                pendingRequests.delete(key);
            },

            clearAll: function() {
                pendingRequests.clear();
            }
        };
    })();

    // ========================================
    // 4. حماية نماذج الحجز بشكل خاص
    // ========================================
    const bookingForms = document.querySelectorAll('form[action*="bookings"]');

    bookingForms.forEach(form => {
        const bookingKey = 'booking_' + Date.now();
        let submitted = false;

        form.addEventListener('submit', function(e) {
            if (submitted) {
                e.preventDefault();
                alert('⚠️ تم إرسال الحجز بالفعل، يرجى الانتظار...');
                return false;
            }

            submitted = true;

            // تعطيل كل الحقول
            const inputs = form.querySelectorAll('input, select, textarea, button');
            inputs.forEach(input => input.disabled = true);

            // إضافة spinner
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    جاري معالجة الحجز...
                `;
            }

            // Safety: إعادة التفعيل بعد 10 ثواني
            setTimeout(() => {
                submitted = false;
                inputs.forEach(input => input.disabled = false);
                if (submitBtn) {
                    submitBtn.innerHTML = submitBtn.dataset.originalText || 'إرسال';
                }
            }, 10000);
        });
    });

    // ========================================
    // 5. منع الضغط على Enter مرات متعددة
    // ========================================
    let lastEnterTime = 0;
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            const now = Date.now();
            if (now - lastEnterTime < 1000) {
                e.preventDefault();
                console.warn('⚠️ Enter key pressed too quickly');
                return false;
            }
            lastEnterTime = now;
        }
    });

    console.log('✅ Double Submit Prevention Activated');
});

// ========================================
// دالة عامة لمنع Double Submit في AJAX
// ========================================
function submitBookingForm(formElement, callback) {
    const formData = new FormData(formElement);
    const url = formElement.action;
    const requestKey = 'booking_' + url;

    // التحقق من وجود طلب سابق
    if (!window.preventDoubleAjax.check(requestKey)) {
        alert('⚠️ يتم معالجة الطلب، يرجى الانتظار...');
        return;
    }

    // تعطيل الزر
    const submitBtn = formElement.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.dataset.originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> جاري الإرسال...';
    }

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        window.preventDoubleAjax.clear(requestKey);

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtn.dataset.originalText;
        }

        if (callback) callback(data);
    })
    .catch(error => {
        window.preventDoubleAjax.clear(requestKey);

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = submitBtn.dataset.originalText;
        }

        console.error('Error:', error);
        alert('حدث خطأ أثناء الإرسال');
    });
}
