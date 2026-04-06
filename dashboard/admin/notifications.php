<?php
require_once __DIR__ . '/../../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

if (!isset($_SESSION['admin_notifications_read'])) {
    $_SESSION['admin_notifications_read'] = [];
}

if (isset($_GET['mark']) && $_GET['mark'] === 'all') {
    $_SESSION['admin_notifications_read'] = ['users' => true, 'borrows' => true, 'reviews' => true, 'stock' => true];
    admin_set_toast('All notifications marked as read.', 'success');
    header('Location: /library_project/dashboard/admin/notifications.php');
    exit();
}

$notifications = [];

$newUsersResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$newUsers = (int) (mysqli_fetch_assoc($newUsersResult)['total'] ?? 0);
if ($newUsers > 0) {
    $notifications[] = ['key' => 'users', 'title' => 'New registrations this week', 'message' => "$newUsers new users joined the platform.", 'type' => 'info'];
}

$pendingResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM borrowings WHERE status = 'borrowed'");
$pending = (int) (mysqli_fetch_assoc($pendingResult)['total'] ?? 0);
if ($pending > 0) {
    $notifications[] = ['key' => 'borrows', 'title' => 'Active borrow requests', 'message' => "$pending books are currently borrowed.", 'type' => 'warning'];
}

$newReviewsResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reviews WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)");
$newReviews = (int) (mysqli_fetch_assoc($newReviewsResult)['total'] ?? 0);
if ($newReviews > 0) {
    $notifications[] = ['key' => 'reviews', 'title' => 'Recent reviews posted', 'message' => "$newReviews new reviews are waiting for moderation.", 'type' => 'info'];
}

$lowStockResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM stock WHERE quantity <= 2");
$lowStock = (int) (mysqli_fetch_assoc($lowStockResult)['total'] ?? 0);
if ($lowStock > 0) {
    $notifications[] = ['key' => 'stock', 'title' => 'Low stock alert', 'message' => "$lowStock book entries need restocking.", 'type' => 'danger'];
}

admin_render_start('Notifications', 'notifications', 'System alerts and administrative updates in one place.');
?>
<section class="admin-card">
    <div class="toolbar">
        <a href="?mark=all" class="btn btn-secondary-soft"><i class="fa-solid fa-check-double"></i> Mark All Read</a>
    </div>

    <div class="admin-grid cols-2 mt-3">
        <?php if ($notifications): ?>
            <?php foreach ($notifications as $item): ?>
                <?php $read = !empty($_SESSION['admin_notifications_read'][$item['key']]); ?>
                <?php $badgeClass = $item['type'] === 'danger' ? 'status-danger' : ($item['type'] === 'warning' ? 'status-warning' : 'status-info'); ?>
                <article class="admin-card" style="margin:0;">
                    <div class="admin-card-header">
                        <h3 class="m-0" style="font-size:1rem;"><?php echo admin_h($item['title']); ?></h3>
                        <span class="status-badge <?php echo $read ? 'status-success' : $badgeClass; ?>"><?php echo $read ? 'Read' : 'New'; ?></span>
                    </div>
                    <p class="mb-0 text-muted"><?php echo admin_h($item['message']); ?></p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <article class="admin-card" style="grid-column:1 / -1; margin:0;">
                <h3 class="m-0 mb-2">All clear</h3>
                <p class="mb-0 text-muted">No new notifications at the moment.</p>
            </article>
        <?php endif; ?>
    </div>
</section>
<?php admin_render_end(); ?>
