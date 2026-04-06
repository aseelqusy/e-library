<?php
require_once __DIR__ . '/../../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

if (isset($_GET['delete'])) {
    $reviewId = (int) $_GET['delete'];
    if ($reviewId > 0) {
        $stmt = mysqli_prepare($conn, 'DELETE FROM reviews WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $reviewId);
        mysqli_stmt_execute($stmt);
        admin_set_toast('Review deleted.', 'success');
    }

    header('Location: /library_project/dashboard/admin/reviews.php');
    exit();
}

$rating = (int) ($_GET['rating'] ?? 0);
$search = trim($_GET['search'] ?? '');
$where = [];
$params = [];
$types = '';

if ($rating >= 1 && $rating <= 5) {
    $where[] = 'r.rating = ?';
    $params[] = $rating;
    $types .= 'i';
}

if ($search !== '') {
    $where[] = '(u.username LIKE ? OR b.title LIKE ? OR r.comment LIKE ?)';
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}

$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$sql = "
    SELECT r.id, r.rating, r.comment, r.created_at,
           u.username, b.title
    FROM reviews r
    INNER JOIN users u ON u.id = r.user_id
    INNER JOIN books b ON b.id = r.book_id
    $whereSql
    ORDER BY r.created_at DESC
";

$stmt = mysqli_prepare($conn, $sql);
if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

admin_render_start('Reviews', 'reviews', 'Moderate feedback and monitor reader sentiment.');
?>
<section class="admin-card">
    <div class="toolbar">
        <form method="get" class="toolbar">
            <input type="search" class="admin-input" name="search" value="<?php echo admin_h($search); ?>" placeholder="Search by user, book, or review text" style="min-width: 280px;">
            <select name="rating" class="admin-select" style="min-width: 180px;">
                <option value="0">All Ratings</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?php echo $i; ?>" <?php echo $rating === $i ? 'selected' : ''; ?>><?php echo $i; ?> stars</option>
                <?php endfor; ?>
            </select>
            <button class="btn btn-secondary-soft" type="submit"><i class="fa-solid fa-filter"></i> Filter</button>
        </form>
    </div>

    <div class="table-wrap mt-3">
        <table class="admin-table" style="min-width: 980px;">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Book</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($review = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo admin_h($review['username']); ?></td>
                            <td><?php echo admin_h($review['title']); ?></td>
                            <td>
                                <span class="status-badge status-info">
                                    <?php echo str_repeat('★', (int) $review['rating']); ?>
                                </span>
                            </td>
                            <td><?php echo admin_h(mb_strimwidth((string) $review['comment'], 0, 120, '...')); ?></td>
                            <td><?php echo admin_h(date('M d, Y', strtotime($review['created_at']))); ?></td>
                            <td>
                                <a class="btn btn-danger-soft" href="?delete=<?php echo (int) $review['id']; ?>" data-confirm="Delete this review permanently?">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No reviews found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php admin_render_end(); ?>
