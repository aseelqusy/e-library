<?php
require_once __DIR__ . '/../../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId > 0) {
        $stmt = mysqli_prepare($conn, 'DELETE FROM books WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $deleteId);
        mysqli_stmt_execute($stmt);
        admin_set_toast('Book deleted successfully.', 'success');
    }
    header('Location: /library_project/dashboard/admin/books.php');
    exit();
}

$search = trim($_GET['search'] ?? '');
$category = (int) ($_GET['category'] ?? 0);
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 8;
$offset = ($page - 1) * $perPage;

$categories = [];
$categoryResult = mysqli_query($conn, 'SELECT id, name FROM categories ORDER BY name ASC');
if ($categoryResult) {
    while ($row = mysqli_fetch_assoc($categoryResult)) {
        $categories[] = $row;
    }
}

$where = [];
$params = [];
$types = '';

if ($search !== '') {
    $where[] = '(b.title LIKE ? OR b.author LIKE ?)';
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $types .= 'ss';
}

if ($category > 0) {
    $where[] = 'b.category_id = ?';
    $params[] = $category;
    $types .= 'i';
}

$whereSql = $where ? (' WHERE ' . implode(' AND ', $where)) : '';

$countSql = 'SELECT COUNT(*) AS total FROM books b' . $whereSql;
$countStmt = mysqli_prepare($conn, $countSql);
if ($types !== '') {
    mysqli_stmt_bind_param($countStmt, $types, ...$params);
}
mysqli_stmt_execute($countStmt);
$countResult = mysqli_stmt_get_result($countStmt);
$totalRows = (int) (mysqli_fetch_assoc($countResult)['total'] ?? 0);
$totalPages = max(1, (int) ceil($totalRows / $perPage));

$listSql = '
    SELECT b.id, b.title, b.author, b.image, b.type, b.price, c.name AS category_name,
           COALESCE(s.quantity, 0) AS quantity
    FROM books b
    LEFT JOIN categories c ON c.id = b.category_id
    LEFT JOIN stock s ON s.book_id = b.id
' . $whereSql . '
    ORDER BY b.created_at DESC, b.id DESC
    LIMIT ? OFFSET ?
';

$listTypes = $types . 'ii';
$listParams = $params;
$listParams[] = $perPage;
$listParams[] = $offset;

$listStmt = mysqli_prepare($conn, $listSql);
mysqli_stmt_bind_param($listStmt, $listTypes, ...$listParams);
mysqli_stmt_execute($listStmt);
$booksResult = mysqli_stmt_get_result($listStmt);

admin_render_start('Manage Books', 'books', 'Search, filter, and maintain your entire catalog.');
?>

<section class="admin-card">
    <div class="toolbar">
        <form class="toolbar" method="get" action="">
            <input type="search" class="admin-input" style="min-width:220px;" name="search" value="<?php echo admin_h($search); ?>" placeholder="Search by title or author">
            <select class="admin-select" name="category" style="min-width:180px;">
                <option value="0">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo (int) $cat['id']; ?>" <?php echo $category === (int) $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo admin_h($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-secondary-soft" type="submit"><i class="fa-solid fa-filter"></i> Apply</button>
        </form>
        <a class="btn btn-primary-soft ms-auto" href="/library_project/dashboard/admin/add-book.php"><i class="fa-solid fa-plus"></i> Add Book</a>
    </div>

    <div class="table-wrap mt-3">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($booksResult && mysqli_num_rows($booksResult) > 0): ?>
                <?php while ($book = mysqli_fetch_assoc($booksResult)): ?>
                    <?php
                    $image = trim((string) ($book['image'] ?? ''));
                    $quantity = (int) $book['quantity'];
                    $statusClass = $quantity > 5 ? 'status-success' : ($quantity > 0 ? 'status-warning' : 'status-danger');
                    $statusText = $quantity > 5 ? 'In Stock' : ($quantity > 0 ? 'Low Stock' : 'Out of Stock');
                    ?>
                    <tr>
                        <td>
                            <?php if ($image !== ''): ?>
                                <img class="table-cover" src="<?php echo admin_h($image); ?>" alt="cover">
                            <?php else: ?>
                                <div class="table-cover d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-book text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo admin_h($book['title']); ?></td>
                        <td><?php echo admin_h($book['author']); ?></td>
                        <td><?php echo admin_h($book['category_name'] ?? 'Uncategorized'); ?></td>
                        <td><?php echo admin_h(ucfirst($book['type'])); ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td>$<?php echo number_format((float) $book['price'], 2); ?></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a class="btn btn-secondary-soft" href="/library_project/dashboard/admin/edit-book.php?id=<?php echo (int) $book['id']; ?>">Edit</a>
                                <a class="btn btn-danger-soft" href="/library_project/dashboard/admin/books.php?delete=<?php echo (int) $book['id']; ?>" data-confirm="Delete this book permanently?">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center py-4">No books found for your current filters.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</section>

<?php admin_render_end(); ?>
