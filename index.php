<?php
include "config.php";

echo "Connected";
?>

<?php include("includes/header.php"); ?>
<?php include("includes/navbar.php"); ?>

    <section class="hero">
        <div class="container">

        <span class="badge bg-light text-primary px-4 py-2 mb-4">
            ✨ Over 10,000+ books available
        </span>

            <h1>
                Discover Your Next <br>
                <span class="gradient-text">Favorite Book</span>
            </h1>

            <p class="mt-4">
                Explore our vast collection of digital books, journals, and resources.
                <br>
                Read anytime, anywhere — completely free.
            </p>

            <div class="mt-5">
                <a href="/library_project/books/browse.php" class="btn btn-gradient me-3">
                    Browse Books →
                </a>

                <a href="#" class="btn btn-outline-secondary rounded-pill px-4">
                    Learn More
                </a>
            </div>

        </div>
    </section>

<?php include("includes/footer.php"); ?>