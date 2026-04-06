<?php
require_once __DIR__ . '/_layout.php';

$conn = user_db_conn();
user_require_member();
$user = user_current_member($conn);
$userId = (int) $user['id'];
$bookId = (int) ($_GET['id'] ?? 0);

$hasBooksTable = user_table_exists($conn, 'books');
$hasCategoriesTable = user_table_exists($conn, 'categories');
$hasFavoritesTable = user_table_exists($conn, 'favorites');
$hasReviewsTable = user_table_exists($conn, 'reviews');

if ($bookId <= 0 || !$hasBooksTable) {
    header('Location: /library_project/dashboard/user/browse.php');
    exit();
}

$bookSql = "
    SELECT b.*, " . ($hasCategoriesTable ? "c.name AS category_name" : "NULL AS category_name") . ",
           " . ($hasReviewsTable ? "(SELECT COUNT(*) FROM reviews r WHERE r.book_id = b.id)" : "0") . " AS review_count,
           " . ($hasReviewsTable ? "(SELECT ROUND(AVG(rating), 1) FROM reviews r WHERE r.book_id = b.id)" : "NULL") . " AS avg_rating,
           " . ($hasFavoritesTable ? "(SELECT COUNT(*) FROM favorites f WHERE f.book_id = b.id AND f.user_id = ?)" : "0") . " AS is_favorited
    FROM books b
";

if ($hasCategoriesTable) {
    $bookSql .= " LEFT JOIN categories c ON c.id = b.category_id\n";
}

$bookSql .= " WHERE b.id = ? LIMIT 1";

$bookStmt = mysqli_prepare($conn, $bookSql);
if ($hasFavoritesTable) {
    mysqli_stmt_bind_param($bookStmt, 'ii', $userId, $bookId);
} else {
    mysqli_stmt_bind_param($bookStmt, 'i', $bookId);
}
mysqli_stmt_execute($bookStmt);
$bookResult = mysqli_stmt_get_result($bookStmt);
$book = $bookResult ? mysqli_fetch_assoc($bookResult) : null;

if (!$book) {
    header('Location: /library_project/dashboard/user/browse.php');
    exit();
}

$reviews = [];
if ($hasReviewsTable) {
    $reviewsSql = "
        SELECT r.rating, r.comment, r.created_at, u.username
        FROM reviews r
        INNER JOIN users u ON u.id = r.user_id
        WHERE r.book_id = ?
        ORDER BY r.created_at DESC
        LIMIT 5
    ";
    $reviewsStmt = mysqli_prepare($conn, $reviewsSql);
    if ($reviewsStmt) {
        mysqli_stmt_bind_param($reviewsStmt, 'i', $bookId);
        mysqli_stmt_execute($reviewsStmt);
        $reviewsResult = mysqli_stmt_get_result($reviewsStmt);
        while ($reviewsResult && $row = mysqli_fetch_assoc($reviewsResult)) {
            $reviews[] = $row;
        }
    }
}

$image = !empty($book['image']) ? $book['image'] : 'https://via.placeholder.com/420x600/7c3aed/ffffff?text=' . urlencode(substr((string) $book['title'], 0, 1));
$isFavorited = (int) ($book['is_favorited'] ?? 0) > 0;

user_render_layout_start('Book Details', 'Read summary, ratings, and quick actions before borrowing.', 'browse', $user);
?>

<section class="card-surface user-detail-hero">
    <img src="<?php echo user_h($image); ?>" alt="<?php echo user_h($book['title']); ?>">
    <div class="user-detail-text">
        <span class="user-chip"><?php echo user_h($book['category_name'] ?? 'Uncategorized'); ?></span>
        <h2><?php echo user_h($book['title']); ?></h2>
        <p><strong>Author:</strong> <?php echo user_h($book['author']); ?></p>
        <p><strong>Availability:</strong> <?php echo user_h(ucfirst((string) $book['type'])); ?></p>
        <p><strong>Rating:</strong> <?php echo user_h((string) ($book['avg_rating'] ?? 'N/A')); ?> (<?php echo (int) ($book['review_count'] ?? 0); ?> reviews)</p>
        <p><?php echo nl2br(user_h((string) ($book['description'] ?? 'No description available.'))); ?></p>
        <div class="user-actions">
            <button class="btn-user btn-user-secondary book-favorite-btn <?php echo $isFavorited ? 'favorited' : ''; ?>" data-book-id="<?php echo (int) $book['id']; ?>"><?php echo $isFavorited ? '❤️ Favorited' : '🤍 Add Favorite'; ?></button>
            <?php if (($book['type'] ?? '') === 'borrow' || ($book['type'] ?? '') === 'both'): ?>
                <button class="btn-user btn-user-primary" onclick="borrowBook(<?php echo (int) $book['id']; ?>, this)"><i class="fa-solid fa-book"></i> Borrow</button>
            <?php endif; ?>
            <a class="btn-user btn-user-secondary" href="/library_project/dashboard/user/browse.php">Back</a>
        </div>
    </div>
</section>

<section class="user-panel card-surface">
    <h3>Recent Reviews</h3>
    <div class="user-list">
        <?php if (empty($reviews)): ?>
            <p class="user-empty">No reviews yet for this book.</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <article class="user-list-item">
                    <div>
                        <strong><?php echo user_h($review['username']); ?></strong>
                        <div><?php echo str_repeat('★', (int) $review['rating']); ?></div>
                        <small><?php echo user_h((string) $review['comment']); ?></small>
                    </div>
                    <small><?php echo user_h(date('M d, Y', strtotime($review['created_at']))); ?></small>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php user_render_layout_end(['/library_project/assets/js/books.js']);

