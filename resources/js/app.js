import './bootstrap';

const passwordToggle = document.querySelectorAll('[data-password-toggle]');
passwordToggle.forEach(toggle => {
    toggle.addEventListener('click', (e) => {
        e.preventDefault();
        const passwordInput = document.querySelector(toggle.getAttribute('data-password-toggle'));
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        toggle.querySelector('i').classList.toggle('ti-eye', passwordInput.type === 'password');
        toggle.querySelector('i').classList.toggle('ti-eye-off', passwordInput.type === 'text');
    });
});
