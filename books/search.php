<?php
session_start();
require_once '../includes/db.php';

// Get search query from URL
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

$books = [];
$user_favorites = [];

if (!empty($search_query)) {
    // Search books by title, author, or description
    $search_param = "%$search_query%";
    $books_query = "SELECT b.*, c.name as category_name 
                    FROM books b 
                    LEFT JOIN categories c ON b.category_id = c.id 
                    WHERE b.title LIKE ? OR b.author LIKE ? OR b.description LIKE ?
                    ORDER BY b.created_at DESC";

    $stmt = mysqli_prepare($conn, $books_query);
    mysqli_stmt_bind_param($stmt, "sss", $search_param, $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $books_result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($books_result)) {
        $books[] = $row;
    }
}

// Get user favorites if logged in
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

<!-- SEARCH HERO -->
<section class="search-hero">
    <div class="search-hero-content">
        <h1>Search Books</h1>
        <p>Find your next favorite book by title, author, or keyword</p>

        <div class="search-bar-large">
            <form action="/library_project/books/search.php" method="GET">
                <div class="search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text"
                           name="q"
                           class="search-input-large"
                           placeholder="Search by title, author, or keyword..."
                           value="<?php echo htmlspecialchars($search_query); ?>"
                           autofocus>
                    <button type="submit" class="btn-search">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- SEARCH RESULTS -->
<div class="page-container">

    <?php if (!empty($search_query)): ?>

        <!-- RESULTS HEADER -->
        <div class="page-header">
            <h2 class="page-title">
                Search Results for "<?php echo htmlspecialchars($search_query); ?>"
            </h2>
            <p class="page-subtitle">
                Found <?php echo count($books); ?> <?php echo count($books) == 1 ? 'book' : 'books'; ?>
            </p>
        </div>

        <!-- RESULTS GRID -->
        <?php if (empty($books)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🔍</div>
                <h3>No Books Found</h3>
                <p>We couldn't find any books matching "<?php echo htmlspecialchars($search_query); ?>"</p>
                <p style="color: var(--muted); font-size: 14px; margin-top: 12px;">Try different keywords or browse all books</p>
                <button class="btn-primary" onclick="window.location.href='/library_project/books/brows.php'" style="margin-top: 24px;">
                    <i class="fas fa-book"></i> Browse All Books
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

    <?php else: ?>

        <!-- SEARCH PROMPT -->
        <div class="empty-state">
            <div class="empty-state-icon">🔍</div>
            <h3>Start Your Search</h3>
            <p>Enter a book title, author name, or keyword in the search bar above</p>
        </div>

    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>

<script src="/library_project/assets/js/books.js"></script>

