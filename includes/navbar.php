<!-- TOP BAR -->
<header class="top-bar">
    <a href="/library_project/index.php" class="logo">
        <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
        <span class="logo-text">E-Library</span>
    </a>

    <div class="top-bar-actions">

        <button class="icon-btn" id="themeToggle">
            <i class="fa-solid fa-moon"></i>
        </button>

        <?php if(isset($_SESSION['user_id'])): ?>

            <?php $dashboardLink = (($_SESSION['role'] ?? 'user') === 'admin') ? '/library_project/dashboard/admin/index.php' : '/library_project/dashboard/user/dashboard.php'; ?>
            <a href="<?php echo $dashboardLink; ?>" class="btn-login">
                Dashboard
            </a>

            <a href="/library_project/auth/logout.php" class="btn-signup">
                Logout
            </a>

        <?php else: ?>

            <a href="/library_project/auth/login.php" class="btn-login">
                Log In
            </a>

            <a href="/library_project/auth/register.php" class="btn-signup">
                Sign Up
            </a>

        <?php endif; ?>

        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</header>

<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$isHomePage = ($currentPath === '/library_project/index.php' || $currentPath === '/library_project/');
$isCategoriesPage = ($currentPath === '/library_project/pages/categories.php');
$isAboutPage = ($currentPath === '/library_project/pages/about.php');
$isContactPage = ($currentPath === '/library_project/pages/contact.php');
?>

<!-- MAIN NAV -->
<nav class="main-nav" id="mainNav">
    <a href="/library_project/index.php" class="<?php echo $isHomePage ? 'active' : ''; ?>">Home</a>
    <a href="/library_project/pages/categories.php" class="<?php echo $isCategoriesPage ? 'active' : ''; ?>">Categories</a>
    <a href="/library_project/pages/about.php" class="<?php echo $isAboutPage ? 'active' : ''; ?>">About</a>
    <a href="/library_project/pages/contact.php" class="<?php echo $isContactPage ? 'active' : ''; ?>">Contact</a>

    <!-- Mobile-only auth links -->
    <div class="mobile-auth-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <?php $dashboardLink = (($_SESSION['role'] ?? 'user') === 'admin') ? '/library_project/dashboard/admin/index.php' : '/library_project/dashboard/user/dashboard.php'; ?>
            <a href="<?php echo $dashboardLink; ?>" class="mobile-auth-link">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="/library_project/auth/logout.php" class="mobile-auth-link" style="color: #ef4444;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</nav>