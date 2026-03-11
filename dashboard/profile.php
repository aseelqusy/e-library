<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once '../includes/db.php';

$conn = $GLOBALS['conn'] ?? (isset($conn) ? $conn : null);
if (!($conn instanceof mysqli)) {
    die('Database connection is not available.');
}

$user_id = (int) $_SESSION['user_id'];
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');

    if ($new_username === '' || $new_email === '') {
        $error_message = 'Username and email are required.';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } else {
        $check_stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ? LIMIT 1');
        mysqli_stmt_bind_param($check_stmt, 'ssi', $new_email, $new_username, $user_id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $error_message = 'Email or username is already used by another account.';
        } else {
            $update_stmt = mysqli_prepare($conn, 'UPDATE users SET username = ?, email = ? WHERE id = ?');
            mysqli_stmt_bind_param($update_stmt, 'ssi', $new_username, $new_email, $user_id);
            if (mysqli_stmt_execute($update_stmt)) {
                $_SESSION['username'] = $new_username;
                $_SESSION['email'] = $new_email;
                $success_message = 'Profile updated successfully.';
            } else {
                $error_message = 'Failed to update profile.';
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = (string) ($_POST['current_password'] ?? '');
    $new_password = (string) ($_POST['new_password'] ?? '');
    $confirm_password = (string) ($_POST['confirm_password'] ?? '');

    if ($current_password === '' || $new_password === '' || $confirm_password === '') {
        $error_message = 'All password fields are required.';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'New passwords do not match.';
    } elseif (strlen($new_password) < 6) {
        $error_message = 'Password must be at least 6 characters.';
    } else {
        $verify_stmt = mysqli_prepare($conn, 'SELECT password FROM users WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($verify_stmt, 'i', $user_id);
        mysqli_stmt_execute($verify_stmt);
        $verify_result = mysqli_stmt_get_result($verify_stmt);
        $row = $verify_result ? mysqli_fetch_assoc($verify_result) : null;
        $stored = $row['password'] ?? '';

        $verified = password_verify($current_password, $stored) || ($current_password === $stored);
        if (!$verified) {
            $error_message = 'Current password is incorrect.';
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $pwd_stmt = mysqli_prepare($conn, 'UPDATE users SET password = ? WHERE id = ?');
            mysqli_stmt_bind_param($pwd_stmt, 'si', $hashed, $user_id);
            if (mysqli_stmt_execute($pwd_stmt)) {
                $success_message = 'Password changed successfully.';
            } else {
                $error_message = 'Failed to change password.';
            }
        }
    }
}

$user_stmt = mysqli_prepare($conn, 'SELECT username, email, role, created_at FROM users WHERE id = ? LIMIT 1');
mysqli_stmt_bind_param($user_stmt, 'i', $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    session_destroy();
    header('Location: ../auth/login.php');
    exit();
}

$stats = [
    'borrowed' => 0,
    'favorites' => 0,
    'reviews' => 0,
];

function user_table_count($conn, $table, $user_id)
{
    $table_escaped = mysqli_real_escape_string($conn, $table);
    $exists_result = mysqli_query($conn, "SHOW TABLES LIKE '$table_escaped'");
    if (!$exists_result || mysqli_num_rows($exists_result) === 0) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM `$table` WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return 0;
    }

    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = $result ? mysqli_fetch_assoc($result) : null;

    return (int) ($row['total'] ?? 0);
}

$stats['borrowed'] = user_table_count($conn, 'borrowings', $user_id);
$stats['favorites'] = user_table_count($conn, 'favorites', $user_id);
$stats['reviews'] = user_table_count($conn, 'reviews', $user_id);

$username = $user['username'];
$email = $user['email'];
$role = $user['role'];
$created_at = $user['created_at'];

include('../includes/header.php');
?>
<link rel="stylesheet" href="/library_project/assets/css/dashboard.css">
<?php include('../includes/navbar.php'); ?>

<div class="dashboard-wrapper">
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar"><?php echo strtoupper(substr($username, 0, 2)); ?></div>
            <div class="sidebar-username"><?php echo htmlspecialchars($username); ?></div>
            <div class="sidebar-email"><?php echo htmlspecialchars($email); ?></div>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item"><a href="/library_project/dashboard/profile.php" class="sidebar-nav-link active"><i class="fas fa-user sidebar-nav-icon"></i> Profile</a></li>
            <li class="sidebar-nav-item"><a href="/library_project/dashboard/borrow.php" class="sidebar-nav-link"><i class="fas fa-book sidebar-nav-icon"></i> My Borrowed Books</a></li>
            <li class="sidebar-nav-item"><a href="/library_project/dashboard/favorits.php" class="sidebar-nav-link"><i class="fas fa-heart sidebar-nav-icon"></i> Favorites</a></li>
            <li class="sidebar-nav-item"><a href="/library_project/books/brows.php" class="sidebar-nav-link"><i class="fas fa-search sidebar-nav-icon"></i> Browse Books</a></li>
            <li class="sidebar-nav-item"><a href="/library_project/auth/logout.php" class="sidebar-nav-link" style="color:#ef4444;"><i class="fas fa-sign-out-alt sidebar-nav-icon"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="dashboard-main">
        <div class="container" style="max-width:980px;">
            <h1 class="page-title" style="margin: 16px 0;">My Profile</h1>

            <?php if ($success_message !== ''): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if ($error_message !== ''): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <div class="stats-grid" style="margin-bottom:16px;">
                <div class="stat-card"><div class="stat-label">Borrowed</div><div class="stat-value"><?php echo $stats['borrowed']; ?></div></div>
                <div class="stat-card"><div class="stat-label">Favorites</div><div class="stat-value"><?php echo $stats['favorites']; ?></div></div>
                <div class="stat-card"><div class="stat-label">Reviews</div><div class="stat-value"><?php echo $stats['reviews']; ?></div></div>
                <div class="stat-card"><div class="stat-label">Role</div><div class="stat-value"><?php echo htmlspecialchars(ucfirst($role)); ?></div></div>
            </div>

            <div class="profile-card" style="margin-bottom:16px;">
                <h3>Edit Profile</h3>
                <form method="POST">
                    <input type="hidden" name="update_profile" value="1">
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-input" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-input" id="email" type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <button class="btn-save" type="submit"><i class="fas fa-save"></i> Save Changes</button>
                </form>
            </div>

            <div class="profile-card">
                <h3>Change Password</h3>
                <form method="POST">
                    <input type="hidden" name="change_password" value="1">
                    <div class="form-group">
                        <label class="form-label" for="current_password">Current Password</label>
                        <input class="form-input" id="current_password" type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="new_password">New Password</label>
                        <input class="form-input" id="new_password" type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                        <input class="form-input" id="confirm_password" type="password" name="confirm_password" required>
                    </div>
                    <button class="btn-save" type="submit"><i class="fas fa-key"></i> Update Password</button>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include('../includes/footer.php'); ?>
