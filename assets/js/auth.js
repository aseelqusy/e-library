let currentStep = 1;

function goStep(step) {
    const target = document.getElementById('fields-' + step);
    if (!target) return;

    document.querySelectorAll('.step-fields').forEach((f) => f.classList.remove('active'));
    target.classList.add('active');

    for (let i = 1; i <= 3; i++) {
        const c = document.getElementById('step-c' + i);
        if (!c) continue;
        c.classList.remove('active', 'done');
        if (i < step) {
            c.classList.add('done');
            c.textContent = '✓';
        } else if (i === step) {
            c.classList.add('active');
            c.textContent = i;
        } else {
            c.textContent = i;
        }
    }

    for (let i = 1; i <= 2; i++) {
        const l = document.getElementById('step-l' + i);
        if (l) l.classList.toggle('done', i < step);
    }

    const socialSection = document.getElementById('social-section');
    if (socialSection) {
        socialSection.style.display = step === 1 ? '' : 'none';
    }

    currentStep = step;
}

function validateField(input) {
    input.classList.toggle('valid', input.value.trim().length >= 2);
}

function validateEmail(input) {
    const ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
    input.classList.toggle('valid', ok);
}

function validateConfirm(input) {
    const passwordInput = document.getElementById('password');
    if (!passwordInput) return;
    const pw = passwordInput.value;
    input.classList.toggle('valid', input.value === pw && pw.length > 0);
}

function togglePw(id, btn) {
    const inp = document.getElementById(id);
    if (!inp) return;
    if (inp.type === 'password') {
        inp.type = 'text';
        btn.textContent = '🙈';
    } else {
        inp.type = 'password';
        btn.textContent = '👁️';
    }
}

function checkStrength(pw) {
    let score = 0;
    if (pw.length >= 8) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;

    const colors = ['', '#ef4444', '#f59e0b', '#10b981', '#7c3aed'];
    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong 💪'];

    for (let i = 1; i <= 4; i++) {
        const seg = document.getElementById('s' + i);
        if (seg) seg.style.background = i <= score ? colors[score] : 'var(--border)';
    }

    const label = document.getElementById('strength-label');
    if (label) {
        label.textContent = pw.length > 0 ? labels[score] : 'Password strength';
        label.style.color = colors[score] || 'var(--muted)';
    }
}

function submitForm() {
    const termsCheck = document.getElementById('terms-check');
    if (!termsCheck) return;

    const checked = termsCheck.classList.contains('checked');
    if (!checked) {
        termsCheck.style.borderColor = '#ef4444';
        setTimeout(() => {
            termsCheck.style.borderColor = '';
        }, 1500);
        return;
    }

    document.querySelectorAll('.step-fields').forEach((f) => {
        f.style.display = 'none';
    });

    const steps = document.querySelector('.steps');
    if (steps) steps.style.display = 'none';

    const loginRedirect = document.getElementById('login-redirect');
    if (loginRedirect) loginRedirect.style.display = 'none';

    const successScreen = document.getElementById('success-screen');
    if (successScreen) successScreen.style.display = 'block';
}

function initRegisterPage() {
    if (document.body.dataset.registerInitDone === 'true') return;
    document.body.dataset.registerInitDone = 'true';

    // Input validators.
    document.querySelectorAll('.js-validate-field').forEach((input) => {
        input.addEventListener('input', () => validateField(input));
    });

    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', () => validateEmail(emailInput));
    }

    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', () => checkStrength(passwordInput.value));
    }

    const confirmInput = document.getElementById('confirm-pw');
    if (confirmInput) {
        confirmInput.addEventListener('input', () => validateConfirm(confirmInput));
    }

    // Password visibility toggles.
    document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
        btn.addEventListener('click', () => {
            togglePw(btn.dataset.togglePassword, btn);
        });
    });

    // Step navigation buttons.
    document.querySelectorAll('[data-go-step]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const step = Number(btn.dataset.goStep);
            if (Number.isInteger(step)) goStep(step);
        });
    });

    const submitBtn = document.querySelector('[data-submit-form="true"]');
    if (submitBtn) {
        submitBtn.addEventListener('click', () => submitForm());
    }

    // Custom terms checkbox toggle.
    const termsCheck = document.getElementById('terms-check');
    if (termsCheck) {
        termsCheck.addEventListener('click', () => {
            termsCheck.classList.toggle('checked');
        });
    }

    // Preserve old select focus/blur styling behavior.
    const heardAbout = document.getElementById('heard-about');
    if (heardAbout) {
        heardAbout.addEventListener('focus', () => {
            heardAbout.style.borderColor = 'var(--purple)';
            heardAbout.style.boxShadow = '0 0 0 3px #ede9fe';
        });
        heardAbout.addEventListener('blur', () => {
            heardAbout.style.borderColor = 'var(--border)';
            heardAbout.style.boxShadow = '';
        });
    }

    const successCta = document.getElementById('success-start-reading');
    if (successCta) {
        successCta.addEventListener('click', () => {
            window.location.href = '#';
        });
    }

    // Animate dots rotation.
    const dots = document.querySelectorAll('.panel-dot');
    if (dots.length > 0) {
        let dotIdx = 0;
        setInterval(() => {
            dots.forEach((d) => d.classList.remove('active'));
            dotIdx = (dotIdx + 1) % dots.length;
            dots[dotIdx].classList.add('active');
        }, 3000);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRegisterPage);
} else {
    initRegisterPage();
}
