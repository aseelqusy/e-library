// Login page functionality

// Toggle password visibility
function toggleLoginPassword(event) {
    event.preventDefault();
    const input = document.getElementById('login-password');
    const btn = event.currentTarget;

    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁️';
    }
}

// Animate dots rotation
let dotIdx = 1; // Start at index 1 (second dot is active for login page)
const dots = document.querySelectorAll('.panel-dot');
if (dots.length > 0) {
    setInterval(() => {
        dots.forEach(d => d.classList.remove('active'));
        dotIdx = (dotIdx + 1) % dots.length;
        dots[dotIdx].classList.add('active');
    }, 3000);
}

// Add input focus animations
document.querySelectorAll('.input-wrap input').forEach(input => {
    input.addEventListener('focus', function() {
        this.closest('.input-wrap').style.transform = 'scale(1.01)';
        this.closest('.input-wrap').style.transition = 'transform 0.2s ease';
    });

    input.addEventListener('blur', function() {
        this.closest('.input-wrap').style.transform = 'scale(1)';
    });
});

// Form submission (you can add backend logic later)
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Add loading state
        const btn = this.querySelector('.submit-btn');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span>⏳ Logging in...</span>';
        btn.disabled = true;

        // Simulate login (replace with actual backend call)
        setTimeout(() => {
            // Redirect to dashboard or show error
            // window.location.href = '../dashboard/dashboard.php';

            // For demo, just reset button
            btn.innerHTML = originalContent;
            btn.disabled = false;
            alert('Login form submitted! Connect this to your backend.');
        }, 1500);
    });
}

