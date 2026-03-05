<!-- TOP BAR -->
<header class="top-bar">
    <a href="/library_project/index.php" class="logo">
        <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
        <span class="logo-text">E-Library</span>
    </a>

    <div class="top-bar-actions">
        <button class="icon-btn"><i class="fa-solid fa-magnifying-glass"></i></button>

        <button class="icon-btn" id="themeToggle">
            <i class="fa-solid fa-moon"></i>
        </button>

        <?php if(isset($_SESSION['user_id'])): ?>

            <a href="/library_project/dashboard/dashboard.php" class="btn-login">
                Dashboard
            </a>

            <a href="/auth/register.php" class="btn-signup">
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
    </div>
</header>

<!-- MAIN NAV -->
<nav class="main-nav">
    <a href="/library_project/index.php" class="active">Home</a>
    <a href="/library_project/books/browse.php">Books</a>
    <a href="#">Browse</a>
    <a href="#">Categories</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
</nav>