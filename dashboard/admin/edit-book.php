<?php
require_once __DIR__ . '/../../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    admin_set_toast('Invalid book selected.', 'error');
    header('Location: /library_project/dashboard/admin/books.php');
    exit();
}

$stmt = mysqli_prepare($conn, '
    SELECT b.*, COALESCE(s.quantity, 0) AS quantity
    FROM books b
    LEFT JOIN stock s ON s.book_id = b.id
    WHERE b.id = ?
    LIMIT 1
');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    admin_set_toast('Book not found.', 'error');
    header('Location: /library_project/dashboard/admin/books.php');
    exit();
}

$categories = [];
$categoryResult = mysqli_query($conn, 'SELECT id, name FROM categories ORDER BY name ASC');
if ($categoryResult) {
    while ($row = mysqli_fetch_assoc($categoryResult)) {
        $categories[] = $row;
    }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $publicationYear = trim($_POST['publication_year'] ?? '');
    $quantity = max(0, (int) ($_POST['quantity'] ?? 0));
    $price = max(0, (float) ($_POST['price'] ?? 0));
    $type = $_POST['type'] ?? 'borrow';
    $availabilityStatus = $_POST['availability_status'] ?? 'available';

    if ($title === '' || $author === '') {
        $errors[] = 'Title and author are required.';
    }

    $coverPath = $book['image'];
    if (!empty($_FILES['cover_image']['name']) && ($_FILES['cover_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../assets/uploads/books';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $name = 'book_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $target = $uploadDir . '/' . $name;
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                $coverPath = '/library_project/assets/uploads/books/' . $name;
            }
        }
    }

    $pdfPath = $book['pdf_path'] ?? null;
    if (!empty($_FILES['pdf_file']['name']) && ($_FILES['pdf_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $pdfDir = __DIR__ . '/../../assets/uploads/pdfs';
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }

        $pdfExt = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
        if ($pdfExt === 'pdf') {
            $pdfName = 'book_' . time() . '_' . mt_rand(1000, 9999) . '.pdf';
            $targetPdf = $pdfDir . '/' . $pdfName;
            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetPdf)) {
                $pdfPath = '/library_project/assets/uploads/pdfs/' . $pdfName;
            }
        }
    }

    if (!$errors) {
        $set = ['title = ?', 'author = ?', 'description = ?', 'price = ?', 'image = ?', 'category_id = ?', 'type = ?'];
        $types = 'sssdsis';
        $values = [$title, $author, $description, $price, $coverPath, $categoryId ?: null, $type];

        if (admin_column_exists($conn, 'books', 'isbn')) {
            $set[] = 'isbn = ?';
            $values[] = $isbn;
            $types .= 's';
        }

        if (admin_column_exists($conn, 'books', 'publication_year')) {
            $set[] = 'publication_year = ?';
            $values[] = $publicationYear !== '' ? (int) $publicationYear : null;
            $types .= 'i';
        }

        if (admin_column_exists($conn, 'books', 'pdf_path')) {
            $set[] = 'pdf_path = ?';
            $values[] = $pdfPath;
            $types .= 's';
        }

        if (admin_column_exists($conn, 'books', 'availability_status')) {
            $set[] = 'availability_status = ?';
            $values[] = $availabilityStatus;
            $types .= 's';
        }

        $types .= 'i';
        $values[] = $id;

        $updateSql = 'UPDATE books SET ' . implode(', ', $set) . ' WHERE id = ?';
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, $types, ...$values);

        if (mysqli_stmt_execute($updateStmt)) {
            $stockStmt = mysqli_prepare($conn, 'INSERT INTO stock (book_id, quantity) VALUES (?, ?) ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)');
            mysqli_stmt_bind_param($stockStmt, 'ii', $id, $quantity);
            mysqli_stmt_execute($stockStmt);

            admin_set_toast('Book updated successfully.', 'success');
            header('Location: /library_project/dashboard/admin/books.php');
            exit();
        }

        $errors[] = 'Failed to update this book.';
    }

    $book['title'] = $title;
    $book['author'] = $author;
    $book['description'] = $description;
    $book['price'] = $price;
    $book['image'] = $coverPath;
    $book['category_id'] = $categoryId;
    $book['type'] = $type;
    $book['quantity'] = $quantity;
    $book['isbn'] = $isbn;
    $book['publication_year'] = $publicationYear;
    $book['availability_status'] = $availabilityStatus;
}

admin_render_start('Edit Book', 'books', 'Update metadata, assets, and availability.');
?>

<section class="admin-card">
    <?php if ($errors): ?>
        <div class="status-badge status-danger mb-3"><?php echo admin_h(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="admin-form-grid">
        <div class="admin-form-group">
            <label class="form-label">Title</label>
            <input class="admin-input" type="text" name="title" required value="<?php echo admin_h($book['title']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Author</label>
            <input class="admin-input" type="text" name="author" required value="<?php echo admin_h($book['author']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Category</label>
            <select class="admin-select" name="category_id">
                <option value="0">Select category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo (int) $cat['id']; ?>" <?php echo (int) $book['category_id'] === (int) $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo admin_h($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="admin-form-group">
            <label class="form-label">ISBN</label>
            <input class="admin-input" type="text" name="isbn" value="<?php echo admin_h($book['isbn'] ?? ''); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Publication Year</label>
            <input class="admin-input" type="number" name="publication_year" min="1000" max="2099" value="<?php echo admin_h((string) ($book['publication_year'] ?? '')); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Quantity</label>
            <input class="admin-input" type="number" name="quantity" min="0" value="<?php echo (int) ($book['quantity'] ?? 0); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Price</label>
            <input class="admin-input" type="number" name="price" min="0" step="0.01" value="<?php echo admin_h((string) $book['price']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Book Type</label>
            <select class="admin-select" name="type">
                <option value="borrow" <?php echo ($book['type'] ?? 'borrow') === 'borrow' ? 'selected' : ''; ?>>Borrow</option>
                <option value="sale" <?php echo ($book['type'] ?? '') === 'sale' ? 'selected' : ''; ?>>Sale</option>
                <option value="both" <?php echo ($book['type'] ?? '') === 'both' ? 'selected' : ''; ?>>Both</option>
            </select>
        </div>

        <div class="admin-form-group">
            <label class="form-label">Availability Status</label>
            <select class="admin-select" name="availability_status">
                <option value="available" <?php echo ($book['availability_status'] ?? 'available') === 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="limited" <?php echo ($book['availability_status'] ?? '') === 'limited' ? 'selected' : ''; ?>>Limited</option>
                <option value="unavailable" <?php echo ($book['availability_status'] ?? '') === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
            </select>
        </div>

        <div class="admin-form-group full">
            <label class="form-label">Description</label>
            <textarea class="admin-textarea" name="description"><?php echo admin_h($book['description'] ?? ''); ?></textarea>
        </div>

        <div class="admin-form-group">
            <label class="form-label">Cover Image Upload</label>
            <input class="admin-file" type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <div class="admin-form-group">
            <label class="form-label">PDF Upload</label>
            <input class="admin-file" type="file" name="pdf_file" accept="application/pdf,.pdf">
        </div>

        <div class="admin-form-group full d-flex justify-content-end gap-2">
            <a href="/library_project/dashboard/admin/books.php" class="btn btn-secondary-soft">Back</a>
            <button type="submit" class="btn btn-primary-soft"><i class="fa-solid fa-pen-to-square"></i> Update Book</button>
        </div>
    </form>
</section>

<?php admin_render_end(); ?>
