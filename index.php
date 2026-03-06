<?php
session_start();
?>
<?php include('includes/header.php'); ?>

    <link rel="stylesheet" href="/library_project/assets/css/index-style.css">
    <link rel="stylesheet" href="/library_project/assets/css/dark-mode.css">

</head>
<body>

<?php include('includes/navbar.php'); ?>

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


<?php include('includes/footer.php'); ?>
