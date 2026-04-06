<?php
require_once __DIR__ . '/_layout.php';

$conn = user_db_conn();
user_require_member();
$user = user_current_member($conn);
$userId = (int) $user['id'];

$searchQuery = trim((string) ($_GET['q'] ?? ''));
$selectedCategories = isset($_GET['categories']) && is_array($_GET['categories']) ? $_GET['categories'] : [];
$selectedAvailability = isset($_GET['availability']) && is_array($_GET['availability']) ? $_GET['availability'] : [];

$hasBooksTable = user_table_exists($conn, 'books');
$hasCategoriesTable = user_table_exists($conn, 'categories');
$hasFavoritesTable = user_table_exists($conn, 'favorites');

$categoryRows = [];
if ($hasCategoriesTable) {
    $categoryResult = mysqli_query($conn, 'SELECT name FROM categories ORDER BY name');
    while ($categoryResult && $row = mysqli_fetch_assoc($categoryResult)) {
        $categoryRows[] = $row['name'];
    }
}

$sql = "
    SELECT b.*, " . ($hasCategoriesTable ? "c.name AS category_name" : "NULL AS category_name") . ", " . ($hasFavoritesTable ? "(f.id IS NOT NULL)" : "0") . " AS is_favorited
    FROM books b
";

if ($hasCategoriesTable) {
    $sql .= " LEFT JOIN categories c ON c.id = b.category_id\n";
}
if ($hasFavoritesTable) {
    $sql .= " LEFT JOIN favorites f ON f.book_id = b.id AND f.user_id = ?\n";
}

$sql .= " WHERE 1 = 1";
$params = [];
$types = '';

if ($hasFavoritesTable) {
    $params[] = $userId;
    $types .= 'i';
}

if ($searchQuery !== '') {
    $sql .= ' AND (b.title LIKE ? OR b.author LIKE ? OR b.description LIKE ?)';
    $like = '%' . $searchQuery . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= 'sss';
}

if (!empty($selectedCategories)) {
    $selectedCategories = array_values(array_filter($selectedCategories, 'strlen'));
    if (!empty($selectedCategories)) {
        $catPlaceholders = implode(',', array_fill(0, count($selectedCategories), '?'));
        $sql .= " AND c.name IN ($catPlaceholders)";
        foreach ($selectedCategories as $category) {
            $params[] = $category;
            $types .= 's';
        }
    }
}

if (!empty($selectedAvailability)) {
    $selectedAvailability = array_values(array_filter($selectedAvailability, 'strlen'));
    if (!empty($selectedAvailability)) {
        $typePlaceholders = implode(',', array_fill(0, count($selectedAvailability), '?'));
        $sql .= " AND b.type IN ($typePlaceholders)";
        foreach ($selectedAvailability as $type) {
            $params[] = $type;
            $types .= 's';
        }
    }
}

$sql .= ' ORDER BY b.created_at DESC';

$books = [];
if ($hasBooksTable) {
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        if ($types !== '') {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($result && $row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
    }
}

user_render_layout_start('Browse Books', 'Search, filter, and discover books with a modern reading experience.', 'browse', $user);
?>

<section class="user-search-wrap card-surface">
    <form method="GET">
        <div class="user-search-bar">
            <input type="text" name="q" value="<?php echo user_h($searchQuery); ?>" placeholder="Search by title, author, or keyword">
            <button class="btn-user btn-user-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </div>

        <div class="user-filter-row">
            <?php foreach ($categoryRows as $category): ?>
                <label class="user-filter-chip">
                    <input type="checkbox" name="categories[]" value="<?php echo user_h($category); ?>" <?php echo in_array($category, $selectedCategories, true) ? 'checked' : ''; ?>>
                    <?php echo user_h($category); ?>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="user-filter-row" style="margin-top:8px;">
            <?php foreach (['borrow' => 'For Borrow', 'sale' => 'For Sale', 'both' => 'Borrow & Sale'] as $type => $label): ?>
                <label class="user-filter-chip">
                    <input type="checkbox" name="availability[]" value="<?php echo user_h($type); ?>" <?php echo in_array($type, $selectedAvailability, true) ? 'checked' : ''; ?>>
                    <?php echo user_h($label); ?>
                </label>
            <?php endforeach; ?>
            <a class="btn-user btn-user-secondary" href="/library_project/dashboard/user/browse.php">Reset</a>
        </div>
    </form>
</section>

<section class="user-book-grid">
    <?php if (empty($books)): ?>
        <article class="user-empty card-surface" style="grid-column: 1 / -1;">
            <h3>No books found</h3>
            <p>Try different keywords or fewer filters.</p>
        </article>
    <?php else: ?>
        <?php foreach ($books as $book): ?>
            <?php
            $img = !empty($book['image']) ? $book['image'] : 'https://via.placeholder.com/300x400/7c3aed/ffffff?text=' . urlencode(substr((string) $book['title'], 0, 1));
            $isFavorited = (int) ($book['is_favorited'] ?? 0) === 1;
            ?>
            <article class="user-book-card card-surface">
                <div class="user-book-cover">
                    <img src="<?php echo user_h($img); ?>" alt="<?php echo user_h($book['title']); ?>">
                </div>
                <div class="user-book-body">
                    <span class="user-chip"><?php echo user_h($book['category_name'] ?? 'Uncategorized'); ?></span>
                    <h3 class="user-book-title"><?php echo user_h($book['title']); ?></h3>
                    <p class="user-book-meta">by <?php echo user_h($book['author']); ?></p>
                    <div class="user-actions">
                        <a class="btn-user btn-user-secondary" href="/library_project/dashboard/user/book-details.php?id=<?php echo (int) $book['id']; ?>">Details</a>
                        <button class="btn-user btn-user-secondary book-favorite-btn <?php echo $isFavorited ? 'favorited' : ''; ?>" data-book-id="<?php echo (int) $book['id']; ?>"><?php echo $isFavorited ? '❤️' : '🤍'; ?></button>
                        <?php if (($book['type'] ?? '') === 'borrow' || ($book['type'] ?? '') === 'both'): ?>
                            <button class="btn-user btn-user-primary" onclick="borrowBook(<?php echo (int) $book['id']; ?>, this)"><i class="fa-solid fa-book"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php user_render_layout_end(['/library_project/assets/js/books.js']);

