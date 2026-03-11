<?php
require_once __DIR__ . '/../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

$errors = [];
$form = [
    'title' => '',
    'author' => '',
    'category_id' => 0,
    'description' => '',
    'isbn' => '',
    'publication_year' => '',
    'quantity' => 1,
    'price' => 0,
    'type' => 'borrow',
    'availability_status' => 'available',
];

$categories = [];
$categoryResult = mysqli_query($conn, 'SELECT id, name FROM categories ORDER BY name ASC');
if ($categoryResult) {
    while ($row = mysqli_fetch_assoc($categoryResult)) {
        $categories[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form as $key => $value) {
        if (isset($_POST[$key])) {
            $form[$key] = is_string($_POST[$key]) ? trim($_POST[$key]) : $_POST[$key];
        }
    }

    $form['category_id'] = (int) $form['category_id'];
    $form['quantity'] = max(0, (int) $form['quantity']);
    $form['price'] = max(0, (float) $form['price']);

    if ($form['title'] === '') {
        $errors[] = 'Title is required.';
    }
    if ($form['author'] === '') {
        $errors[] = 'Author is required.';
    }

    $uploadDir = __DIR__ . '/../assets/uploads/books';
    $pdfDir = __DIR__ . '/../assets/uploads/pdfs';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if (!is_dir($pdfDir)) {
        mkdir($pdfDir, 0777, true);
    }

    $coverPath = null;
    if (!empty($_FILES['cover_image']['name']) && ($_FILES['cover_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $coverExt = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($coverExt, $allowed, true)) {
            $errors[] = 'Cover image must be jpg, png, or webp.';
        } else {
            $coverName = 'book_' . time() . '_' . mt_rand(1000, 9999) . '.' . $coverExt;
            $target = $uploadDir . '/' . $coverName;
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
                $coverPath = '/library_project/assets/uploads/books/' . $coverName;
            }
        }
    }

    $pdfPath = null;
    if (!empty($_FILES['pdf_file']['name']) && ($_FILES['pdf_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $pdfExt = strtolower(pathinfo($_FILES['pdf_file']['name'], PATHINFO_EXTENSION));
        if ($pdfExt !== 'pdf') {
            $errors[] = 'Uploaded document must be a PDF.';
        } else {
            $pdfName = 'book_' . time() . '_' . mt_rand(1000, 9999) . '.pdf';
            $target = $pdfDir . '/' . $pdfName;
            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target)) {
                $pdfPath = '/library_project/assets/uploads/pdfs/' . $pdfName;
            }
        }
    }

    if (!$errors) {
        $columns = ['title', 'author', 'description', 'price', 'image', 'category_id', 'type'];
        $values = [$form['title'], $form['author'], $form['description'], $form['price'], $coverPath, $form['category_id'] ?: null, $form['type']];
        $types = 'sssdsis';

        if (admin_column_exists($conn, 'books', 'isbn')) {
            $columns[] = 'isbn';
            $values[] = $form['isbn'];
            $types .= 's';
        }

        if (admin_column_exists($conn, 'books', 'publication_year')) {
            $columns[] = 'publication_year';
            $values[] = $form['publication_year'] !== '' ? (int) $form['publication_year'] : null;
            $types .= 'i';
        }

        if (admin_column_exists($conn, 'books', 'pdf_path')) {
            $columns[] = 'pdf_path';
            $values[] = $pdfPath;
            $types .= 's';
        }

        if (admin_column_exists($conn, 'books', 'availability_status')) {
            $columns[] = 'availability_status';
            $values[] = $form['availability_status'];
            $types .= 's';
        }

        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = 'INSERT INTO books (' . implode(', ', $columns) . ') VALUES (' . $placeholders . ')';
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$values);

        if (mysqli_stmt_execute($stmt)) {
            $bookId = mysqli_insert_id($conn);

            $stockStmt = mysqli_prepare($conn, 'INSERT INTO stock (book_id, quantity) VALUES (?, ?) ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)');
            mysqli_stmt_bind_param($stockStmt, 'ii', $bookId, $form['quantity']);
            mysqli_stmt_execute($stockStmt);

            admin_set_toast('Book added successfully.', 'success');
            header('Location: /library_project/dashboard/books.php');
            exit();
        }

        $errors[] = 'Failed to save the new book.';
    }
}

admin_render_start('Add Book', 'books', 'Create a new catalog entry with files and availability.');
?>

<section class="admin-card">
    <?php if ($errors): ?>
        <div class="status-badge status-danger mb-3"><?php echo admin_h(implode(' ', $errors)); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="admin-form-grid">
        <div class="admin-form-group">
            <label class="form-label">Title</label>
            <input class="admin-input" type="text" name="title" required value="<?php echo admin_h($form['title']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Author</label>
            <input class="admin-input" type="text" name="author" required value="<?php echo admin_h($form['author']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Category</label>
            <select class="admin-select" name="category_id">
                <option value="0">Select category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo (int) $cat['id']; ?>" <?php echo (int) $form['category_id'] === (int) $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo admin_h($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="admin-form-group">
            <label class="form-label">ISBN</label>
            <input class="admin-input" type="text" name="isbn" value="<?php echo admin_h($form['isbn']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Publication Year</label>
            <input class="admin-input" type="number" name="publication_year" min="1000" max="2099" value="<?php echo admin_h($form['publication_year']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Quantity</label>
            <input class="admin-input" type="number" name="quantity" min="0" value="<?php echo (int) $form['quantity']; ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Price</label>
            <input class="admin-input" type="number" name="price" min="0" step="0.01" value="<?php echo admin_h((string) $form['price']); ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Book Type</label>
            <select class="admin-select" name="type">
                <option value="borrow" <?php echo $form['type'] === 'borrow' ? 'selected' : ''; ?>>Borrow</option>
                <option value="sale" <?php echo $form['type'] === 'sale' ? 'selected' : ''; ?>>Sale</option>
                <option value="both" <?php echo $form['type'] === 'both' ? 'selected' : ''; ?>>Both</option>
            </select>
        </div>

        <div class="admin-form-group">
            <label class="form-label">Availability Status</label>
            <select class="admin-select" name="availability_status">
                <option value="available" <?php echo $form['availability_status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                <option value="limited" <?php echo $form['availability_status'] === 'limited' ? 'selected' : ''; ?>>Limited</option>
                <option value="unavailable" <?php echo $form['availability_status'] === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
            </select>
        </div>

        <div class="admin-form-group full">
            <label class="form-label">Description</label>
            <textarea class="admin-textarea" name="description"><?php echo admin_h($form['description']); ?></textarea>
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
            <a href="/library_project/dashboard/books.php" class="btn btn-secondary-soft">Cancel</a>
            <button type="submit" class="btn btn-primary-soft"><i class="fa-solid fa-floppy-disk"></i> Save Book</button>
        </div>
    </form>
</section>

<?php admin_render_end(); ?>
