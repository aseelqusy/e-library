<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? '';

// Fetch favorite books
$favorites_query = "SELECT b.*, c.name as category_name, f.created_at as favorited_at
                    FROM favorites f
                    JOIN books b ON f.book_id = b.id
                    LEFT JOIN categories c ON b.category_id = c.id
                    WHERE f.user_id = ?
                    ORDER BY f.created_at DESC";
$stmt = mysqli_prepare($conn, $favorites_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$favorites_result = mysqli_stmt_get_result($stmt);
$favorite_books = [];
while ($row = mysqli_fetch_assoc($favorites_result)) {
    $favorite_books[] = $row;
}

// Get user favorites IDs
$user_favorites = [];
foreach ($favorite_books as $book) {
    $user_favorites[] = $book['id'];
}
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">
<link rel="stylesheet" href="/library_project/assets/css/dashboard.css">

<?php include('../includes/navbar.php'); ?>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo strtoupper(substr($username, 0, 2)); ?>
            </div>
            <div class="sidebar-username"><?php echo htmlspecialchars($username); ?></div>
            <div class="sidebar-email"><?php echo htmlspecialchars($email); ?></div>
        </div>


            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/dashboard.php" class="sidebar-nav-link">
                    <i class="fas fa-home sidebar-nav-icon"></i>
                    Dashboard
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/borrow.php" class="sidebar-nav-link">
                    <i class="fas fa-book sidebar-nav-icon"></i>
                    My Borrowed Books
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/favorits.php" class="sidebar-nav-link active">
                    <i class="fas fa-heart sidebar-nav-icon"></i>
                    Favorites
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/profile.php" class="sidebar-nav-link">
                    <i class="fas fa-user sidebar-nav-icon"></i>
                    Profile
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/books/brows.php" class="sidebar-nav-link">
                    <i class="fas fa-search sidebar-nav-icon"></i>
                    Browse Books
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/auth/logout.php" class="sidebar-nav-link" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt sidebar-nav-icon"></i>
                    Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->


        <!-- PAGE HEADER -->
        <div class="page-header">

                <i class="fas fa-heart"></i> My Favorite Books
            </h1>
            <p class="page-subtitle">
                Books you've saved for later reading
            </p>
        </div>

        <!-- FAVORITES GRID -->
        <?php if (empty($favorite_books)): ?>
            <div class="empty-state">

                <h3>No Favorite Books Yet</h3>
                <p>Start adding books to your favorites by clicking the heart icon on any book card!</p>
                <button class="btn-primary" onclick="window.location.href='/library_project/books/brows.php'">
                    <i class="fas fa-search"></i> Browse Books
                </button>
            </div>
        <?php else: ?>

            <div style="margin-bottom: 24px; padding: 16px; background: rgba(236, 72, 153, 0.1); border-radius: 12px; border: 1px solid rgba(236, 72, 153, 0.2);">

                    <span style="font-size: 20px;">💡</span>
                    <div style="flex: 1;">
                        <div style="font-size: 14px; font-weight: 600; color: var(--pink); margin-bottom: 4px;">
                            You have <?php echo count($favorite_books); ?> favorite <?php echo count($favorite_books) == 1 ? 'book' : 'books'; ?>
                        </div>
                        <p style="font-size: 13px; color: var(--muted); margin: 0;">
                            Click the heart icon on any book to remove it from favorites
                        </p>
                    </div>
                </div>
            </div>

            <div class="favorites-grid">
                <?php foreach ($favorite_books as $book): ?>
                    <?php


                        // Determine badge
                        $badge_text = 'Available';
                        $badge_class = 'available';
                        if ($book['type'] == 'sale') {
                            $badge_text = 'For Sale';
                            $badge_class = '';
                        } elseif ($book['type'] == 'borrow') {
                            $badge_text = 'For Borrow';

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
                            <button class="book-favorite-btn favorited"
                                    data-book-id="<?php echo $book['id']; ?>">
                            <img src="<?php echo htmlspecialchars($book_image); ?>"
                            </button>
                        </div>
                        <div class="book-card-body">
                            <div class="book-category">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?>
                            <button class="book-favorite-btn favorited"
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
                            <div style="font-size: 12px; color: var(--muted); margin-bottom: 12px;">
                                <i class="fas fa-heart"></i> Added <?php echo date('M d, Y', strtotime($book['favorited_at'])); ?>
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


    </main>


</div>

<?php include('../includes/footer.php'); ?>

<script src="/library_project/assets/js/books.js"></script>

