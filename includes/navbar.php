<!-- TOP BAR -->
<header class="top-bar">
    <a href="/library_project/index.php" class="logo">
        <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
        <span class="logo-text">E-Library</span>
    </a>

    <div class="top-bar-actions">
        <a href="/library_project/books/search.php" class="icon-btn" title="Search Books">
            <i class="fa-solid fa-magnifying-glass"></i>
        </a>

        <button class="icon-btn" id="themeToggle">
            <i class="fa-solid fa-moon"></i>
        </button>

        <?php if(isset($_SESSION['user_id'])): ?>

            <a href="/library_project/dashboard/dashboard.php" class="btn-login">
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

<!-- MAIN NAV -->
<nav class="main-nav" id="mainNav">
    <a href="/library_project/index.php" class="active">Home</a>
    <a href="/library_project/books/brows.php">Books</a>
    <a href="/library_project/books/search.php">Browse</a>
    <a href="/library_project/pages/categories.php">Categories</a>
    <a href="/library_project/pages/about.php">About</a>
    <a href="/library_project/pages/contact.php">Contact</a>

    <!-- Mobile-only auth links -->
    <div class="mobile-auth-links">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="/library_project/dashboard/dashboard.php" class="mobile-auth-link">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="/library_project/auth/logout.php" class="mobile-auth-link" style="color: #ef4444;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        <?php endif; ?>
    </div>
</nav>