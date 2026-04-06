<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';

function user_db_conn()
{
    $conn = $GLOBALS['conn'] ?? null;
    if (!($conn instanceof mysqli)) {
        die('Database connection is not available.');
    }

    return $conn;
}

function user_table_exists(mysqli $conn, $tableName)
{
    $safe = mysqli_real_escape_string($conn, (string) $tableName);
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$safe'");
    return $result && mysqli_num_rows($result) > 0;
}

function user_h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function user_require_member()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /library_project/auth/login.php');
        exit();
    }

    if (($_SESSION['role'] ?? 'user') === 'admin') {
        header('Location: /library_project/dashboard/admin/index.php');
        exit();
    }
}

function user_current_member(mysqli $conn)
{
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $stmt = mysqli_prepare($conn, 'SELECT id, username, email, created_at FROM users WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = $result ? mysqli_fetch_assoc($result) : null;

    if (!$user) {
        session_destroy();
        header('Location: /library_project/auth/login.php');
        exit();
    }

    return $user;
}

function user_sidebar_link($href, $icon, $label, $activeSlug, $slug)
{
    $activeClass = $activeSlug === $slug ? 'active' : '';
    echo '<a href="' . user_h($href) . '" class="user-side-link ' . $activeClass . '">';
    echo '<i class="fa-solid ' . user_h($icon) . '"></i><span>' . user_h($label) . '</span>';
    echo '</a>';
}

function user_render_layout_start($title, $subtitle, $activeSlug, $user)
{
    include __DIR__ . '/../../includes/header.php';
    echo '<link rel="stylesheet" href="/library_project/assets/css/dashboard.css">';
    echo '<link rel="stylesheet" href="/library_project/assets/css/user-pages.css">';
    include __DIR__ . '/../../includes/navbar.php';

    $initials = strtoupper(substr((string) ($user['username'] ?? 'U'), 0, 2));
    ?>
    <div class="user-shell">
        <aside class="user-sidebar card-surface">
            <div class="user-profile-chip">
                <div class="user-avatar"><?php echo user_h($initials); ?></div>
                <div>
                    <p class="user-name"><?php echo user_h($user['username'] ?? 'Reader'); ?></p>
                    <p class="user-email"><?php echo user_h($user['email'] ?? ''); ?></p>
                </div>
            </div>

            <nav class="user-side-nav">
                <?php user_sidebar_link('/library_project/dashboard/user/dashboard.php', 'fa-chart-simple', 'Dashboard', $activeSlug, 'dashboard'); ?>
                <?php user_sidebar_link('/library_project/dashboard/user/browse.php', 'fa-book-open-reader', 'Browse Books', $activeSlug, 'browse'); ?>
                <?php user_sidebar_link('/library_project/dashboard/user/favorites.php', 'fa-heart', 'Favorites', $activeSlug, 'favorites'); ?>
                <?php user_sidebar_link('/library_project/dashboard/user/profile.php', 'fa-user-gear', 'Profile', $activeSlug, 'profile'); ?>
                <?php user_sidebar_link('/library_project/auth/logout.php', 'fa-right-from-bracket', 'Logout', $activeSlug, 'logout'); ?>
            </nav>
        </aside>

        <main class="user-main">
            <header class="user-page-head card-surface">
                <div>
                    <h1><?php echo user_h($title); ?></h1>
                    <p><?php echo user_h($subtitle); ?></p>
                </div>
                <div class="mode-hint"><i class="fa-regular fa-moon"></i> Theme-aware UI</div>
            </header>
    <?php
}

function user_render_layout_end($scripts = [])
{
    echo '</main></div>';

    foreach ($scripts as $script) {
        echo '<script src="' . user_h($script) . '"></script>';
    }

    include __DIR__ . '/../../includes/footer.php';
}

