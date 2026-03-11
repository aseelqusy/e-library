# Admin Dashboard - Implementation Guide

## Quick Start Setup

### Step 1: Import CSS Files (Add to admin pages HEAD)
```html
<link rel="stylesheet" href="/assets/css/admin-dashboard.css">
<link rel="stylesheet" href="/assets/css/admin-components.css">
<link rel="stylesheet" href="/assets/css/admin-animations.css">
```

### Step 2: Import JavaScript Files (Add to admin pages BODY end)
```html
<script src="/assets/js/admin-utils.js"></script>
<script src="/assets/js/admin-interactions.js"></script>
```

### Step 3: Use Admin Layout Template
All admin pages should use this structure:

```html
<?php 
    include __DIR__ . '/../../includes/admin-header.php'; 
    include __DIR__ . '/../../includes/admin-sidebar.php'; 
?>

<div class="admin-main">
    <?php include __DIR__ . '/../../includes/admin-header-top.php'; ?>
    
    <div class="admin-content">
        <!-- Page content goes here -->
    </div>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
```

---

## Building Admin Pages

### Example 1: Dashboard Home Page
**File:** `dashboard/admin/index.php`

```html
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back, Admin! Here's your library overview.</p>
</div>

<!-- Statistics Section -->
<div class="grid grid-cols-4 gap-2 mb-3">
    <div class="stat-card slide-up stagger-1">
        <div class="stat-card-icon">📚</div>
        <div class="stat-card-content">
            <div class="stat-card-label">Total Books</div>
            <div class="stat-card-value">1,250</div>
            <div class="stat-card-trend positive">12% from last week</div>
        </div>
    </div>
    
    <div class="stat-card success slide-up stagger-2">
        <div class="stat-card-icon">👥</div>
        <div class="stat-card-content">
            <div class="stat-card-label">Total Users</div>
            <div class="stat-card-value">485</div>
            <div class="stat-card-trend positive">8% new this month</div>
        </div>
    </div>
    
    <div class="stat-card warning slide-up stagger-3">
        <div class="stat-card-icon">📋</div>
        <div class="stat-card-content">
            <div class="stat-card-label">Active Borrows</div>
            <div class="stat-card-value">125</div>
            <div class="stat-card-trend negative">5% overdue</div>
        </div>
    </div>
    
    <div class="stat-card info slide-up stagger-4">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-content">
            <div class="stat-card-label">Revenue</div>
            <div class="stat-card-value">$12,500</div>
            <div class="stat-card-trend positive">23% increase</div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-2 gap-2 mb-3">
    <div class="card card-hover slide-up stagger-2">
        <div class="card-header">
            <h3>Books Added This Year</h3>
        </div>
        <div class="card-body">
            <canvas id="booksChart"></canvas>
        </div>
    </div>
    
    <div class="card card-hover slide-up stagger-3">
        <div class="card-header">
            <h3>Category Distribution</h3>
        </div>
        <div class="card-body">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card slide-up stagger-4">
    <div class="card-header">
        <h3>Recent Borrowing Activity</h3>
        <a href="/dashboard/admin/borrowings.php" class="text-primary">View All →</a>
    </div>
    <div class="card-body">
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Book</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>The Great Gatsby</td>
                        <td>Feb 15, 2026</td>
                        <td><span class="badge badge-primary">Approved</span></td>
                        <td>
                            <button class="btn btn-sm btn-secondary">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
```

---

### Example 2: Manage Books Page
**File:** `dashboard/admin/books.php`

```html
<div class="page-header">
    <h1 class="page-title">Books Library</h1>
    <p class="page-subtitle">Manage all books in your library system</p>
</div>

<!-- Search & Filter Bar -->
<div class="search-filter-bar mb-3">
    <div class="search-input-group flex-1">
        <input type="text" class="form-input" placeholder="Search books...">
    </div>
    <select class="form-select" id="categoryFilter">
        <option>All Categories</option>
        <option>Fiction</option>
        <option>Science</option>
    </select>
    <select class="form-select" id="statusFilter">
        <option>All Status</option>
        <option>Available</option>
        <option>Borrowed</option>
    </select>
    <a href="/dashboard/admin/addBook.php" class="btn btn-primary">+ Add New Book</a>
</div>

<!-- Stats Bar -->
<div class="card mb-3 p-2">
    <div class="flex gap-2">
        <div><strong>Total:</strong> 1,250</div>
        <div><strong>Available:</strong> 980</div>
        <div><strong>Borrowed:</strong> 200</div>
        <div><strong>Damaged:</strong> 70</div>
    </div>
</div>

<!-- Books Table -->
<div class="card">
    <div class="data-table-wrapper">
        <table class="data-table" id="booksTable">
            <thead>
                <tr>
                    <th><input type="checkbox" class="select-all"></th>
                    <th>Title</th>
                    <th class="sortable">Author</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><strong>The Great Gatsby</strong></td>
                    <td>F. Scott Fitzgerald</td>
                    <td><span class="badge badge-primary">Fiction</span></td>
                    <td>5</td>
                    <td><span class="badge badge-success">Available</span></td>
                    <td>
                        <button class="btn btn-sm btn-secondary" data-action="edit">✎</button>
                        <button class="btn btn-sm btn-danger" data-action="delete">🗑</button>
                        <button class="btn btn-sm btn-secondary" data-action="view">👁</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="data-table-footer">
            <span>Showing 1-20 of 1,250 books</span>
            <div class="data-table-pagination">
                <button class="data-table-pagination-btn">&laquo;</button>
                <button class="data-table-pagination-btn active">1</button>
                <button class="data-table-pagination-btn">2</button>
                <button class="data-table-pagination-btn">3</button>
                <button class="data-table-pagination-btn">&raquo;</button>
            </div>
        </div>
    </div>
</div>

<script>
    const booksTable = new DataTable('#booksTable');
    
    // Delete button handler
    document.querySelectorAll('[data-action="delete"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const bookName = row.querySelector('td:nth-child(2)').textContent;
            
            ConfirmDialog.show(
                `Delete "${bookName}"? This action cannot be undone.`,
                () => {
                    // Call API to delete
                    toast.success('Book deleted successfully');
                },
                () => console.log('Cancelled')
            );
        });
    });
</script>
```

---

### Example 3: Add/Edit Book Form
**File:** `dashboard/admin/addBook.php` or `editBook.php`

```html
<div class="page-header">
    <h1 class="page-title">Add New Book</h1>
    <p class="page-subtitle">Add a new book to your library</p>
</div>

<div class="grid grid-cols-3 gap-2">
    <!-- Form -->
    <div class="grid grid-cols-2 col-span-2 gap-2">
        <form id="bookForm" class="card col-span-2">
            <!-- Basic Information -->
            <div class="card-header">
                <h3>Basic Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label required">Title</label>
                    <input type="text" class="form-input" placeholder="Book title" required>
                    <span class="form-help">Enter the complete book title</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Author</label>
                    <input type="text" class="form-input" placeholder="Author name" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">ISBN</label>
                    <input type="text" class="form-input" placeholder="ISBN-13" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Category</label>
                    <select class="form-select" required>
                        <option>Select Category</option>
                        <option>Fiction</option>
                        <option>Science</option>
                        <option>History</option>
                    </select>
                </div>
            </div>
            
            <!-- Details -->
            <div class="card-header">
                <h3>Details</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label required">Description</label>
                    <textarea class="form-textarea" placeholder="Book description" required></textarea>
                    <span class="form-help">500 characters max</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Publication Year</label>
                    <input type="number" class="form-input" placeholder="2026">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Number of Pages</label>
                    <input type="number" class="form-input" placeholder="350">
                </div>
            </div>
            
            <!-- Availability -->
            <div class="card-header">
                <h3>Availability</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label required">Total Copies</label>
                    <input type="number" class="form-input" placeholder="5" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Status</label>
                    <div class="flex gap-2">
                        <label class="form-checkbox">
                            <input type="radio" name="status" value="available" checked>
                            Available
                        </label>
                        <label class="form-checkbox">
                            <input type="radio" name="status" value="comingsoon">
                            Coming Soon
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Media -->
            <div class="card-header">
                <h3>Media & Attachments</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label required">Cover Image</label>
                    <div class="form-input" style="border: 2px dashed var(--color-dark-border); padding: 2rem; text-align: center; cursor: pointer;">
                        <input type="file" class="hidden" accept="image/*">
                        <div>📤 Click to upload or drag & drop</div>
                        <small>Max 2MB. PNG, JPG, JPEG</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">PDF File (Optional)</label>
                    <div class="form-input" style="border: 2px dashed var(--color-dark-border); padding: 2rem; text-align: center; cursor: pointer;">
                        <input type="file" class="hidden" accept=".pdf">
                        <div>📤 Upload PDF</div>
                        <small>Max 10MB</small>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card-footer flex justify-end gap-2">
                <button type="reset" class="btn btn-secondary">Cancel</button>
                <button type="button" class="btn btn-secondary">Save as Draft</button>
                <button type="submit" class="btn btn-primary">Publish</button>
            </div>
        </form>
        
        <!-- Preview -->
        <div class="card">
            <div class="card-header">
                <h3>Preview</h3>
            </div>
            <div class="card-body text-center">
                <div style="width: 150px; height: 200px; background: var(--color-dark-border); border-radius: var(--radius-sm); margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                    📖
                </div>
                <div class="mt-2">
                    <h4>Book Title</h4>
                    <p class="text-secondary">Author Name</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const form = new FormValidator('#bookForm');
    
    document.getElementById('bookForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (form.validateAll()) {
            toast.success('Book published successfully!');
            // Submit form...
        }
    });
</script>
```

---

## Key CSS Classes Reference

### Cards
```html
<div class="card">Basic card</div>
<div class="card card-hover">Card with hover lift</div>
<div class="stat-card">Stat card</div>
<div class="stat-card success">Success variant</div>
```

### Buttons
```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-lg">Large</button>
<button class="btn btn-sm">Small</button>
```

### Badges
```html
<span class="badge badge-success">✓ Active</span>
<span class="badge badge-warning">⏳ Pending</span>
<span class="badge badge-danger">✗ Inactive</span>
<span class="badge badge-info">ⓘ Info</span>
```

### Forms
```html
<div class="form-group">
    <label class="form-label required">Field Label</label>
    <input type="text" class="form-input">
    <span class="form-help">Help text</span>
</div>
```

### Tables
```html
<table class="data-table">
    <thead>
        <tr>
            <th class="sortable">Sortable Header</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data</td>
        </tr>
    </tbody>
</table>
```

### Grid
```html
<div class="grid grid-cols-4 gap-2">
    <div>Column 1</div>
    <div>Column 2</div>
    <div>Column 3</div>
    <div>Column 4</div>
</div>
```

### Spacing
```html
<div class="mt-1 mb-2 p-3">Spacing utilities</div>
<div class="px-2 py-1">Padding utilities</div>
```

---

## JavaScript Usage

### Toast Notifications
```javascript
toast.success('Action successful!');
toast.error('Something went wrong');
toast.warning('Be careful!');
toast.info('Information message');
```

### Modals
```javascript
const modal = new Modal('.modal-selector');
modal.open();
modal.close();
modal.setContent('<p>New content</p>');
```

### Confirmation Dialogs
```javascript
ConfirmDialog.show(
    'Are you sure?',
    () => {
        // On confirm
        console.log('Confirmed');
    },
    () => {
        // On cancel
        console.log('Cancelled');
    }
);
```

### Counter Animation
```javascript
CounterAnimation.animateCounter(element, 1250, 2000);
CounterAnimation.animateAllCounters(); // Animate all stat cards
```

### Form Validation
```javascript
const form = new FormValidator('#myForm');
if (form.validateAll()) {
    // Form is valid
}
```

### Data Table
```javascript
const table = new DataTable('#myTable');
table.sort(headerElement);
table.filterRows(row => row.visible);
```

---

## Animation Classes

```html
<!-- Fade in -->
<div class="fade-in">Content fades in</div>

<!-- Slide up -->
<div class="slide-up">Content slides up</div>

<!-- Scale in -->
<div class="scale-in">Content scales in</div>

<!-- Staggered animations -->
<div class="slide-up stagger-1">First</div>
<div class="slide-up stagger-2">Second</div>
<div class="slide-up stagger-3">Third</div>
```

---

## Responsive Breakpoints

```css
xs: 0px       /* Mobile phones */
sm: 640px     /* Small tablets */
md: 768px     /* Tablets */
lg: 1024px    /* Small desktops */
xl: 1280px    /* Desktops */
2xl: 1536px   /* Large desktops */
```

Use classes: `grid-cols-1 md:grid-cols-2 lg:grid-cols-4`

---

## Dark Mode Support

```html
<!-- Add to body class to enable light mode -->
<body class="light-mode">
```

Toggle with button:
```html
<button class="dark-mode-toggle">🌙</button>
```

---

## File Structure for Implementation

```
admin-files-to-create/
├── includes/
│   ├── admin-header.php
│   ├── admin-sidebar.php
│   ├── admin-header-top.php
│   └── admin-footer.php
├── assets/
│   ├── css/
│   │   ├── admin-dashboard.css
│   │   ├── admin-components.css
│   │   └── admin-animations.css
│   └── js/
│       ├── admin-utils.js
│       ├── admin-interactions.js
│       └── admin-charts.js
└── dashboard/admin/
    ├── index.php
    ├── books.php
    ├── addBook.php
    ├── editBook.php
    ├── categories.php
    ├── users.php
    ├── borrowings.php
    ├── reviews.php
    ├── reports.php
    ├── settings.php
    └── profile.php
```

---

## Next Steps

1. Create CSS files from the comprehensive specification
2. Create PHP layout templates (header, sidebar, footer)
3. Start building pages in priority order
4. Add JavaScript functionality
5. Test responsive design
6. Optimize performance
7. Add additional polish and animations

For full specification details, see `ADMIN-DASHBOARD-SPEC.md`

