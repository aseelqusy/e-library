<?php
session_start();
require_once '../includes/db.php';

// Get book ID from URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id <= 0) {
    header('Location: /library_project/books/brows.php');
    exit();
}

// Fetch book details
$book_query = "SELECT b.*, c.name as category_name, a.name as author_name 
               FROM books b 
               LEFT JOIN categories c ON b.category_id = c.id 
               LEFT JOIN authors a ON b.author_id = a.id
               WHERE b.id = ?";
$stmt = mysqli_prepare($conn, $book_query);
mysqli_stmt_bind_param($stmt, "i", $book_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header('Location: /library_project/books/brows.php');
    exit();
}

$book = mysqli_fetch_assoc($result);

// Check if favorited
$is_favorited = false;
if (isset($_SESSION['user_id'])) {
    $fav_query = "SELECT id FROM favorites WHERE user_id = ? AND book_id = ?";
    $fav_stmt = mysqli_prepare($conn, $fav_query);
    mysqli_stmt_bind_param($fav_stmt, "ii", $_SESSION['user_id'], $book_id);
    mysqli_stmt_execute($fav_stmt);
    $fav_result = mysqli_stmt_get_result($fav_stmt);
    $is_favorited = mysqli_num_rows($fav_result) > 0;
}

// Check if already borrowed
$is_borrowed = false;
if (isset($_SESSION['user_id'])) {
    $borrow_query = "SELECT id FROM borrowings WHERE user_id = ? AND book_id = ? AND status = 'borrowed'";
    $borrow_stmt = mysqli_prepare($conn, $borrow_query);
    mysqli_stmt_bind_param($borrow_stmt, "ii", $_SESSION['user_id'], $book_id);
    mysqli_stmt_execute($borrow_stmt);
    $borrow_result = mysqli_stmt_get_result($borrow_stmt);
    $is_borrowed = mysqli_num_rows($borrow_result) > 0;
}

// Fetch reviews
$reviews_query = "SELECT r.*, u.username FROM reviews r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.book_id = ? 
                  ORDER BY r.created_at DESC LIMIT 10";
$reviews_stmt = mysqli_prepare($conn, $reviews_query);
mysqli_stmt_bind_param($reviews_stmt, "i", $book_id);
mysqli_stmt_execute($reviews_stmt);
$reviews_result = mysqli_stmt_get_result($reviews_stmt);
$reviews = [];
while ($row = mysqli_fetch_assoc($reviews_result)) {
    $reviews[] = $row;
}

// Calculate average rating
$avg_rating = 4.8; // Default
if (!empty($reviews)) {
    $total = 0;
    foreach ($reviews as $review) {
        $total += $review['rating'];
    }
    $avg_rating = round($total / count($reviews), 1);
}

$book_image = !empty($book['image']) ? $book['image'] : 'https://via.placeholder.com/400x600/7c3aed/ffffff?text=' . urlencode(substr($book['title'], 0, 1));
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">

<?php include('../includes/navbar.php'); ?>

<!-- BOOK DETAILS -->
<div class="page-container">

    <div class="book-details-container">

        <!-- BOOK IMAGE -->
        <div class="book-details-image">
            <img src="<?php echo htmlspecialchars($book_image); ?>"
                 alt="<?php echo htmlspecialchars($book['title']); ?>"
                 onerror="this.src='https://via.placeholder.com/400x600/7c3aed/ffffff?text=No+Image'">
        </div>
        

        <div class="book-details-content">
            

                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($book['category_name'] ? $book['category_name'] : 'Uncategorized'); ?>
            </div>
            


            <div class="book-details-author">
                <i class="fas fa-user-pen"></i>

            </div>
            
            <div class="book-details-rating">
                <div class="book-rating">
                    <span class="stars">★★★★★</span>

                </div>
            </div>
            
            <p class="book-details-description">
                <?php echo nl2br(htmlspecialchars($book['description'] ?? 'No description available.')); ?>
            </p>
            
            <!-- BOOK INFO -->
            <div class="book-details-info">
                <div class="info-row">

                    <span class="info-value">
                        <?php 
                <?php echo nl2br(htmlspecialchars($book['description'] ? $book['description'] : 'No description available.')); ?>

                            else echo 'Available for Borrow & Sale';
                        ?>
                    </span>
                </div>
                <?php if ($book['type'] == 'sale' || $book['type'] == 'both'): ?>
                <div class="info-row">
                        <?php
                    <span class="info-value">$<?php echo number_format($book['price'], 2); ?></span>
                </div>
                <?php endif; ?>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-calendar"></i> Added</span>
                    <span class="info-value"><?php echo date('M d, Y', strtotime($book['created_at'])); ?></span>
                </div>
            </div>
            
            <!-- ACTION BUTTONS -->
            <div class="book-details-actions">
                <?php if ($book['type'] == 'borrow' || $book['type'] == 'both'): ?>
                    <?php if ($is_borrowed): ?>
                        <button class="btn-primary" disabled style="opacity: 0.6;">
                            <i class="fas fa-check"></i> Already Borrowed
                        </button>
                    <?php else: ?>

                            <i class="fas fa-book"></i> Borrow This Book
                        </button>
                    <?php endif; ?>
                <?php endif; ?>
                
                <button class="btn-secondary book-favorite-btn <?php echo $is_favorited ? 'favorited' : ''; ?>" 
                        data-book-id="<?php echo $book['id']; ?>">
                    <?php echo $is_favorited ? '❤️' : '🤍'; ?>
                    <?php echo $is_favorited ? 'Favorited' : 'Add to Favorites'; ?>
                </button>
            </div>
            
            <!-- SHARE BUTTONS -->
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button class="btn-icon-only" title="Share on Facebook">

                <button class="btn-secondary book-favorite-btn <?php echo $is_favorited ? 'favorited' : ''; ?>"
                <button class="btn-icon-only" title="Share on Twitter">
                    <i class="fab fa-twitter"></i>
                </button>
                <button class="btn-icon-only" title="Copy Link">
                    <i class="fas fa-link"></i>
                </button>
            </div>
            


    </div>
    
    <!-- REVIEWS SECTION -->
    <div class="reviews-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-star"></i> Reviews (<?php echo count($reviews); ?>)
            </h2>
        </div>
        
        <?php if (empty($reviews)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">⭐</div>
                <h3>No Reviews Yet</h3>
                <p>Be the first to review this book!</p>
            </div>
        <?php else: ?>

                <div class="review-card">


                            <div class="review-avatar">
                                <?php echo strtoupper(substr($review['username'], 0, 2)); ?>
                            </div>
                            <div class="review-user-info">

                                <div class="review-date">
                                    <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="book-rating">
                            <span class="stars">
                                <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
                            </span>
                        </div>
                    </div>
                    <p class="review-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


</div>

<?php include('../includes/footer.php'); ?>

<script src="/library_project/assets/js/books.js"></script>


