// Dark Mode Toggle Functionality

const DARK_MODE_KEY = 'elibrary-dark-mode';

// Enable dark mode
function enableDarkMode() {
    document.documentElement.setAttribute('data-theme', 'dark');
    localStorage.setItem(DARK_MODE_KEY, 'true');
    updateThemeIcon();
}

// Disable dark mode
function disableDarkMode() {
    document.documentElement.removeAttribute('data-theme');
    localStorage.setItem(DARK_MODE_KEY, 'false');
    updateThemeIcon();
}

// Toggle dark mode
function toggleDarkMode() {
    const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

    if (isDarkMode) {
        disableDarkMode();
    } else {
        enableDarkMode();
    }
}

// Update theme icon
function updateThemeIcon() {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;

    const icon = themeToggle.querySelector('i');
    const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';

    if (isDarkMode) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}

// Listen for theme toggle button clicks
document.addEventListener('click', (e) => {
    if (e.target.closest('#themeToggle')) {
        toggleDarkMode();
    }
});



