<?php
require_once __DIR__ . '/../../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

if (isset($_GET['promote'])) {
    $userId = (int) $_GET['promote'];
    if ($userId > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET role = 'staff' WHERE id = ? AND role = 'user'");
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        admin_set_toast('User promoted to staff.', 'success');
    }
    header('Location: /library_project/dashboard/admin/users.php');
    exit();
}

if (isset($_GET['delete'])) {
    $userId = (int) $_GET['delete'];
    if ($userId > 0 && $userId !== (int) $_SESSION['user_id']) {
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role <> 'admin'");
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        admin_set_toast('User removed.', 'success');
    }
    header('Location: /library_project/dashboard/admin/users.php');
    exit();
}

$search = trim($_GET['search'] ?? '');
$sql = 'SELECT u.id, u.username, u.email, u.role, u.created_at, COUNT(br.id) AS borrow_count FROM users u LEFT JOIN borrowings br ON br.user_id = u.id ';
$params = [];
$types = '';

if ($search !== '') {
    $sql .= 'WHERE (u.username LIKE ? OR u.email LIKE ?) ';
    $like = '%' . $search . '%';
    $params = [$like, $like];
    $types = 'ss';
}

$sql .= 'GROUP BY u.id ORDER BY u.created_at DESC';
$stmt = mysqli_prepare($conn, $sql);
if ($types !== '') {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$usersResult = mysqli_stmt_get_result($stmt);

admin_render_start('Users', 'users', 'Manage students, staff roles, and account lifecycle.');
?>
<section class="admin-card">
    <div class="toolbar">
        <form class="toolbar" method="get" action="">
            <input class="admin-input" type="search" name="search" value="<?php echo admin_h($search); ?>" placeholder="Search username or email" style="min-width:260px;">
            <button class="btn btn-secondary-soft" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form>
    </div>

    <div class="table-wrap mt-3">
        <table class="admin-table" style="min-width:900px;">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Borrows</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($usersResult && mysqli_num_rows($usersResult) > 0): ?>
                <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                    <tr>
                        <td><?php echo admin_h($user['username']); ?></td>
                        <td><?php echo admin_h($user['email']); ?></td>
                        <td>
                            <?php $roleClass = $user['role'] === 'admin' ? 'status-info' : ($user['role'] === 'staff' ? 'status-warning' : 'status-success'); ?>
                            <span class="status-badge <?php echo $roleClass; ?>"><?php echo admin_h(strtoupper($user['role'])); ?></span>
                        </td>
                        <td><?php echo (int) $user['borrow_count']; ?></td>
                        <td><?php echo admin_h(date('M d, Y', strtotime($user['created_at']))); ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <?php if ($user['role'] === 'user'): ?>
                                    <a class="btn btn-secondary-soft" href="?promote=<?php echo (int) $user['id']; ?>">Promote</a>
                                <?php endif; ?>
                                <?php if ($user['role'] !== 'admin' && (int) $user['id'] !== (int) $_SESSION['user_id']): ?>
                                    <a class="btn btn-danger-soft" href="?delete=<?php echo (int) $user['id']; ?>" data-confirm="Delete this user account and related records?">Delete</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center py-4">No users found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php admin_render_end(); ?>
