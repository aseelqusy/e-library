<?php
require_once __DIR__ . '/_layout.php';

$conn = user_db_conn();
user_require_member();
$user = user_current_member($conn);
$userId = (int) $user['id'];

$favorites = [];
$hasFavoritesTable = user_table_exists($conn, 'favorites');
$hasBooksTable = user_table_exists($conn, 'books');
$hasCategoriesTable = user_table_exists($conn, 'categories');

if ($hasFavoritesTable && $hasBooksTable) {
    $sql = "
        SELECT b.*, " . ($hasCategoriesTable ? "c.name AS category_name," : "NULL AS category_name,") . " f.created_at AS favorited_at
        FROM favorites f
        INNER JOIN books b ON b.id = f.book_id
        " . ($hasCategoriesTable ? "LEFT JOIN categories c ON c.id = b.category_id" : "") . "
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC
    ";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($result && $row = mysqli_fetch_assoc($result)) {
            $favorites[] = $row;
        }
    }
}

user_render_layout_start('Your Favorites', 'All books you liked in one place for quick access.', 'favorites', $user);
?>

<section class="user-panel card-surface">
    <h3>Saved Books (<?php echo count($favorites); ?>)</h3>
    <?php if (empty($favorites)): ?>
        <div class="user-empty">
            <h4>No favorites yet</h4>
            <p>Mark books with a heart icon from the browse page.</p>
            <a class="btn-user btn-user-primary" href="/library_project/dashboard/user/browse.php">Browse Books</a>
        </div>
    <?php else: ?>
        <div class="user-book-grid">
            <?php foreach ($favorites as $book): ?>
                <?php $img = !empty($book['image']) ? $book['image'] : 'https://via.placeholder.com/300x400/7c3aed/ffffff?text=' . urlencode(substr((string) $book['title'], 0, 1)); ?>
                <article class="user-book-card card-surface">
                    <div class="user-book-cover">
                        <img src="<?php echo user_h($img); ?>" alt="<?php echo user_h($book['title']); ?>">
                    </div>
                    <div class="user-book-body">
                        <span class="user-chip"><?php echo user_h($book['category_name'] ?? 'Uncategorized'); ?></span>
                        <h3 class="user-book-title"><?php echo user_h($book['title']); ?></h3>
                        <p class="user-book-meta">by <?php echo user_h($book['author']); ?></p>
                        <p class="user-book-meta">Saved on <?php echo user_h(date('M d, Y', strtotime($book['favorited_at']))); ?></p>
                        <div class="user-actions">
                            <a class="btn-user btn-user-secondary" href="/library_project/dashboard/user/book-details.php?id=<?php echo (int) $book['id']; ?>">Details</a>
                            <button class="btn-user btn-user-secondary book-favorite-btn favorited" data-book-id="<?php echo (int) $book['id']; ?>">❤️ Remove</button>
                            <?php if (($book['type'] ?? '') === 'borrow' || ($book['type'] ?? '') === 'both'): ?>
                                <button class="btn-user btn-user-primary" onclick="borrowBook(<?php echo (int) $book['id']; ?>, this)">Borrow</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php user_render_layout_end(['/library_project/assets/js/books.js']);

