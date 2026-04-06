<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function admin_h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function admin_db()
{
    global $conn;
    return $conn;
}

function admin_require_admin()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /library_project/auth/login.php');
        exit();
    }

    if (($_SESSION['role'] ?? 'user') !== 'admin') {
        header('Location: /library_project/dashboard/user/dashboard.php');
        exit();
    }
}

function admin_column_exists($conn, $table, $column)
{
    static $cache = [];
    $key = $table . '.' . $column;

    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $tableEscaped = mysqli_real_escape_string($conn, $table);
    $columnEscaped = mysqli_real_escape_string($conn, $column);
    $query = "SHOW COLUMNS FROM `$tableEscaped` LIKE '$columnEscaped'";
    $result = mysqli_query($conn, $query);

    $cache[$key] = $result && mysqli_num_rows($result) > 0;
    return $cache[$key];
}

function admin_set_toast($message, $type = 'success')
{
    $_SESSION['admin_toast'] = [
        'message' => $message,
        'type' => $type,
    ];
}

function admin_get_toast()
{
    if (!isset($_SESSION['admin_toast'])) {
        return null;
    }

    $toast = $_SESSION['admin_toast'];
    unset($_SESSION['admin_toast']);

    return $toast;
}

function admin_sidebar_items()
{
    return [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-chart-line', 'href' => '/library_project/dashboard/admin/index.php'],
        ['key' => 'books', 'label' => 'Books', 'icon' => 'fa-book-open', 'href' => '/library_project/dashboard/admin/books.php'],
        ['key' => 'categories', 'label' => 'Categories', 'icon' => 'fa-layer-group', 'href' => '/library_project/dashboard/admin/categories.php'],
        ['key' => 'users', 'label' => 'Users', 'icon' => 'fa-users', 'href' => '/library_project/dashboard/admin/users.php'],
        ['key' => 'borrow-requests', 'label' => 'Borrow Requests', 'icon' => 'fa-repeat', 'href' => '/library_project/dashboard/admin/borrow-requests.php'],
        ['key' => 'reviews', 'label' => 'Reviews', 'icon' => 'fa-star', 'href' => '/library_project/dashboard/admin/reviews.php'],
        ['key' => 'notifications', 'label' => 'Notifications', 'icon' => 'fa-bell', 'href' => '/library_project/dashboard/admin/notifications.php'],
        ['key' => 'settings', 'label' => 'Settings', 'icon' => 'fa-gear', 'href' => '/library_project/dashboard/admin/settings.php'],
    ];
}

function admin_render_start($pageTitle, $activeKey, $subtitle = 'Manage your digital library efficiently.')
{
    $username = $_SESSION['username'] ?? 'Admin';
    $email = $_SESSION['email'] ?? 'admin@library.local';
    $initials = strtoupper(substr($username, 0, 2));

    $toast = admin_get_toast();

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '  <meta charset="UTF-8">';
    echo '  <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '  <title>' . admin_h($pageTitle) . ' - E-Library Admin</title>';
    echo '  <script src="/library_project/assets/js/theme-init.js"></script>';
    echo '  <link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">';
    echo '  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">';
    echo '  <link rel="stylesheet" href="/library_project/assets/css/admin-components.css">';
    echo '  <link rel="stylesheet" href="/library_project/assets/css/admin-animations.css">';
    echo '</head>';
    echo '<body>';
    echo '  <div class="admin-shell">';
    echo '    <aside class="admin-sidebar" id="adminSidebar">';
    echo '      <div class="admin-brand">';
    echo '        <a href="/library_project/index.php" class="admin-brand-link">';
    echo '          <span class="brand-icon"><i class="fa-solid fa-book-open-reader"></i></span>';
    echo '          <span class="brand-text">E-Library Admin</span>';
    echo '        </a>';
    echo '      </div>';
    echo '      <div class="admin-profile">';
    echo '        <div class="admin-avatar">' . admin_h($initials) . '</div>';
    echo '        <div class="admin-profile-meta">';
    echo '          <strong>' . admin_h($username) . '</strong>';
    echo '          <span>' . admin_h($email) . '</span>';
    echo '        </div>';
    echo '      </div>';
    echo '      <nav class="admin-nav">';

    foreach (admin_sidebar_items() as $item) {
        $activeClass = $activeKey === $item['key'] ? ' is-active' : '';
        echo '<a class="admin-nav-link' . $activeClass . '" href="' . admin_h($item['href']) . '">';
        echo '  <i class="fa-solid ' . admin_h($item['icon']) . '"></i>';
        echo '  <span>' . admin_h($item['label']) . '</span>';
        echo '</a>';
    }

    echo '      </nav>';
    echo '      <div class="admin-sidebar-footer">';
    echo '        <a href="/library_project/auth/logout.php" class="admin-nav-link admin-nav-link-danger">';
    echo '          <i class="fa-solid fa-right-from-bracket"></i>';
    echo '          <span>Logout</span>';
    echo '        </a>';
    echo '      </div>';
    echo '    </aside>';
    echo '    <div class="admin-overlay" id="adminOverlay"></div>';
    echo '    <div class="admin-main">';
    echo '      <header class="admin-topbar">';
    echo '        <button type="button" class="icon-btn" id="sidebarToggle" aria-label="Toggle sidebar">';
    echo '          <i class="fa-solid fa-bars"></i>';
    echo '        </button>';
    echo '        <div class="admin-search">';
    echo '          <i class="fa-solid fa-magnifying-glass"></i>';
    echo '          <input type="search" placeholder="Search books, users, reviews..." aria-label="Global search">';
    echo '        </div>';
    echo '        <div class="admin-topbar-actions">';
    echo '          <button class="icon-btn" id="themeToggle" aria-label="Toggle theme"><i class="fa-solid fa-moon"></i></button>';
    echo '          <a href="/library_project/dashboard/admin/notifications.php" class="icon-btn" aria-label="Notifications"><i class="fa-solid fa-bell"></i></a>';
    echo '        </div>';
    echo '      </header>';
    echo '      <main class="admin-content page-fade">';
    echo '        <section class="page-header">';
    echo '          <div>';
    echo '            <h1>' . admin_h($pageTitle) . '</h1>';
    echo '            <p>' . admin_h($subtitle) . '</p>';
    echo '          </div>';
    echo '        </section>';

    if (is_array($toast) && isset($toast['message'], $toast['type'])) {
        echo '<script>window.__ADMIN_TOAST=' . json_encode($toast) . ';</script>';
    }
}

function admin_render_end()
{
    echo '      </main>';
    echo '    </div>';
    echo '  </div>';

    echo '  <div class="admin-modal" id="confirmModal" aria-hidden="true">';
    echo '    <div class="admin-modal-dialog">';
    echo '      <h3>Confirm Action</h3>';
    echo '      <p id="confirmModalText">Are you sure you want to continue?</p>';
    echo '      <div class="admin-modal-actions">';
    echo '        <button type="button" class="btn btn-secondary-soft" data-modal-close="confirmModal">Cancel</button>';
    echo '        <a href="#" class="btn btn-danger-soft" id="confirmModalAction">Yes, Continue</a>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';

    echo '  <div class="toast-stack" id="toastStack"></div>';
    echo '  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
    echo '  <script src="/library_project/assets/js/dark-mode.js"></script>';
    echo '  <script src="/library_project/assets/js/admin-utils.js"></script>';
    echo '</body>';
    echo '</html>';
}

