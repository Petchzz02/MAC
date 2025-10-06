/*
   JavaScript สำหรับหน้า login.php (เวอร์ชันเรียบง่าย)
   แก้ไขปัญหาการไม่สามารถกรอกรหัสผ่านได้
*/

document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Login page loaded - Simple version');

    // ตัวแปรสำหรับ elements หลัก
    const form = document.querySelector('.js-login-form');
    const inputs = document.querySelectorAll('.form-control');
    const button = form ? form.querySelector('.btn-primary') : null;
    const employeeIdInput = document.getElementById('employee_id');
    const passwordInput = document.getElementById('password');

    // ตรวจสอบว่าพบ elements หรือไม่
    console.log('Found elements:', {
        form: !!form,
        inputs: inputs.length,
        button: !!button,
        employeeId: !!employeeIdInput,
        password: !!passwordInput
    });

    // =========== เอฟเฟ็กต์พื้นฐานสำหรับ Input Fields =========== 
    inputs.forEach((input, index) => {
        console.log(`Setting up input ${index}: ${input.id}`);
        
        // เอฟเฟ็กต์ focus/blur
        input.addEventListener('focus', function () {
            console.log('Focus on:', this.id);
            this.parentElement.style.transform = 'translateY(-2px)';
            this.classList.add('focus-shadow');
        });
        
        input.addEventListener('blur', function () {
            console.log('Blur on:', this.id);
            this.parentElement.style.transform = 'translateY(0)';
            this.classList.remove('focus-shadow');
        });

        // ลบ error state เมื่อพิมพ์
        input.addEventListener('input', function() {
            console.log('Input event on:', this.id, 'Value length:', this.value.length);
            this.classList.remove('is-invalid');
        });

        // Navigation ด้วย Enter key
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                console.log('Enter key pressed on:', this.id);
                e.preventDefault();
                
                if (index < inputs.length - 1) {
                    // ไปยัง input ถัดไป
                    inputs[index + 1].focus();
                } else {
                    // Submit form
                    if (button && !button.disabled) {
                        form.submit();
                    }
                }
            }
        });
    });

    // =========== การจัดการ Form Submission ===========
    if (form && button) {
        let isSubmitting = false;
        
        form.addEventListener('submit', function (e) {
            console.log('Form submit event triggered');
            
            // ป้องกัน double submit
            if (isSubmitting) {
                console.log('Already submitting, preventing duplicate');
                e.preventDefault();
                return false;
            }

            // ตรวจสอบ validation
            let isValid = true;
            const employeeId = employeeIdInput ? employeeIdInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value : '';

            console.log('Validation check:', { employeeId, passwordLength: password.length });

            if (!employeeId || !password) {
                console.log('Validation failed - empty fields');
                isValid = false;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        console.log('Invalid field:', input.id);
                    }
                });
                
                // แสดง alert
                alert('กรุณากรอกรหัสพนักงานและรหัสผ่านให้ครบถ้วน');
            }

            if (!isValid) {
                e.preventDefault();
                return false;
            }

            // เริ่ม loading state
            isSubmitting = true;
            console.log('Starting loading state');
            
            button.innerHTML = '<span style="display: inline-block; width: 16px; height: 16px; border: 2px solid #ffffff; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></span> กำลังเข้าสู่ระบบ...';
            button.disabled = true;
            
            // Timeout fallback
            setTimeout(() => {
                if (isSubmitting) {
                    console.log('Timeout reached, resetting form');
                    resetForm();
                }
            }, 10000);
        });
        
        function resetForm() {
            isSubmitting = false;
            button.innerHTML = 'เข้าสู่ระบบ';
            button.disabled = false;
            console.log('Form reset completed');
        }
    }

    // =========== Auto-focus และ localStorage ===========
    if (employeeIdInput) {
        // โหลดรหัสพนักงานล่าสุด
        const savedEmployeeId = localStorage.getItem('lastEmployeeId');
        if (savedEmployeeId) {
            employeeIdInput.value = savedEmployeeId;
            console.log('Loaded saved employee ID:', savedEmployeeId);
        }

        // บันทึกรหัสพนักงาน
        employeeIdInput.addEventListener('input', function() {
            localStorage.setItem('lastEmployeeId', this.value);
        });

        // Auto focus
        setTimeout(() => {
            if (!employeeIdInput.value) {
                employeeIdInput.focus();
                console.log('Auto focused on employee ID input');
            } else if (passwordInput) {
                passwordInput.focus();
                console.log('Auto focused on password input');
            }
        }, 500);
    }

    // =========== ตรวจสอบ Error Parameters ===========
    const params = new URLSearchParams(window.location.search);
    if (params.has('error')) {
        const errorCode = params.get('error');
        console.log('Error parameter found:', errorCode);
        
        // เพิ่มเอฟเฟ็กต์ shake
        setTimeout(() => {
            inputs.forEach(input => {
                input.classList.add('is-invalid');
                input.style.animation = 'shake 0.5s ease-in-out';
            });
            
            setTimeout(() => {
                inputs.forEach(input => {
                    input.classList.remove('is-invalid');
                    input.style.animation = '';
                });
            }, 500);
        }, 300);
    }

    // =========== Debug Information ===========
    console.log('📱 Setup completed:', {
        formFound: !!form,
        inputCount: inputs.length,
        buttonFound: !!button,
        employeeIdFound: !!employeeIdInput,
        passwordFound: !!passwordInput
    });

    // Test input functionality
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            console.log('Password input working, length:', this.value.length);
        });
    }
});

// CSS Animation สำหรับ loading spinner
const style = document.createElement('style');
style.textContent = `
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.focus-shadow {
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
}

.is-invalid {
    border-color: #dc3545 !important;
}
`;
document.head.appendChild(style);
