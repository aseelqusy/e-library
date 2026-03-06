(function () {
    try {
        if (localStorage.getItem('elibrary-dark-mode') === 'true') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    } catch (e) {
        // Ignore storage access errors and keep default theme.
    }
})();

