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

// Fetch user stats
$stats = [
    'total_borrowed' => 0,
    'currently_borrowed' => 0,
    'favorites' => 0,
    'reviews' => 0
];

// Total books borrowed (all time)
$total_borrowed_query = "SELECT COUNT(*) as count FROM borrowings WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $total_borrowed_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$stats['total_borrowed'] = mysqli_fetch_assoc($result)['count'];

// Currently borrowed books
$currently_borrowed_query = "SELECT COUNT(*) as count FROM borrowings WHERE user_id = ? AND status = 'borrowed'";
$stmt = mysqli_prepare($conn, $currently_borrowed_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$stats['currently_borrowed'] = mysqli_fetch_assoc($result)['count'];

// Favorites count
$favorites_query = "SELECT COUNT(*) as count FROM favorites WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $favorites_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$stats['favorites'] = mysqli_fetch_assoc($result)['count'];

// Reviews count
$reviews_query = "SELECT COUNT(*) as count FROM reviews WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $reviews_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$stats['reviews'] = mysqli_fetch_assoc($result)['count'];

// Recently borrowed books
$recent_borrowed_query = "SELECT b.*, br.borrow_date, br.return_date, br.status, c.name as category_name
                          FROM borrowings br
                          JOIN books b ON br.book_id = b.id
                          LEFT JOIN categories c ON b.category_id = c.id
                          WHERE br.user_id = ?
                          ORDER BY br.borrow_date DESC
                          LIMIT 3";
$stmt = mysqli_prepare($conn, $recent_borrowed_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$recent_borrowed_result = mysqli_stmt_get_result($stmt);
$recent_borrowed = [];
while ($row = mysqli_fetch_assoc($recent_borrowed_result)) {
    $recent_borrowed[] = $row;
}

// Recommended books (random selection for now)
$recommended_query = "SELECT b.*, c.name as category_name
                      FROM books b
                      LEFT JOIN categories c ON b.category_id = c.id
                      WHERE (b.type = 'borrow' OR b.type = 'both')
                      ORDER BY RAND()
                      LIMIT 4";
$recommended_result = mysqli_query($conn, $recommended_query);
$recommended = [];
while ($row = mysqli_fetch_assoc($recommended_result)) {
    $recommended[] = $row;
}

// Get user favorites
$user_favorites = [];
$fav_query = "SELECT book_id FROM favorites WHERE user_id = ?";
$fav_stmt = mysqli_prepare($conn, $fav_query);
mysqli_stmt_bind_param($fav_stmt, "i", $user_id);
mysqli_stmt_execute($fav_stmt);
$fav_result = mysqli_stmt_get_result($fav_stmt);
while ($row = mysqli_fetch_assoc($fav_result)) {
    $user_favorites[] = $row['book_id'];
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
                <a href="/library_project/dashboard/dashboard.php" class="sidebar-nav-link active">
                    <i class="fas fa-home sidebar-nav-icon"></i>
                    Dashboard
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/borrow.php" class="sidebar-nav-link">
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


        <!-- WELCOME BANNER -->
        <div class="welcome-banner">

                <h1>Welcome back, <?php echo htmlspecialchars($username); ?>! 👋</h1>
                <p>Ready to continue your reading journey? Explore new books or pick up where you left off.</p>
            </div>
        </div>

        <!-- STATS GRID -->
        <div class="stats-grid">
            <div class="stat-card">

                    <span class="stat-label">Total Books Read</span>
                    <div class="stat-icon purple-gradient">📚</div>
                </div>
                <div class="stat-value"><?php echo $stats['total_borrowed']; ?></div>
                <div class="stat-description">All-time borrowed books</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-label">Currently Reading</span>

                </div>
                <div class="stat-value"><?php echo $stats['currently_borrowed']; ?></div>
                <div class="stat-description">Books you're reading now</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-label">Favorites</span>
                    <div class="stat-icon green-gradient">❤️</div>
                </div>

                <div class="stat-description">Books you saved</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-label">Reviews Written</span>
                    <div class="stat-icon blue-gradient">⭐</div>
                </div>
                <div class="stat-value"><?php echo $stats['reviews']; ?></div>

            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="dashboard-section">
            <div class="dashboard-section-header">
                <h2 class="dashboard-section-title">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h2>
            </div>

            <div class="quick-actions">
                <a href="/library_project/books/brows.php" class="quick-action-btn">

                        <i class="fas fa-search"></i>
                    </div>
                    <div class="quick-action-text">
                        <h4>Browse Books</h4>
                        <p>Explore our collection</p>
                    </div>
                </a>


                    <div class="quick-action-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="quick-action-text">
                        <h4>My Borrows</h4>
                        <p>View borrowed books</p>
                    </div>
                </a>

                <a href="/library_project/dashboard/favorits.php" class="quick-action-btn">
                    <div class="quick-action-icon">

                    </div>
                    <div class="quick-action-text">
                        <h4>My Favorites</h4>
                        <p>See saved books</p>
                    </div>
                </a>

                <a href="/library_project/dashboard/profile.php" class="quick-action-btn">
                    <div class="quick-action-icon">
                        <i class="fas fa-user"></i>
                    </div>

                        <h4>Edit Profile</h4>
                        <p>Update your info</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- RECENTLY BORROWED -->
        <?php if (!empty($recent_borrowed)): ?>
        <div class="dashboard-section">
            <div class="dashboard-section-header">
                <h2 class="dashboard-section-title">

                </h2>
                <a href="/library_project/dashboard/borrow.php" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="borrowed-list">
                <?php foreach ($recent_borrowed as $item): ?>
                    <?php


                        // Check if overdue
                        $is_overdue = false;
                        $status_class = 'borrowed';
                        if ($item['status'] == 'borrowed' && $item['return_date']) {
                            $return_date = strtotime($item['return_date']);
                            $today = strtotime('today');
                            if ($return_date < $today) {
                                $is_overdue = true;
                                $status_class = 'overdue';
                            }
                        } elseif ($item['status'] == 'returned') {
                            $status_class = 'returned';

                    ?>
                    <div class="borrowed-item">
                        <div class="borrowed-item-image">
                            <img src="<?php echo htmlspecialchars($book_image); ?>"
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"

                        </div>
                        <div class="borrowed-item-info">
                            <h3 class="borrowed-item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <div class="borrowed-item-author"><?php echo htmlspecialchars($item['author']); ?></div>
                            <div class="borrowed-item-dates">
                                <div class="date-info">
                                    <span class="date-label">Borrowed:</span>
                                    <span class="date-value"><?php echo date('M d, Y', strtotime($item['borrow_date'])); ?></span>
                                </div>
                                <?php if ($item['return_date']): ?>
                                <div class="date-info">
                                    <span class="date-label">Due:</span>
                            <img src="<?php echo htmlspecialchars($book_image); ?>"
                                        <?php echo date('M d, Y', strtotime($item['return_date'])); ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="borrowed-item-actions">
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo $is_overdue ? 'Overdue' : ucfirst($item['status']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- RECOMMENDED BOOKS -->
        <?php if (!empty($recommended)): ?>
        <div class="dashboard-section">
            <div class="dashboard-section-header">
                <h2 class="dashboard-section-title">
                    <i class="fas fa-sparkles"></i> Recommended for You
                </h2>
            </div>

            <div class="books-grid">
                <?php foreach ($recommended as $book): ?>
                    <?php
                        $is_favorited = in_array($book['id'], $user_favorites);
                        $book_image = !empty($book['image']) ? $book['image'] : 'https://via.placeholder.com/300x400/7c3aed/ffffff?text=' . urlencode(substr($book['title'], 0, 1));
                    ?>
                    <div class="book-card">
                        <div class="book-card-image">
                            <img src="<?php echo htmlspecialchars($book_image); ?>"
                                 alt="<?php echo htmlspecialchars($book['title']); ?>"
                                 onerror="this.src='https://via.placeholder.com/300x400/7c3aed/ffffff?text=No+Image'">

                            <button class="book-favorite-btn <?php echo $is_favorited ? 'favorited' : ''; ?>"
                                    data-book-id="<?php echo $book['id']; ?>">
                                <?php echo $is_favorited ? '❤️' : '🤍'; ?>
                            </button>
                        </div>
                        <div class="book-card-body">

                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($book['category_name'] ?? 'Uncategorized'); ?>
                            </div>
                            <h3 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <div class="book-author">
                                <i class="fas fa-user-pen"></i>
                                <?php echo htmlspecialchars($book['author']); ?>
                            </div>
                            <p class="book-description">
                            <img src="<?php echo htmlspecialchars($book_image); ?>"
                            </p>
                            <div class="book-rating">
                                <span class="stars">★★★★★</span>
                                <span class="rating-text">4.8</span>
                            </div>
                            <button class="book-favorite-btn <?php echo $is_favorited ? 'favorited' : ''; ?>"
                                <a href="/library_project/books/detaills.php?id=<?php echo $book['id']; ?>" class="btn-view-details">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <button class="btn-icon-only" onclick="borrowBook(<?php echo $book['id']; ?>, this)" title="Borrow Book">
                                    <i class="fas fa-book"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>


    </main>


</div>

<?php include('../includes/footer.php'); ?>

<script src="/library_project/assets/js/books.js"></script>
