<?php
require_once __DIR__ . '/../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

$stats = [
    'books' => 0,
    'users' => 0,
    'requests' => 0,
    'categories' => 0,
];

$queries = [
    'books' => 'SELECT COUNT(*) AS total FROM books',
    'users' => "SELECT COUNT(*) AS total FROM users WHERE role = 'user'",
    'requests' => "SELECT COUNT(*) AS total FROM borrowings WHERE status = 'borrowed'",
    'categories' => 'SELECT COUNT(*) AS total FROM categories',
];

foreach ($queries as $key => $query) {
    $result = mysqli_query($conn, $query);
    if ($result) {
        $stats[$key] = (int) (mysqli_fetch_assoc($result)['total'] ?? 0);
    }
}

$activitySql = "
    SELECT b.title, u.username, br.status, br.borrow_date
    FROM borrowings br
    INNER JOIN books b ON b.id = br.book_id
    INNER JOIN users u ON u.id = br.user_id
    ORDER BY br.borrow_date DESC
    LIMIT 8
";
$activityResult = mysqli_query($conn, $activitySql);

admin_render_start('Admin Dashboard', 'dashboard', 'Welcome back. Here is today\'s library pulse.');
?>
<div class="admin-grid cols-4">
    <article class="admin-card">
        <div class="admin-card-header">
            <div class="admin-kpi">
                <span class="admin-kpi-label">Total Books</span>
                <strong class="admin-kpi-value"><?php echo number_format($stats['books']); ?></strong>
            </div>
            <span class="admin-kpi-icon"><i class="fa-solid fa-book"></i></span>
        </div>
        <span class="status-badge status-info">+12 this month</span>
    </article>

    <article class="admin-card">
        <div class="admin-card-header">
            <div class="admin-kpi">
                <span class="admin-kpi-label">Total Users</span>
                <strong class="admin-kpi-value"><?php echo number_format($stats['users']); ?></strong>
            </div>
            <span class="admin-kpi-icon"><i class="fa-solid fa-users"></i></span>
        </div>
        <span class="status-badge status-success">Active community</span>
    </article>

    <article class="admin-card">
        <div class="admin-card-header">
            <div class="admin-kpi">
                <span class="admin-kpi-label">Borrow Requests</span>
                <strong class="admin-kpi-value"><?php echo number_format($stats['requests']); ?></strong>
            </div>
            <span class="admin-kpi-icon"><i class="fa-solid fa-clock-rotate-left"></i></span>
        </div>
        <span class="status-badge status-warning">Needs review</span>
    </article>

    <article class="admin-card">
        <div class="admin-card-header">
            <div class="admin-kpi">
                <span class="admin-kpi-label">Categories</span>
                <strong class="admin-kpi-value"><?php echo number_format($stats['categories']); ?></strong>
            </div>
            <span class="admin-kpi-icon"><i class="fa-solid fa-layer-group"></i></span>
        </div>
        <span class="status-badge status-info">Organized catalog</span>
    </article>
</div>

<section class="admin-card mt-3">
    <div class="admin-card-header">
        <h3 class="m-0">Quick Actions</h3>
    </div>
    <div class="quick-actions">
        <a class="quick-action" href="/library_project/dashboard/add-book.php"><i class="fa-solid fa-plus"></i><span>Add Book</span></a>
        <a class="quick-action" href="/library_project/dashboard/books.php"><i class="fa-solid fa-table"></i><span>Manage Books</span></a>
        <a class="quick-action" href="/library_project/dashboard/users.php"><i class="fa-solid fa-user-gear"></i><span>Manage Users</span></a>
        <a class="quick-action" href="/library_project/dashboard/settings.php"><i class="fa-solid fa-sliders"></i><span>Platform Settings</span></a>
    </div>
</section>

<div class="admin-grid cols-2 mt-3">
    <section class="admin-card">
        <div class="admin-card-header">
            <h3 class="m-0">Recent Activity</h3>
        </div>
        <div class="table-wrap">
            <table class="admin-table" style="min-width:640px;">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Book</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($activityResult && mysqli_num_rows($activityResult) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($activityResult)): ?>
                        <tr>
                            <td><?php echo admin_h($row['username']); ?></td>
                            <td><?php echo admin_h($row['title']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $row['status'] === 'borrowed' ? 'status-warning' : 'status-success'; ?>">
                                    <?php echo admin_h(ucfirst($row['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo admin_h(date('M d, Y H:i', strtotime($row['borrow_date']))); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-4">No recent activity available.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <h3 class="m-0">Borrow Trend</h3>
        </div>
        <div class="chart-placeholder">
            <p class="mb-2"><i class="fa-solid fa-chart-column fa-xl"></i></p>
            <p class="mb-0">Chart area placeholder (connect Chart.js later).</p>
        </div>
    </section>
</div>

<?php admin_render_end(); ?>
