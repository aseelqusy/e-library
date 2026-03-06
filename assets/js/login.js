// Login page functionality

function toggleLoginPassword(event) {
    event.preventDefault();
    const input = document.getElementById('login-password');
    const btn = event.currentTarget;
    if (!input || !btn) return;

    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁️';
    }
}

function initLoginPage() {
    // Avoid double-binding if script is loaded more than once.
    if (document.body.dataset.loginInitDone === 'true') return;
    document.body.dataset.loginInitDone = 'true';

    // Toggle password visibility.
    document.querySelectorAll('[data-login-toggle="true"]').forEach((btn) => {
        btn.addEventListener('click', toggleLoginPassword);
    });

    // Remember-me custom checkbox toggle.
    const rememberCheck = document.getElementById('remember-check');
    if (rememberCheck) {
        rememberCheck.addEventListener('click', () => {
            rememberCheck.classList.toggle('checked');
        });
    }

    // Animate dots rotation.
    let dotIdx = 1;
    const dots = document.querySelectorAll('.panel-dot');
    if (dots.length > 0) {
        setInterval(() => {
            dots.forEach((d) => d.classList.remove('active'));
            dotIdx = (dotIdx + 1) % dots.length;
            dots[dotIdx].classList.add('active');
        }, 3000);
    }

    // Add input focus animations.
    document.querySelectorAll('.input-wrap input').forEach((input) => {
        input.addEventListener('focus', function () {
            const wrap = this.closest('.input-wrap');
            if (!wrap) return;
            wrap.style.transform = 'scale(1.01)';
            wrap.style.transition = 'transform 0.2s ease';
        });

        input.addEventListener('blur', function () {
            const wrap = this.closest('.input-wrap');
            if (!wrap) return;
            wrap.style.transform = 'scale(1)';
        });
    });

    // Form submission (placeholder logic).
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = this.querySelector('.submit-btn');
            if (!btn) return;

            const originalContent = btn.innerHTML;
            btn.innerHTML = '<span>⏳ Logging in...</span>';
            btn.disabled = true;

            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.disabled = false;
                alert('Login form submitted! Connect this to your backend.');
            }, 1500);
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoginPage);
} else {
    initLoginPage();
}
