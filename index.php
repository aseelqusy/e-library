<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>E-Library - Discover Your Next Favorite Book</title>

    <!-- Set theme before stylesheets to avoid flash -->
    <script src="/library_project/assets/js/theme-init.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="/library_project/assets/css/index-style.css">
    <link rel="stylesheet" href="/library_project/assets/css/dark-mode.css">

</head>
<body>

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

<!-- HERO SECTION WITH AUTH-STYLE DESIGN -->
<section class="hero-section">
    <div class="hero-background">
        <!-- Animated blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>

        <!-- Grid texture overlay -->
        <div class="hero-grid-overlay"></div>

        <!-- Flying books animation -->
        <div class="flying-books-hero">
            <div class="flying-book-item">📕</div>
            <div class="flying-book-item">📗</div>
            <div class="flying-book-item">📘</div>
            <div class="flying-book-item">📙</div>
            <div class="flying-book-item">📔</div>
        </div>

        <!-- Sparkles -->
        <div class="sparkles-hero">
            <div class="sparkle-item">✨</div>
            <div class="sparkle-item">✨</div>
            <div class="sparkle-item">✨</div>
            <div class="sparkle-item">✨</div>
            <div class="sparkle-item">✨</div>
        </div>
    </div>

    <div class="hero-content">
        <div class="hero-badge">
            <i class="fa-solid fa-sparkles"></i>
            Over 10,000+ books available
        </div>

        <h1 class="hero-title">
            Discover Your Next<br>
            <span class="gradient-text">Favorite Book</span>
        </h1>

        <p class="hero-subtitle">
            Explore our vast collection of digital books, journals, and resources.
            Read anytime, anywhere — completely free.
        </p>

        <div class="hero-stats">
            <div class="stat-card">
                <div class="stat-icon">📚</div>
                <div class="stat-info">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Books</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <div class="stat-number">4.2K</div>
                    <div class="stat-label">Readers</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-info">
                    <div class="stat-number">4.8</div>
                    <div class="stat-label">Rating</div>
                </div>
            </div>
        </div>

        <div class="hero-buttons">
            <a href="/library_project/auth/register.php" class="btn-primary">
                Get Started <i class="fa-solid fa-arrow-right"></i>
            </a>
            <button class="btn-secondary">
                Learn More
            </button>
        </div>

        <!-- Quick tip -->
        <div class="quick-tip">
            <div class="tip-icon">💡</div>
            <div class="tip-content">
                <div class="tip-title">Quick Tip</div>
                <p class="tip-text">Create a free account to unlock personalized recommendations and save your favorite books!</p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-grid">

        <div class="footer-brand">
            <a href="#" class="logo">
                <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
                <span class="logo-text">E-Library</span>
            </a>
            <p>
                Your gateway to thousands of books, journals, and digital resources.
                Explore, learn, and grow anytime, anywhere.
            </p>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Books</a></li>
                <li><a href="#">Browse</a></li>
                <li><a href="#">Categories</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contact Us</h4>
            <div class="contact-email">
                <i class="fa-regular fa-envelope"></i>
                info@elibrary.com
            </div>
            <div class="social-icons">
                <button class="social-btn"><i class="fa-brands fa-facebook-f"></i></button>
                <button class="social-btn"><i class="fa-brands fa-twitter"></i></button>
                <button class="social-btn"><i class="fa-brands fa-instagram"></i></button>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        © <?php echo date("Y"); ?> E-Library.
        Made with <span class="heart">❤</span> All rights reserved.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/library_project/assets/js/dark-mode.js"></script>

</body>
</html>
