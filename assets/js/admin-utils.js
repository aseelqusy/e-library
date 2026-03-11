(function () {
    const body = document.body;
    const sidebarToggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('adminOverlay');

    function closeSidebar() {
        body.classList.remove('sidebar-open');
    }

    function openSidebar() {
        body.classList.add('sidebar-open');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            if (body.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth > 991) {
            closeSidebar();
        }
    });

    const confirmModal = document.getElementById('confirmModal');
    const confirmActionLink = document.getElementById('confirmModalAction');
    const confirmModalText = document.getElementById('confirmModalText');

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        }
    }

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-confirm]');
        if (trigger) {
            event.preventDefault();
            if (confirmActionLink) {
                confirmActionLink.href = trigger.getAttribute('href') || '#';
            }
            if (confirmModalText) {
                confirmModalText.textContent = trigger.getAttribute('data-confirm') || 'Are you sure you want to continue?';
            }
            openModal('confirmModal');
            return;
        }

        const closeTrigger = event.target.closest('[data-modal-close]');
        if (closeTrigger) {
            event.preventDefault();
            closeModal(closeTrigger.getAttribute('data-modal-close'));
            return;
        }

        if (confirmModal && event.target === confirmModal) {
            closeModal('confirmModal');
        }
    });

    const toastStack = document.getElementById('toastStack');

    function showToast(message, type) {
        if (!toastStack || !message) {
            return;
        }

        const toast = document.createElement('div');
        toast.className = 'admin-toast ' + (type || 'success');
        toast.textContent = message;
        toastStack.appendChild(toast);

        window.setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(8px)';
            window.setTimeout(function () {
                toast.remove();
            }, 220);
        }, 3000);
    }

    window.adminShowToast = showToast;

    if (window.__ADMIN_TOAST && window.__ADMIN_TOAST.message) {
        showToast(window.__ADMIN_TOAST.message, window.__ADMIN_TOAST.type || 'success');
    }
})();

