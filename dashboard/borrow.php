<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';
$email = $_SESSION['email'] ?? '';

// Fetch all borrowed books
$borrowed_query = "SELECT b.*, br.id as borrowing_id, br.borrow_date, br.return_date, br.status, c.name as category_name
                   FROM borrowings br
                   JOIN books b ON br.book_id = b.id
                   LEFT JOIN categories c ON b.category_id = c.id
                   WHERE br.user_id = ?
                   ORDER BY br.status ASC, br.borrow_date DESC";
$stmt = mysqli_prepare($conn, $borrowed_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$borrowed_result = mysqli_stmt_get_result($stmt);
$borrowed_books = [];
while ($row = mysqli_fetch_assoc($borrowed_result)) {
    $borrowed_books[] = $row;
}
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">
<link rel="stylesheet" href="/library_project/assets/css/dashboard.css">

<?php include('../includes/navbar.php'); ?>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo strtoupper(substr($username, 0, 2)); ?>
            </div>
            <div class="sidebar-username"><?php echo htmlspecialchars($username); ?></div>
            <div class="sidebar-email"><?php echo htmlspecialchars($email); ?></div>
        </div>


            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/dashboard.php" class="sidebar-nav-link">
                    <i class="fas fa-home sidebar-nav-icon"></i>
                    Dashboard
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/borrow.php" class="sidebar-nav-link active">
                    <i class="fas fa-book sidebar-nav-icon"></i>
                    My Borrowed Books
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/favorits.php" class="sidebar-nav-link">
                    <i class="fas fa-heart sidebar-nav-icon"></i>
                    Favorites
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/profile.php" class="sidebar-nav-link">
                    <i class="fas fa-user sidebar-nav-icon"></i>
                    Profile
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/books/brows.php" class="sidebar-nav-link">
                    <i class="fas fa-search sidebar-nav-icon"></i>
                    Browse Books
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/auth/logout.php" class="sidebar-nav-link" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt sidebar-nav-icon"></i>
                    Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->


        <!-- PAGE HEADER -->
        <div class="page-header">

                <i class="fas fa-book"></i> My Borrowed Books
            </h1>
            <p class="page-subtitle">
                Manage your borrowed books and track return dates
            </p>
        </div>

        <!-- BORROWED BOOKS LIST -->
        <?php if (empty($borrowed_books)): ?>
            <div class="empty-state">

                <h3>No Borrowed Books</h3>
                <p>You haven't borrowed any books yet. Start exploring our collection!</p>
                <button class="btn-primary" onclick="window.location.href='/library_project/books/brows.php'">
                    <i class="fas fa-search"></i> Browse Books
                </button>
            </div>
        <?php else: ?>
            <div class="borrowed-list">
                <?php foreach ($borrowed_books as $item): ?>
                    <?php
                        $book_image = !empty($item['image']) ? $item['image'] : 'https://via.placeholder.com/100x140/7c3aed/ffffff?text=' . urlencode(substr($item['title'], 0, 1));

                        // Check if overdue
                        $is_overdue = false;
                        $status_class = 'borrowed';

                            $return_date = strtotime($item['return_date']);
                            $today = strtotime('today');
                            if ($return_date < $today) {
                                $is_overdue = true;
                                $status_class = 'overdue';
                            }
                        } elseif ($item['status'] == 'returned') {
                            $status_class = 'returned';
                        }

                        $days_until_due = '';
                        if ($item['status'] == 'borrowed' && $item['return_date']) {
                            $diff = floor((strtotime($item['return_date']) - time()) / (60 * 60 * 24));
                            if ($diff > 0) {

                            } elseif ($diff == 0) {
                                $days_until_due = "Due today";
                            } else {
                                $days_until_due = abs($diff) . " days overdue";
                            }
                        }
                    ?>
                    <div class="borrowed-item">
                        <div class="borrowed-item-image">
                            <img src="<?php echo htmlspecialchars($book_image); ?>"
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 onerror="this.src='https://via.placeholder.com/100x140/7c3aed/ffffff?text=No+Image'">
                        </div>
                        <div class="borrowed-item-info">
                            <img src="<?php echo htmlspecialchars($book_image); ?>"
                            <div class="borrowed-item-author">
                                <i class="fas fa-user-pen"></i> <?php echo htmlspecialchars($item['author']); ?>
                            </div>
                            <div class="borrowed-item-dates">
                                <div class="date-info">
                                    <span class="date-label"><i class="fas fa-calendar"></i> Borrowed:</span>
                                    <span class="date-value"><?php echo date('M d, Y', strtotime($item['borrow_date'])); ?></span>
                                </div>
                                <?php if ($item['return_date'] && $item['status'] == 'borrowed'): ?>
                                <div class="date-info">
                                    <span class="date-label"><i class="fas fa-clock"></i> Due:</span>
                                    <span class="date-value" style="<?php echo $is_overdue ? 'color: #ef4444; font-weight: 600;' : ''; ?>">
                                        <?php echo date('M d, Y', strtotime($item['return_date'])); ?>
                                        <?php if ($days_until_due): ?>
                                            <span style="font-size: 12px;">(<?php echo $days_until_due; ?>)</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="borrowed-item-actions">
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php
                                    if ($is_overdue) echo '<i class="fas fa-exclamation-triangle"></i> Overdue';
                                    else echo ucfirst($item['status']);
                                ?>
                            </span>
                            <?php if ($item['status'] == 'borrowed'): ?>
                                <?php
                                    <i class="fas fa-check"></i> Return Book
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>


    </main>


</div>

<?php include('../includes/footer.php'); ?>

<script src="/library_project/assets/js/books.js"></script>

