let currentStep = 1;

function goStep(step) {
    // Hide all step fields
    document.querySelectorAll('.step-fields').forEach(f => f.classList.remove('active'));
    document.getElementById('fields-' + step).classList.add('active');

    // Update step circles
    for (let i = 1; i <= 3; i++) {
        const c = document.getElementById('step-c' + i);
        c.classList.remove('active', 'done');
        if (i < step)       { c.classList.add('done'); c.textContent = '✓'; }
        else if (i === step) { c.classList.add('active'); c.textContent = i; }
        else                 { c.textContent = i; }
    }
    for (let i = 1; i <= 2; i++) {
        const l = document.getElementById('step-l' + i);
        l.classList.toggle('done', i < step);
    }

    // Hide social buttons on steps > 1
    document.getElementById('social-section').style.display = step === 1 ? '' : 'none';
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
    const pw = document.getElementById('password').value;
    input.classList.toggle('valid', input.value === pw && pw.length > 0);
}

function togglePw(id, btn) {
    const inp = document.getElementById(id);
    if (inp.type === 'password') { inp.type = 'text'; btn.textContent = '🙈'; }
    else { inp.type = 'password'; btn.textContent = '👁️'; }
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
        seg.style.background = i <= score ? colors[score] : 'var(--border)';
    }
    document.getElementById('strength-label').textContent = pw.length > 0 ? labels[score] : 'Password strength';
    document.getElementById('strength-label').style.color = colors[score] || 'var(--muted)';
}

function submitForm() {
    const checked = document.getElementById('terms-check').classList.contains('checked');
    if (!checked) {
        document.getElementById('terms-check').style.borderColor = '#ef4444';
        setTimeout(() => document.getElementById('terms-check').style.borderColor = '', 1500);
        return;
    }
    // Show success
    document.querySelectorAll('.step-fields').forEach(f => f.style.display = 'none');
    document.querySelector('.steps').style.display = 'none';
    document.getElementById('login-redirect').style.display = 'none';
    document.getElementById('success-screen').style.display = 'block';
}

// Animate dots rotation
let dotIdx = 0;
const dots = document.querySelectorAll('.panel-dot');
setInterval(() => {
    dots.forEach(d => d.classList.remove('active'));
    dotIdx = (dotIdx + 1) % dots.length;
    dots[dotIdx].classList.add('active');
}, 3000);
