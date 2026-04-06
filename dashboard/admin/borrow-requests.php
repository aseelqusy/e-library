<?php
require_once __DIR__ . '/../../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

if (isset($_GET['return'])) {
    $borrowingId = (int) $_GET['return'];
    if ($borrowingId > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE borrowings SET status = 'returned', return_date = CURDATE() WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $borrowingId);
        mysqli_stmt_execute($stmt);
        admin_set_toast('Borrow record marked as returned.', 'success');
    }

    header('Location: /library_project/dashboard/admin/borrow-requests.php');
    exit();
}

$status = $_GET['status'] ?? 'all';
$whereSql = '';
$params = [];
$types = '';

if (in_array($status, ['borrowed', 'returned'], true)) {
    $whereSql = 'WHERE br.status = ?';
    $params[] = $status;
    $types = 's';
}

$sql = "
    SELECT br.id, br.borrow_date, br.return_date, br.status,
           u.username, u.email,
           b.title, b.author
    FROM borrowings br
    INNER JOIN users u ON u.id = br.user_id
    INNER JOIN books b ON b.id = br.book_id
    $whereSql
    ORDER BY br.borrow_date DESC
";

$stmt = mysqli_prepare($conn, $sql);
if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

admin_render_start('Borrow Requests', 'borrow-requests', 'Track active lending and process returned books quickly.');
?>
<section class="admin-card">
    <div class="toolbar">
        <a class="btn <?php echo $status === 'all' ? 'btn-primary-soft' : 'btn-secondary-soft'; ?>" href="?status=all">All</a>
        <a class="btn <?php echo $status === 'borrowed' ? 'btn-primary-soft' : 'btn-secondary-soft'; ?>" href="?status=borrowed">Borrowed</a>
        <a class="btn <?php echo $status === 'returned' ? 'btn-primary-soft' : 'btn-secondary-soft'; ?>" href="?status=returned">Returned</a>
    </div>

    <div class="table-wrap mt-3">
        <table class="admin-table" style="min-width: 920px;">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Book</th>
                    <th>Borrowed On</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <strong><?php echo admin_h($row['username']); ?></strong><br>
                                <small class="text-muted"><?php echo admin_h($row['email']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo admin_h($row['title']); ?></strong><br>
                                <small class="text-muted">by <?php echo admin_h($row['author']); ?></small>
                            </td>
                            <td><?php echo admin_h(date('M d, Y', strtotime($row['borrow_date']))); ?></td>
                            <td><?php echo $row['return_date'] ? admin_h(date('M d, Y', strtotime($row['return_date']))) : '-'; ?></td>
                            <td>
                                <span class="status-badge <?php echo $row['status'] === 'borrowed' ? 'status-warning' : 'status-success'; ?>">
                                    <?php echo admin_h(ucfirst($row['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['status'] === 'borrowed'): ?>
                                    <a class="btn btn-primary-soft" href="?return=<?php echo (int) $row['id']; ?>" data-confirm="Mark this book as returned?">Mark Returned</a>
                                <?php else: ?>
                                    <span class="status-badge status-success">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No borrow requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php admin_render_end(); ?>
