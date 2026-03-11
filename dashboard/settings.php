<?php
require_once __DIR__ . '/../includes/admin-layout.php';

admin_require_admin();

$settingsPath = __DIR__ . '/../database/admin-settings.json';
$defaults = [
    'library_name' => 'E-Library',
    'contact_email' => 'info@elibrary.com',
    'max_borrow_days' => 14,
    'max_books_per_user' => 3,
    'allow_registration' => true,
    'enable_reviews' => true,
    'maintenance_mode' => false,
    'default_theme' => 'system',
];

$settings = $defaults;
if (is_file($settingsPath)) {
    $decoded = json_decode((string) file_get_contents($settingsPath), true);
    if (is_array($decoded)) {
        $settings = array_merge($settings, $decoded);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings['library_name'] = trim($_POST['library_name'] ?? $settings['library_name']);
    $settings['contact_email'] = trim($_POST['contact_email'] ?? $settings['contact_email']);
    $settings['max_borrow_days'] = max(1, (int) ($_POST['max_borrow_days'] ?? $settings['max_borrow_days']));
    $settings['max_books_per_user'] = max(1, (int) ($_POST['max_books_per_user'] ?? $settings['max_books_per_user']));
    $settings['allow_registration'] = isset($_POST['allow_registration']);
    $settings['enable_reviews'] = isset($_POST['enable_reviews']);
    $settings['maintenance_mode'] = isset($_POST['maintenance_mode']);
    $settings['default_theme'] = in_array($_POST['default_theme'] ?? 'system', ['system', 'light', 'dark'], true)
        ? $_POST['default_theme']
        : 'system';

    file_put_contents($settingsPath, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    admin_set_toast('Settings saved successfully.', 'success');
    header('Location: /library_project/dashboard/settings.php');
    exit();
}

admin_render_start('Settings', 'settings', 'Configure platform behavior and library policies.');
?>
<section class="admin-card">
    <form method="post" class="admin-form-grid">
        <div class="admin-form-group">
            <label class="form-label">Library Name</label>
            <input class="admin-input" type="text" name="library_name" value="<?php echo admin_h($settings['library_name']); ?>" required>
        </div>

        <div class="admin-form-group">
            <label class="form-label">Contact Email</label>
            <input class="admin-input" type="email" name="contact_email" value="<?php echo admin_h($settings['contact_email']); ?>" required>
        </div>

        <div class="admin-form-group">
            <label class="form-label">Max Borrow Days</label>
            <input class="admin-input" type="number" min="1" name="max_borrow_days" value="<?php echo (int) $settings['max_borrow_days']; ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Max Books Per User</label>
            <input class="admin-input" type="number" min="1" name="max_books_per_user" value="<?php echo (int) $settings['max_books_per_user']; ?>">
        </div>

        <div class="admin-form-group">
            <label class="form-label">Default Theme</label>
            <select class="admin-select" name="default_theme">
                <option value="system" <?php echo $settings['default_theme'] === 'system' ? 'selected' : ''; ?>>Follow System</option>
                <option value="light" <?php echo $settings['default_theme'] === 'light' ? 'selected' : ''; ?>>Light</option>
                <option value="dark" <?php echo $settings['default_theme'] === 'dark' ? 'selected' : ''; ?>>Dark</option>
            </select>
        </div>

        <div class="admin-form-group full">
            <label class="form-label">Feature Flags</label>
            <div class="admin-grid cols-3">
                <label class="admin-card" style="margin:0; padding:0.75rem;"><input type="checkbox" name="allow_registration" <?php echo $settings['allow_registration'] ? 'checked' : ''; ?>> Allow user registration</label>
                <label class="admin-card" style="margin:0; padding:0.75rem;"><input type="checkbox" name="enable_reviews" <?php echo $settings['enable_reviews'] ? 'checked' : ''; ?>> Enable book reviews</label>
                <label class="admin-card" style="margin:0; padding:0.75rem;"><input type="checkbox" name="maintenance_mode" <?php echo $settings['maintenance_mode'] ? 'checked' : ''; ?>> Maintenance mode</label>
            </div>
        </div>

        <div class="admin-form-group full d-flex justify-content-end">
            <button type="submit" class="btn btn-primary-soft"><i class="fa-solid fa-floppy-disk"></i> Save Settings</button>
        </div>
    </form>
</section>
<?php admin_render_end(); ?>
