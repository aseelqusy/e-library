<?php
require_once __DIR__ . '/_layout.php';

$conn = user_db_conn();
user_require_member();
$user = user_current_member($conn);
$userId = (int) $user['id'];

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim((string) ($_POST['username'] ?? ''));
    $newEmail = trim((string) ($_POST['email'] ?? ''));

    if ($newUsername === '' || $newEmail === '') {
        $errorMessage = 'Username and email are required.';
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Please provide a valid email address.';
    } else {
        $checkStmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ? LIMIT 1');
        mysqli_stmt_bind_param($checkStmt, 'ssi', $newEmail, $newUsername, $userId);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $errorMessage = 'Email or username is already used by another account.';
        } else {
            $updateStmt = mysqli_prepare($conn, 'UPDATE users SET username = ?, email = ? WHERE id = ?');
            mysqli_stmt_bind_param($updateStmt, 'ssi', $newUsername, $newEmail, $userId);
            if (mysqli_stmt_execute($updateStmt)) {
                $_SESSION['username'] = $newUsername;
                $_SESSION['email'] = $newEmail;
                $successMessage = 'Profile updated successfully.';
            } else {
                $errorMessage = 'Unable to update profile right now.';
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = (string) ($_POST['current_password'] ?? '');
    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $errorMessage = 'Please fill all password fields.';
    } elseif ($newPassword !== $confirmPassword) {
        $errorMessage = 'New password and confirm password do not match.';
    } elseif (strlen($newPassword) < 6) {
        $errorMessage = 'New password must be at least 6 characters.';
    } else {
        $verifyStmt = mysqli_prepare($conn, 'SELECT password FROM users WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($verifyStmt, 'i', $userId);
        mysqli_stmt_execute($verifyStmt);
        $verifyResult = mysqli_stmt_get_result($verifyStmt);
        $verifyRow = $verifyResult ? mysqli_fetch_assoc($verifyResult) : null;
        $stored = (string) ($verifyRow['password'] ?? '');

        $valid = password_verify($currentPassword, $stored) || $currentPassword === $stored;
        if (!$valid) {
            $errorMessage = 'Current password is incorrect.';
        } else {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $pwdStmt = mysqli_prepare($conn, 'UPDATE users SET password = ? WHERE id = ?');
            mysqli_stmt_bind_param($pwdStmt, 'si', $hashed, $userId);
            if (mysqli_stmt_execute($pwdStmt)) {
                $successMessage = 'Password changed successfully.';
            } else {
                $errorMessage = 'Unable to change password right now.';
            }
        }
    }
}

$user = user_current_member($conn);

$stats = ['borrowed' => 0, 'favorites' => 0, 'reviews' => 0];
$statsMeta = [
    'borrowed' => ['table' => 'borrowings', 'sql' => "SELECT COUNT(*) AS total FROM borrowings WHERE user_id = ? AND status = 'borrowed'"],
    'favorites' => ['table' => 'favorites', 'sql' => 'SELECT COUNT(*) AS total FROM favorites WHERE user_id = ?'],
    'reviews' => ['table' => 'reviews', 'sql' => 'SELECT COUNT(*) AS total FROM reviews WHERE user_id = ?'],
];

foreach ($statsMeta as $key => $meta) {
    if (!user_table_exists($conn, $meta['table'])) {
        $stats[$key] = 0;
        continue;
    }

    $stmt = mysqli_prepare($conn, $meta['sql']);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $stats[$key] = (int) (($result ? mysqli_fetch_assoc($result) : [])['total'] ?? 0);
    }
}

user_render_layout_start('Your Profile', 'Manage account details, security settings, and your reading stats.', 'profile', $user);
?>

<section class="user-stats-grid">
    <article class="user-stat card-surface"><p class="user-stat-label">Borrowed</p><p class="user-stat-value"><?php echo (int) $stats['borrowed']; ?></p></article>
    <article class="user-stat card-surface"><p class="user-stat-label">Favorites</p><p class="user-stat-value"><?php echo (int) $stats['favorites']; ?></p></article>
    <article class="user-stat card-surface"><p class="user-stat-label">Reviews</p><p class="user-stat-value"><?php echo (int) $stats['reviews']; ?></p></article>
    <article class="user-stat card-surface"><p class="user-stat-label">Member Since</p><p class="user-stat-value" style="font-size:18px;"><?php echo user_h(date('M Y', strtotime($user['created_at']))); ?></p></article>
</section>

<section class="user-grid-2">
    <article class="user-panel card-surface">
        <h3>Edit Profile</h3>
        <?php if ($successMessage !== ''): ?><div class="user-alert success"><?php echo user_h($successMessage); ?></div><?php endif; ?>
        <?php if ($errorMessage !== ''): ?><div class="user-alert error"><?php echo user_h($errorMessage); ?></div><?php endif; ?>
        <form method="POST">
            <input type="hidden" name="update_profile" value="1">
            <div class="user-form-grid">
                <div class="user-field">
                    <label for="username">Username</label>
                    <input id="username" name="username" required value="<?php echo user_h($user['username']); ?>">
                </div>
                <div class="user-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required value="<?php echo user_h($user['email']); ?>">
                </div>
            </div>
            <div class="user-actions" style="margin-top:12px;">
                <button class="btn-user btn-user-primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            </div>
        </form>
    </article>

    <article class="user-panel card-surface">
        <h3>Change Password</h3>
        <form method="POST">
            <input type="hidden" name="change_password" value="1">
            <div class="user-field">
                <label for="current_password">Current Password</label>
                <input id="current_password" name="current_password" type="password" required>
            </div>
            <div class="user-form-grid" style="margin-top:10px;">
                <div class="user-field">
                    <label for="new_password">New Password</label>
                    <input id="new_password" name="new_password" type="password" required>
                </div>
                <div class="user-field">
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required>
                </div>
            </div>
            <div class="user-actions" style="margin-top:12px;">
                <button class="btn-user btn-user-secondary" type="submit"><i class="fa-solid fa-key"></i> Update Password</button>
            </div>
        </form>
    </article>
</section>

<?php user_render_layout_end();

