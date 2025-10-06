document.addEventListener('DOMContentLoaded', function() {
    // Animation for form inputs
    const inputs = document.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Form submission animation
    const form = document.querySelector('form');
    const button = document.querySelector('.btn-primary');
    
    form.addEventListener('submit', function() {
        button.style.width = '50px';
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    });
});