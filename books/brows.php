<?php
session_start();
require_once '../includes/db.php';

// Get filters from URL
$category_filter = isset($_GET['categories']) ? explode(',', $_GET['categories']) : [];
$status_filter = isset($_GET['status']) ? explode(',', $_GET['status']) : [];

// Fetch categories for filter
$categories_query = "SELECT * FROM categories ORDER BY name";
$categories_result = mysqli_query($conn, $categories_query);
$categories = [];
while ($row = mysqli_fetch_assoc($categories_result)) {
    $categories[] = $row;
}

// Build books query with filters
$books_query = "SELECT b.*, c.name as category_name FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE 1=1";

$params = [];
$types = "";

if (!empty($category_filter)) {
    $placeholders = str_repeat('?,', count($category_filter) - 1) . '?';
    $books_query .= " AND c.name IN ($placeholders)";
    $params = array_merge($params, $category_filter);
    $types .= str_repeat('s', count($category_filter));
}

if (!empty($status_filter)) {
    $placeholders = str_repeat('?,', count($status_filter) - 1) . '?';
    $books_query .= " AND b.type IN ($placeholders)";
    $params = array_merge($params, $status_filter);
    $types .= str_repeat('s', count($status_filter));
}

$books_query .= " ORDER BY b.created_at DESC";

if (!empty($params)) {
    $stmt = mysqli_prepare($conn, $books_query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $books_result = mysqli_stmt_get_result($stmt);
} else {
    $books_result = mysqli_query($conn, $books_query);
}

$books = [];
while ($row = mysqli_fetch_assoc($books_result)) {
    $books[] = $row;
}

// Get user favorites if logged in
$user_favorites = [];
if (isset($_SESSION['user_id'])) {
    $fav_query = "SELECT book_id FROM favorites WHERE user_id = ?";
    $fav_stmt = mysqli_prepare($conn, $fav_query);
    mysqli_stmt_bind_param($fav_stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($fav_stmt);
    $fav_result = mysqli_stmt_get_result($fav_stmt);
    while ($row = mysqli_fetch_assoc($fav_result)) {
        $user_favorites[] = $row['book_id'];
    }
}
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">

<?php include('../includes/navbar.php'); ?>

<!-- HERO SECTION -->
<section class="search-hero">
    <div class="search-hero-content">
        <h1>Browse Our Collection</h1>
        <p>Discover thousands of books across all genres and categories</p>

        <div class="search-bar-large">
            <form action="/library_project/books/search.php" method="GET">
                <div class="search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" name="q" class="search-input-large" placeholder="Search by title, author, or keyword...">
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- MAIN CONTENT -->
<div class="page-container">

    <!-- FILTERS -->
    <div class="filters-section">
        <div class="filter-header">
            <h3><i class="fas fa-filter"></i> Filters</h3>
            <button class="clear-filters">Clear All</button>
        </div>

        <div class="filter-group">
            <label class="filter-label">Categories</label>
            <div class="filter-options">
                <?php if (empty($categories)): ?>
                    <span style="color: var(--muted); font-size: 14px;">No categories available</span>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <button class="filter-chip <?php echo in_array($cat['name'], $category_filter) ? 'active' : ''; ?>"
                                data-filter-type="category"
                                data-filter-value="<?php echo htmlspecialchars($cat['name']); ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="filter-group">
            <label class="filter-label">Availability</label>
            <div class="filter-options">
                <button class="filter-chip <?php echo in_array('borrow', $status_filter) ? 'active' : ''; ?>"
                        data-filter-type="status"
                        data-filter-value="borrow">
                    📚 For Borrow
                </button>
                <button class="filter-chip <?php echo in_array('sale', $status_filter) ? 'active' : ''; ?>"
                        data-filter-type="status"
                        data-filter-value="sale">
                    💰 For Sale
                </button>
                <button class="filter-chip <?php echo in_array('both', $status_filter) ? 'active' : ''; ?>"
                        data-filter-type="status"
                        data-filter-value="both">
                    ✨ Both
                </button>
            </div>
        </div>
    </div>

    <!-- BOOKS HEADER -->
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-book"></i> All Books
            <span style="color: var(--muted); font-weight: 500; font-size: 18px;">(<?php echo count($books); ?>)</span>
        </h2>
    </div>

    <!-- BOOKS GRID -->
    <?php if (empty($books)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Books Found</h3>
            <p>Try adjusting your filters or check back later for new additions.</p>
            <button class="btn-primary" onclick="window.location.href='/library_project/books/brows.php'">
                <i class="fas fa-refresh"></i> Reset Filters
            </button>
        </div>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
                <?php
                    $is_favorited = in_array($book['id'], $user_favorites);
                    $book_image = !empty($book['image']) ? $book['image'] : 'https://via.placeholder.com/300x400/7c3aed/ffffff?text=' . urlencode(substr($book['title'], 0, 1));

                    // Determine badge
                    $badge_text = 'Available';
                    $badge_class = 'available';
                    if ($book['type'] == 'sale') {
                        $badge_text = 'For Sale';
                        $badge_class = '';
                    } elseif ($book['type'] == 'borrow') {
                        $badge_text = 'For Borrow';
                        $badge_class = 'available';
                    } elseif ($book['type'] == 'both') {
                        $badge_text = 'Borrow/Sale';
                        $badge_class = 'available';
                    }
                ?>
                <div class="book-card">
                    <div class="book-card-image">
                        <img src="<?php echo htmlspecialchars($book_image); ?>"
                             alt="<?php echo htmlspecialchars($book['title']); ?>"
                             onerror="this.src='https://via.placeholder.com/300x400/7c3aed/ffffff?text=No+Image'">
                        <span class="book-badge <?php echo $badge_class; ?>"><?php echo $badge_text; ?></span>
                        <button class="book-favorite-btn <?php echo $is_favorited ? 'favorited' : ''; ?>"
                                data-book-id="<?php echo $book['id']; ?>">
                            <?php echo $is_favorited ? '❤️' : '🤍'; ?>
                        </button>
                    </div>
                    <div class="book-card-body">
                        <div class="book-category">
                            <i class="fas fa-tag"></i> <?php echo htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?>
                        </div>
                        <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                        <div class="book-author">
                            <i class="fas fa-user-pen"></i>
                            <?php echo htmlspecialchars($book['author']); ?>
                        </div>
                        <p class="book-description">
                            <?php echo htmlspecialchars(substr($book['description'] ?? 'No description available.', 0, 120)); ?>...
                        </p>
                        <div class="book-rating">
                            <span class="stars">★★★★★</span>
                            <span class="rating-text">4.8 (120 reviews)</span>
                        </div>
                        <div class="book-card-actions">
                            <a href="/library_project/books/detaills.php?id=<?php echo $book['id']; ?>" class="btn-view-details">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <?php if ($book['type'] == 'borrow' || $book['type'] == 'both'): ?>
                                <button class="btn-icon-only" onclick="borrowBook(<?php echo $book['id']; ?>, this)" title="Borrow Book">
                                    <i class="fas fa-book"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>

<script src="/library_project/assets/js/books.js"></script>

