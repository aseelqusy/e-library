<?php
include "config.php";

echo "Connected";
?>
<?php include("includes/header.php"); ?>
<?php include("includes/navbar.php"); ?>

    <section class="hero">
        <div class="hero-badge">
            <i class="fa-solid fa-sparkles"></i>
            Over 10,000+ books available
        </div>

        <h1>
            Discover Your Next<br>
            <span class="gradient-text">Favorite Book</span>
        </h1>

        <p>
            Explore our vast collection of digital books, journals, and resources.
            Read anytime, anywhere — completely free.
        </p>

        <div class="hero-buttons">
            <a href="/library_project/books/browse.php" class="btn-browse">
                Browse Books <i class="fa-solid fa-arrow-right"></i>
            </a>
            <button class="btn-learn">Learn More</button>
        </div>
    </section>

<?php include("includes/footer.php"); ?>