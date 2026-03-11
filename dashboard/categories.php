<?php
require_once __DIR__ . '/../includes/admin-layout.php';

admin_require_admin();
$conn = admin_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        if (!empty($_POST['category_id'])) {
            $categoryId = (int) $_POST['category_id'];
            $stmt = mysqli_prepare($conn, 'UPDATE categories SET name = ? WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'si', $name, $categoryId);
            mysqli_stmt_execute($stmt);
            admin_set_toast('Category updated.', 'success');
        } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO categories (name) VALUES (?)');
            mysqli_stmt_bind_param($stmt, 's', $name);
            mysqli_stmt_execute($stmt);
            admin_set_toast('Category created.', 'success');
        }
    }

    header('Location: /library_project/dashboard/categories.php');
    exit();
}

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];
    if ($deleteId > 0) {
        $stmt = mysqli_prepare($conn, 'DELETE FROM categories WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $deleteId);
        mysqli_stmt_execute($stmt);
        admin_set_toast('Category deleted.', 'success');
    }

    header('Location: /library_project/dashboard/categories.php');
    exit();
}

$editCategory = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $stmt = mysqli_prepare($conn, 'SELECT id, name FROM categories WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $editId);
    mysqli_stmt_execute($stmt);
    $editCategory = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

$listSql = '
    SELECT c.id, c.name, COUNT(b.id) AS book_count
    FROM categories c
    LEFT JOIN books b ON b.category_id = c.id
    GROUP BY c.id, c.name
    ORDER BY c.name ASC
';
$listResult = mysqli_query($conn, $listSql);

admin_render_start('Categories', 'categories', 'Organize your books with clean category structures.');
?>

<div class="admin-grid cols-2">
    <section class="admin-card">
        <div class="admin-card-header">
            <h3 class="m-0"><?php echo $editCategory ? 'Edit Category' : 'Add Category'; ?></h3>
        </div>

        <form method="post" class="admin-form-grid">
            <?php if ($editCategory): ?>
                <input type="hidden" name="category_id" value="<?php echo (int) $editCategory['id']; ?>">
            <?php endif; ?>
            <div class="admin-form-group full">
                <label class="form-label">Category Name</label>
                <input type="text" class="admin-input" name="name" required value="<?php echo admin_h($editCategory['name'] ?? ''); ?>">
            </div>
            <div class="admin-form-group full d-flex justify-content-end gap-2">
                <?php if ($editCategory): ?>
                    <a class="btn btn-secondary-soft" href="/library_project/dashboard/categories.php">Cancel</a>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary-soft"><?php echo $editCategory ? 'Update' : 'Create'; ?></button>
            </div>
        </form>
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <h3 class="m-0">Category List</h3>
        </div>

        <div class="table-wrap">
            <table class="admin-table" style="min-width:560px;">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Books</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($listResult && mysqli_num_rows($listResult) > 0): ?>
                    <?php while ($cat = mysqli_fetch_assoc($listResult)): ?>
                        <tr>
                            <td><?php echo admin_h($cat['name']); ?></td>
                            <td><span class="status-badge status-info"><?php echo (int) $cat['book_count']; ?> books</span></td>
                            <td>
                                <a class="btn btn-secondary-soft" href="?edit=<?php echo (int) $cat['id']; ?>">Edit</a>
                                <a class="btn btn-danger-soft" href="?delete=<?php echo (int) $cat['id']; ?>" data-confirm="Delete this category? Books will become uncategorized.">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center py-4">No categories found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php admin_render_end(); ?>
