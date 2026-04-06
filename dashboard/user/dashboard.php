<?php
require_once __DIR__ . '/_layout.php';

$conn = user_db_conn();
user_require_member();
$user = user_current_member($conn);
$userId = (int) $user['id'];

$stats = [
    'borrowed' => 0,
    'favorites' => 0,
    'reviews' => 0,
    'books' => 0,
];

$statsSql = [
    'borrowed' => user_table_exists($conn, 'borrowings') ? "SELECT COUNT(*) AS total FROM borrowings WHERE user_id = ? AND status = 'borrowed'" : null,
    'favorites' => user_table_exists($conn, 'favorites') ? 'SELECT COUNT(*) AS total FROM favorites WHERE user_id = ?' : null,
    'reviews' => user_table_exists($conn, 'reviews') ? 'SELECT COUNT(*) AS total FROM reviews WHERE user_id = ?' : null,
    'books' => user_table_exists($conn, 'books') ? 'SELECT COUNT(*) AS total FROM books' : null,
];

foreach ($statsSql as $key => $sql) {
    if ($sql === null) {
        $stats[$key] = 0;
        continue;
    }

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        continue;
    }

    if ($key === 'books') {
        mysqli_stmt_execute($stmt);
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
    }

    $result = mysqli_stmt_get_result($stmt);
    $stats[$key] = (int) (($result ? mysqli_fetch_assoc($result) : [])['total'] ?? 0);
}

$recentBorrowings = [];
if (user_table_exists($conn, 'borrowings') && user_table_exists($conn, 'books')) {
    $recentSql = "
        SELECT br.id, br.borrow_date, br.return_date, b.title
        FROM borrowings br
        INNER JOIN books b ON b.id = br.book_id
        WHERE br.user_id = ?
        ORDER BY br.borrow_date DESC
        LIMIT 5
    ";
    $recentStmt = mysqli_prepare($conn, $recentSql);
    if ($recentStmt) {
        mysqli_stmt_bind_param($recentStmt, 'i', $userId);
        mysqli_stmt_execute($recentStmt);
        $recentResult = mysqli_stmt_get_result($recentStmt);
        while ($recentResult && $row = mysqli_fetch_assoc($recentResult)) {
            $recentBorrowings[] = $row;
        }
    }
}

$suggestedBooks = [];
if (user_table_exists($conn, 'books')) {
    $suggestSql = "
        SELECT b.id, b.title, b.author, b.image, c.name AS category_name
        FROM books b
        LEFT JOIN categories c ON c.id = b.category_id
        ORDER BY b.created_at DESC
        LIMIT 3
    ";
    $suggestResult = mysqli_query($conn, $suggestSql);
    while ($suggestResult && $row = mysqli_fetch_assoc($suggestResult)) {
        $suggestedBooks[] = $row;
    }
}

user_render_layout_start('Welcome back, ' . ($user['username'] ?? 'Reader') . '!', 'Track your activity, explore books, and keep your reading flow smooth.', 'dashboard', $user);
?>

<section class="user-stats-grid">
    <article class="user-stat card-surface">
        <p class="user-stat-label">Currently Borrowed</p>
        <p class="user-stat-value"><?php echo number_format($stats['borrowed']); ?></p>
    </article>
    <article class="user-stat card-surface">
        <p class="user-stat-label">Favorite Books</p>
        <p class="user-stat-value"><?php echo number_format($stats['favorites']); ?></p>
    </article>
    <article class="user-stat card-surface">
        <p class="user-stat-label">Reviews Written</p>
        <p class="user-stat-value"><?php echo number_format($stats['reviews']); ?></p>
    </article>
    <article class="user-stat card-surface">
        <p class="user-stat-label">Library Collection</p>
        <p class="user-stat-value"><?php echo number_format($stats['books']); ?></p>
    </article>
</section>

<section class="user-grid-2">
    <article class="user-panel card-surface">
        <h3>Recent Borrowing</h3>
        <div class="user-list">
            <?php if (empty($recentBorrowings)): ?>
                <p class="user-empty">No borrowing activity yet. Start browsing to borrow your first book.</p>
            <?php else: ?>
                <?php foreach ($recentBorrowings as $item): ?>
                    <div class="user-list-item">
                        <div>
                            <strong><?php echo user_h($item['title']); ?></strong><br>
                            <small>Borrowed on <?php echo user_h(date('M d, Y', strtotime($item['borrow_date']))); ?></small>
                        </div>
                        <span><?php echo user_h(date('M d', strtotime($item['return_date']))); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </article>

    <article class="user-panel card-surface">
        <h3>Quick Actions</h3>
        <div class="user-actions" style="margin-bottom:12px;">
            <a class="btn-user btn-user-primary" href="/library_project/dashboard/user/browse.php"><i class="fa-solid fa-magnifying-glass"></i> Browse Books</a>
            <a class="btn-user btn-user-secondary" href="/library_project/dashboard/user/favorites.php"><i class="fa-solid fa-heart"></i> View Favorites</a>
            <a class="btn-user btn-user-secondary" href="/library_project/dashboard/user/profile.php"><i class="fa-solid fa-user"></i> Edit Profile</a>
        </div>
        <p class="user-book-meta">You joined on <?php echo user_h(date('F d, Y', strtotime($user['created_at']))); ?>.</p>
    </article>
</section>

<section class="user-panel card-surface">
    <h3>Suggested For You</h3>
    <div class="user-book-grid">
        <?php foreach ($suggestedBooks as $book): ?>
            <?php $img = !empty($book['image']) ? $book['image'] : 'https://via.placeholder.com/300x400/7c3aed/ffffff?text=' . urlencode(substr((string) $book['title'], 0, 1)); ?>
            <article class="user-book-card card-surface">
                <div class="user-book-cover">
                    <img src="<?php echo user_h($img); ?>" alt="<?php echo user_h($book['title']); ?>">
                </div>
                <div class="user-book-body">
                    <span class="user-chip"><?php echo user_h($book['category_name'] ?? 'Uncategorized'); ?></span>
                    <h4 class="user-book-title"><?php echo user_h($book['title']); ?></h4>
                    <p class="user-book-meta">by <?php echo user_h($book['author']); ?></p>
                    <div class="user-actions">
                        <a class="btn-user btn-user-secondary" href="/library_project/dashboard/user/book-details.php?id=<?php echo (int) $book['id']; ?>">Details</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php user_render_layout_end();

