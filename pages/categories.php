<?php
session_start();
require_once '../includes/db.php';

// Fetch all categories
$categories_query = "SELECT c.*, COUNT(b.id) as book_count 
                     FROM categories c 
                     LEFT JOIN books b ON c.id = b.category_id 
                     GROUP BY c.id 
                     ORDER BY c.name";
$categories_result = mysqli_query($conn, $categories_query);
$categories = [];
while ($row = mysqli_fetch_assoc($categories_result)) {
    $categories[] = $row;
}
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">

<?php include('../includes/navbar.php'); ?>

<!-- CATEGORIES PAGE -->
<div class="page-container">

    <!-- PAGE HEADER -->
    <div class="page-header" style="text-align: center; padding: 60px 0 40px;">
        <h1 class="page-title" style="font-size: 48px; margin-bottom: 16px;">
            Browse by Category
        </h1>
        <p class="page-subtitle" style="font-size: 18px; max-width: 700px; margin: 0 auto;">
            Explore our vast collection organized by genre. Find your next favorite book in your preferred category.
        </p>
    </div>

    <!-- CATEGORIES GRID -->
    <?php if (empty($categories)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📂</div>
            <h3>No Categories Yet</h3>
            <p>Categories will appear here once books are added to the library.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 28px; margin-bottom: 60px;">

            <?php
            $category_icons = [
                'Fiction' => '📖',
                'Non-Fiction' => '📚',
                'Science' => '🔬',
                'Technology' => '💻',
                'History' => '🏛️',
                'Biography' => '👤',
                'Self-Help' => '🌟',
                'Mystery' => '🔍',
                'Romance' => '💕',
                'Fantasy' => '🐉',
                'Thriller' => '😱',
                'Horror' => '👻',
                'Adventure' => '🗺️',
                'Comedy' => '😄',
                'Drama' => '🎭'
            ];

            $gradient_colors = [
                ['#7c3aed', '#ec4899'],
                ['#10b981', '#059669'],
                ['#3b82f6', '#2563eb'],
                ['#f59e0b', '#d97706'],
                ['#ef4444', '#dc2626'],
                ['#8b5cf6', '#7c3aed'],
                ['#14b8a6', '#0d9488'],
                ['#f97316', '#ea580c'],
            ];

            foreach ($categories as $index => $category):
                $icon = $category_icons[$category['name']] ?? '📚';
                $gradient = $gradient_colors[$index % count($gradient_colors)];
            ?>
                <a href="/library_project/books/brows.php?categories=<?php echo urlencode($category['name']); ?>"
                   style="text-decoration: none; color: inherit;">
                    <div style="background: var(--white); border: 1px solid var(--border); border-radius: 20px; padding: 32px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; height: 100%;"
                         onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 40px rgba(124, 58, 237, 0.15)';"
                         onmouseout="this.style.transform=''; this.style.boxShadow='';">

                        <div style="width: 80px; height: 80px; margin: 0 auto 20px; background: linear-gradient(135deg, <?php echo $gradient[0]; ?>, <?php echo $gradient[1]; ?>); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 40px;">
                            <?php echo $icon; ?>
                        </div>

                        <h3 style="font-family: 'Sora', sans-serif; font-size: 22px; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </h3>

                        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; color: var(--muted); font-size: 14px; margin-bottom: 16px;">
                            <i class="fas fa-book"></i>
                            <span><?php echo $category['book_count']; ?> <?php echo $category['book_count'] == 1 ? 'book' : 'books'; ?></span>
                        </div>

                        <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(124, 58, 237, 0.1); border-radius: 20px; font-size: 14px; font-weight: 600; color: var(--purple);">
                            Explore <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

    <!-- POPULAR CATEGORIES -->
    <?php if (!empty($categories)): ?>
    <div style="background: linear-gradient(145deg, #4c1d95 0%, #7c3aed 40%, #9333ea 65%, #ec4899 100%); border-radius: 20px; padding: 60px 40px; text-align: center; color: white; margin-bottom: 60px;">
        <h2 style="font-size: 32px; font-weight: 800; margin-bottom: 16px;">Find Your Perfect Read</h2>
        <p style="font-size: 16px; opacity: 0.9; margin-bottom: 32px;">
            With <?php echo count($categories); ?> categories and thousands of books, there's something for everyone
        </p>
        <a href="/library_project/books/brows.php" class="btn-primary" style="background: white; color: var(--purple); text-decoration: none; display: inline-flex; align-items: center; gap: 10px;">
            <i class="fas fa-book-open"></i> Browse All Books
        </a>
    </div>
    <?php endif; ?>

    <!-- CATEGORY TIPS -->
    <div style="max-width: 800px; margin: 0 auto 60px;">
        <h2 style="font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 700; text-align: center; margin-bottom: 32px;">
            How to Use Categories
        </h2>

        <div style="display: grid; gap: 20px;">

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 24px; display: flex; gap: 20px; align-items: start;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--purple), var(--pink)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; flex-shrink: 0;">
                    1
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Choose a Category</h3>
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin: 0;">
                        Click on any category above to see all books in that genre
                    </p>
                </div>
            </div>

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 24px; display: flex; gap: 20px; align-items: start;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; flex-shrink: 0;">
                    2
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Browse & Filter</h3>
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin: 0;">
                        Use additional filters to narrow down your search and find exactly what you're looking for
                    </p>
                </div>
            </div>

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 24px; display: flex; gap: 20px; align-items: start;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; flex-shrink: 0;">
                    3
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">Borrow & Enjoy</h3>
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin: 0;">
                        Click on a book to view details, then borrow it instantly or add it to your favorites
                    </p>
                </div>
            </div>

        </div>
    </div>

</div>

<?php include('../includes/footer.php'); ?>

<style>
:root {
    --white: #ffffff;
    --border: #e5e7eb;
    --text: #1a1a2e;
    --muted: #6b7280;
    --purple: #7c3aed;
    --pink: #ec4899;
    --bg: #f9fafb;
}

[data-theme="dark"] {
    --white: #1a1a1a;
    --border: #2a2a2a;
    --text: #f5f5f5;
    --muted: #b0b0b0;
    --bg: #0f0f0f;
}

body {
    background: var(--bg);
}

/* RESPONSIVE STYLES */
@media (max-width: 768px) {
    .page-header {
        padding: 40px 20px 30px !important;
    }

    .page-title {
        font-size: 32px !important;
    }

    .page-subtitle {
        font-size: 16px !important;
    }

    /* Categories grid */
    .page-container > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        padding: 0 20px;
        margin-bottom: 40px !important;
    }

    /* Category card hover states work better on touch */
    .page-container > div[style*="grid-template-columns"] a > div {
        padding: 24px !important;
    }

    /* Gradient section */
    .page-container > div[style*="background: linear-gradient"] {
        padding: 40px 24px !important;
        margin: 0 20px 40px !important;
    }

    /* Tips section */
    .page-container > div[style*="max-width: 800px"] {
        padding: 0 20px;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 30px 16px 24px !important;
    }

    .page-title {
        font-size: 26px !important;
    }

    .page-subtitle {
        font-size: 15px !important;
    }

    .page-container > div[style*="grid-template-columns"] {
        padding: 0 16px;
    }

    .page-container > div[style*="grid-template-columns"] a > div {
        padding: 20px !important;
    }

    /* Icon size */
    .page-container div[style*="width: 80px"] {
        width: 64px !important;
        height: 64px !important;
        font-size: 32px !important;
    }

    .page-container h2, .page-container h3 {
        font-size: 20px !important;
    }
}
</style>

