# Admin Dashboard - Complete UI/UX Specification & Implementation Guide

**Project:** Electronic Library Website  
**Status:** Design Phase 1 - Core UI/UX Specification  
**Date:** March 2026

---

## Table of Contents
1. [Design System](#design-system)
2. [Page Structure](#page-structure)
3. [Reusable Components](#reusable-components)
4. [Page-by-Page Layouts](#page-by-page-layouts)
5. [Animation Strategy](#animation-strategy)
6. [Implementation Priority](#implementation-priority)

---

## Design System

### Color Palette

```
PRIMARY COLORS:
  - Primary: #7C3AED (Purple) - Main brand color
  - Primary Dark: #6D28D9 (Deep Purple) - Hover/Active states
  - Primary Light: #A78BFA (Light Purple) - Backgrounds
  
SECONDARY COLORS:
  - Secondary: #EC4899 (Pink) - Accents, CTAs
  - Secondary Dark: #DB2777 (Deep Pink) - Hover states
  - Secondary Light: #F472B6 (Light Pink) - Backgrounds

SEMANTIC COLORS:
  - Success: #10B981 (Emerald) - Approved, Active, Positive
  - Warning: #F59E0B (Amber) - Pending, Caution
  - Danger: #EF4444 (Red) - Rejected, Inactive, Negative
  - Info: #3B82F6 (Blue) - Information, Neutral actions

NEUTRAL COLORS:
  - Dark BG: #0F172A (Slate 900) - Main background
  - Card BG: #1E293B (Slate 800) - Card/Panel background
  - Border: #334155 (Slate 700) - Borders, dividers
  - Text Primary: #F1F5F9 (Slate 100) - Main text
  - Text Secondary: #CBD5E1 (Slate 400) - Secondary text
  - Text Tertiary: #94A3B8 (Slate 500) - Tertiary text
  
LIGHT MODE VARIANTS:
  - BG: #FFFFFF
  - Card BG: #F8FAFC (Slate 50)
  - Border: #E2E8F0 (Slate 200)
  - Text Primary: #0F172A (Slate 900)
  - Text Secondary: #475569 (Slate 600)
```

### Typography

```
FONT STACK:
  - Primary: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif
  - Monospace: 'Courier New', monospace

SIZES & WEIGHTS:
  - H1 (Page Title): 32px, Bold (700)
  - H2 (Section Title): 24px, Semi-Bold (600)
  - H3 (Card Title): 18px, Semi-Bold (600)
  - Subtitle: 14px, Medium (500), Secondary text color
  - Body: 14px, Regular (400)
  - Small: 12px, Regular (400)
  - Code/Badge: 12px, Medium (500), Monospace
  
LINE HEIGHT:
  - Headings: 1.2
  - Body: 1.5
  - Compact: 1.3
```

### Spacing System (8px base unit)

```
xs: 4px     (0.5rem)
sm: 8px     (1rem)
md: 16px    (2rem)
lg: 24px    (3rem)
xl: 32px    (4rem)
2xl: 48px   (6rem)
```

### Border Radius

```
SOFT: 8px        - Cards, inputs, buttons
MEDIUM: 12px     - Modals, larger components
LARGE: 16px      - Containers
FULL: 9999px     - Pills, avatars, badges
```

### Shadows

```
SOFT:     0 1px 2px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.1)
SMALL:    0 4px 6px rgba(0,0,0,0.1), 0 2px 4px rgba(0,0,0,0.06)
MEDIUM:   0 10px 15px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.05)
LARGE:    0 20px 25px rgba(0,0,0,0.1), 0 10px 10px rgba(0,0,0,0.04)
ELEVATION: 0 25px 50px rgba(0,0,0,0.25)
```

---

## Page Structure

### Master Layout Template

```
┌─────────────────────────────────────────────────────────────┐
│              ADMIN DASHBOARD - MASTER LAYOUT                 │
├──────────────┬──────────────────────────────────────────────┤
│              │                                              │
│   SIDEBAR    │            TOP NAVIGATION BAR                │
│   (260px)    │            (Breadcrumb, User, Settings)      │
│              ├──────────────────────────────────────────────┤
│              │                                              │
│   - Dashboard│                                              │
│   - Books    │         MAIN CONTENT AREA                   │
│   - Category │         (Responsive Grid)                   │
│   - Users    │                                              │
│   - Borrows  │                                              │
│   - Reviews  │                                              │
│   - Reports  │                                              │
│   - Settings │                                              │
│              │                                              │
│   Profile   │                                              │
│   Logout    │                                              │
│              │                                              │
└──────────────┴──────────────────────────────────────────────┘
```

---

## Reusable Components

### 1. Stat Card Component
**Purpose:** Display key metrics (books count, users, active borrows, etc.)

```
┌─────────────────────────┐
│  📚 Books in Library    │
│                         │
│      1,250              │
│   ↑ 12% from last week  │
└─────────────────────────┘
```

**Elements:**
- Icon (top-left)
- Title (top-right)
- Large number (center)
- Trend indicator (bottom) - up/down with percentage
- Background gradient optional
- Hover lift effect

**Variants:**
- Normal (Primary purple)
- Success (Green)
- Warning (Amber)
- Danger (Red)

---

### 2. Data Table Component
**Purpose:** Display, search, filter, and manage records

```
┌─────────────────────────────────────────────────────┐
│ ☐  | Title      | Author   | Category | Actions   │
├─────────────────────────────────────────────────────┤
│ ☐  | Book 1     | Author 1 | Fiction  | ✎ 🗑 👁  │
│ ☐  | Book 2     | Author 2 | Science  | ✎ 🗑 👁  │
│ ☐  | Book 3     | Author 3 | History  | ✎ 🗑 👁  │
└─────────────────────────────────────────────────────┘
📄 Showing 1-10 of 250 | << < 1 2 3 > >>
```

**Features:**
- Checkbox selection (header to select all)
- Sortable columns (click header to sort)
- Hover row highlight
- Status badges inline
- Action buttons (Edit, Delete, View, etc.)
- Pagination controls
- Empty state message

---

### 3. Status Badge Component
**Purpose:** Display status/category tags

```
✓ Active        ⏳ Pending       ✗ Inactive      ⓘ Draft
[Green]         [Amber]         [Red]           [Blue]
```

**Variants:**
- Solid background
- Outline with colored border
- Icon + text
- Pill-shaped (rounded)

---

### 4. Form Component
**Purpose:** Clean, organized data entry

```
┌──────────────────────────────┐
│ Book Information             │
├──────────────────────────────┤
│ Title *                      │
│ [________________]           │
│                              │
│ Author *                     │
│ [________________]           │
│                              │
│ Category *                   │
│ [┌─────────────────────────┐]│
│  │ Select Category...      │ │
│  │ - Fiction               │ │
│  │ - Science               │ │
│  └─────────────────────────┘ │
│                              │
│ [   Cancel   ]  [   Save   ] │
└──────────────────────────────┘
```

**Elements:**
- Field group (label, input, helper text)
- Section dividers
- Validation messages (red)
- Required asterisks
- Help text (smaller, secondary color)
- Submit & Cancel buttons

---

### 5. Modal Dialog Component
**Purpose:** Focused actions, confirmations, detailed views

```
┌─────────────────────────────────┐
│  Delete Book?              [✕]  │
├─────────────────────────────────┤
│                                 │
│ Are you sure you want to delete │
│ "The Great Gatsby"?             │
│                                 │
│ This action cannot be undone.   │
│                                 │
│                 [Cancel] [Delete]│
└─────────────────────────────────┘
```

**Features:**
- Backdrop overlay (semi-transparent)
- Close button (X)
- Title & content
- Action buttons
- Keyboard support (ESC to close)
- Focus trap

---

### 6. Navigation Sidebar Component
**Purpose:** Main navigation for all admin functions

```
ADMIN DASHBOARD
╔════════════════════════╗
║ ⊞ Dashboard            ║
║ 📚 Books               ║
║ 🏷️  Categories         ║
║ 👥 Users               ║
║ 📋 Borrow Requests     ║
║ ⭐ Reviews             ║
║ 📊 Reports             ║
║ ⚙️  Settings           ║
╠════════════════════════╣
║ ➡️  Logout              ║
║ ⓘ About                ║
╚════════════════════════╝
```

**Features:**
- Icon + Label
- Active state (highlight + left border)
- Hover effects
- Collapsible on mobile
- User profile section at bottom
- Logout button

---

### 7. Search & Filter Bar Component
**Purpose:** Data discovery and filtering

```
┌────────────────────────────────────────┐
│ 🔍 Search books...    | Category: All ▼│
│                       | Status: All ▼  │
│                       | [Clear Filters]│
└────────────────────────────────────────┘
```

**Elements:**
- Search input with icon
- Filter dropdowns
- Clear filters button
- Apply button (if needed)

---

### 8. Action Button Group Component
**Purpose:** Multiple actions on a record

```
┌─────────────────┐
│ Actions      ▼  │
├─────────────────┤
│ ✎ Edit          │
│ 👁 View         │
│ 🗑 Delete       │
│ 📋 Duplicate    │
└─────────────────┘
```

**Features:**
- Dropdown on click
- Icons + labels
- Color coding (danger for delete)
- Keyboard accessible

---

### 9. Toast Notification Component
**Purpose:** Feedback for user actions

```
✓ Book successfully added!        [✕]
[Green background, auto-dismiss in 4s]

⚠ Warning: Low stock!             [✕]
[Yellow background]

✗ Error: Could not delete book    [✕]
[Red background]
```

**Features:**
- Position: Top-right
- Auto-dismiss (4-5 seconds)
- Color-coded (success, warning, error)
- Close button
- Stack on multiple notifications

---

### 10. Breadcrumb Navigation Component
**Purpose:** Show current page location

```
Admin > Books > Edit Book > "The Great Gatsby"
```

**Features:**
- Clickable links (except current)
- Separator icon (>)
- Current page not linked

---

## Page-by-Page Layouts

### Page 1: Dashboard Home (`/dashboard/admin/index.php`)

**Purpose:** Overview of library system, quick stats, recent activity

**Layout Sections:**

```
1. HEADER
   - Page Title: "Dashboard"
   - Date/Time widget
   - Quick filters (Date range selector)

2. STATISTICS CARDS (4-column grid, responsive)
   ┌──────────┬──────────┬──────────┬──────────┐
   │  Books   │  Users   │ Borrows  │ Revenue  │
   │  1,250   │  485     │  125     │ $12,500  │
   │  ↑ 12%   │  ↑ 8%    │  ↓ 5%    │  ↑ 23%   │
   └──────────┴──────────┴──────────┴──────────┘

3. QUICK ACTIONS (6 cards with icons)
   ┌─────────────────────────────────────────┐
   │ + Add Book  │ + Add User  │ + Category   │
   │ ⚠ Pending   │ 📊 Reports  │ ⚙️ Settings  │
   └─────────────────────────────────────────┘

4. CHARTS SECTION (2-column layout)
   Left: Books Added This Year (Line Chart)
   Right: Category Distribution (Pie Chart)

5. RECENT ACTIVITY TABLE
   Title: "Recent Borrowing Activity"
   Columns: User | Book | Date | Status | Action
   Show: Last 10 transactions

6. RECENT BOOKS ADDED TABLE
   Title: "Latest Added Books"
   Columns: Cover | Title | Author | Date Added | Actions
   Show: Last 5 books
```

**Buttons:**
- "View All Books"
- "View All Users"
- "View All Requests"
- "Generate Report"

**Interactions:**
- Click stat cards → Navigate to respective page
- Charts interactive (hover for values)
- Sort tables by clicking headers
- Pagination on recent activity

---

### Page 2: Manage Books (`/dashboard/admin/books.php`)

**Purpose:** View, search, filter, and manage all books

**Layout:**

```
1. PAGE HEADER
   Title: "Books Library"
   Subtitle: "Manage all books in the system"

2. TOOLBAR
   ┌──────────────────────────────────────────┐
   │ 🔍 Search books...  | Category: All ▼    │
   │                     | Status: All ▼      │
   │ [+ Add New Book]    [📥 Import] [📤 Export]
   └──────────────────────────────────────────┘

3. STATISTICS MINI BAR
   Total: 1,250  |  Available: 980  |  Borrowed: 200  |  Damaged: 70

4. BOOKS TABLE (Sortable, Paginated)
   ┌─────────────────────────────────────────────────────────┐
   │ ☐ | Cover | Title | Author | Category | Qty | Actions │
   ├─────────────────────────────────────────────────────────┤
   │ ☐ |  📖   | Book  | Name   | Fiction  │ 5   │ ✎ 🗑 👁│
   │ ☐ |  📖   | Book  | Name   | Science  │ 3   │ ✎ 🗑 👁│
   │ ☐ |  📖   | Book  | Name   | History  │ 2   │ ✎ 🗑 👁│
   └─────────────────────────────────────────────────────────┘
   Showing 1-20 of 1,250

5. BULK ACTIONS (visible when items selected)
   ┌──────────────────────────┐
   │ 5 items selected         │
   │ [Delete] [Change Status] │
   └──────────────────────────┘
```

**Column Details:**
- Checkbox (select all)
- Cover (thumbnail image)
- Title (linked to details)
- Author
- Category (badge)
- Quantity (number with low-stock warning)
- Status (badge: Available, Borrowed, Damaged)
- Actions (Edit, Delete, View)

**Filters:**
- Search by title, author, ISBN
- Category dropdown
- Status (Available, Borrowed, Damaged, All)
- Date range (added between dates)

**Actions:**
- View book details
- Edit book
- Delete book
- Change status
- Duplicate book

**Empty State:**
```
📚 No books found
Try adjusting your search or filters
[+ Add Your First Book]
```

---

### Page 3: Add New Book (`/dashboard/admin/addBook.php`)

**Purpose:** Create new book entry

**Layout:**

```
1. PAGE HEADER
   Breadcrumb: Admin > Books > Add New Book
   Title: "Add New Book"

2. FORM SECTION - BASIC INFORMATION
   Title *              [__________________]
   Author *             [__________________]
   ISBN *               [__________________]
   Publication Year *   [________]

3. FORM SECTION - DETAILS
   Category *           [Select Category ▼]
   Description *        [Large text area]
   Language             [English ▼]
   Number of Pages      [________]

4. FORM SECTION - QUANTITY & AVAILABILITY
   Total Copies *       [________]
   Available Copies *   [________]
   Status *             [◉ Available ○ Out of Stock ○ Coming Soon]

5. FORM SECTION - MEDIA & ATTACHMENTS
   Cover Image *        [📤 Upload Image] [Preview box]
   PDF File (Optional)  [📤 Upload PDF]
   Summary/Tags         [__________________]

6. ACTION BUTTONS
   [Cancel] [Save as Draft] [Publish]
```

**Validation Rules:**
- All required fields marked with *
- Real-time validation feedback
- ISBN format validation
- Image size limit (2MB)
- PDF size limit (10MB)
- Auto-slug generation for URL

**Interactions:**
- Image preview on upload
- Character counter for description (max 500)
- Auto-save to draft every 30 seconds
- Unsaved changes warning on exit

---

### Page 4: Edit Book (`/dashboard/admin/editBook.php`)

**Purpose:** Modify existing book information

**Layout:** (Same as Add New Book)

**Additional Elements:**
- Book thumbnail on top
- "Last edited by [Admin Name] on [Date]"
- Version history link
- Preview button (see how book looks in public catalog)
- Duplicate button

---

### Page 5: Manage Categories (`/dashboard/admin/categories.php`)

**Purpose:** Organize and manage book categories

**Layout:**

```
1. PAGE HEADER
   Title: "Categories"
   Subtitle: "Organize book categories"

2. TOOLBAR
   🔍 Search categories...
   [+ Add New Category]

3. CATEGORIES GRID (Cards view, 3-column layout)
   ┌──────────────┐
   │ 📚 Fiction   │
   │ 245 books    │
   │ [✎] [🗑]     │
   └──────────────┘

4. OR TABLE VIEW (Toggle between grid/table)
   ┌────────────────────────────────────────┐
   │ ☐ | Name     | Books | Icon | Actions │
   ├────────────────────────────────────────┤
   │ ☐ | Fiction  | 245   | 📖   │ ✎ 🗑   │
   │ ☐ | Science  | 180   | 🔬   │ ✎ 🗑   │
   │ ☐ | History  | 92    | 📜   │ ✎ 🗑   │
   └────────────────────────────────────────┘

5. CATEGORY DETAILS MODAL (on click)
   Name:        [__________________]
   Description: [__________________]
   Icon:        [Select Icon ▼]
   Color:       [Pick color]
   [Cancel] [Save]
```

**Actions:**
- Add new category
- Edit category
- Delete category (with warning if books exist)
- Reorder categories (drag-drop)

---

### Page 6: Manage Users (`/dashboard/admin/users.php`)

**Purpose:** Manage library users and roles

**Layout:**

```
1. PAGE HEADER
   Title: "Users"
   Subtitle: "Manage user accounts and permissions"

2. TOOLBAR
   🔍 Search users...     | Role: All ▼
   | Status: All ▼        | Joined: All ▼
   [+ Add New User]

3. STATISTICS BAR
   Total Users: 485  |  Active: 420  |  Inactive: 65  |  Admins: 3

4. USERS TABLE
   ┌─────────────────────────────────────────────────────┐
   │ ☐ | Avatar | Name | Email | Role | Joined | Status │
   ├─────────────────────────────────────────────────────┤
   │ ☐ | 👤    | John | j@e   | User | Jan 15 | Active │
   │ ☐ | 👤    | Jane | j@e   | Admin| Feb 20 | Active │
   │ ☐ | 👤    | Bob  | b@e   | User | Mar 1  | Inactive
   └─────────────────────────────────────────────────────┘

5. USER DETAIL MODAL
   ┌──────────────────────────┐
   │ User Profile             │
   ├──────────────────────────┤
   │ 👤 Avatar                │
   │ Name: John Doe           │
   │ Email: john@example.com  │
   │ Role: User               │
   │ Joined: January 15, 2026 │
   │ Books Borrowed: 5        │
   │ Overdue: 1               │
   │ Account Status: Active   │
   │                          │
   │ [Reset Password]         │
   │ [Block User] [Delete]    │
   └──────────────────────────┘
```

**Filters:**
- Search by name, email, username
- Role (Admin, User, Guest)
- Status (Active, Inactive, Suspended)
- Joined date range
- Books borrowed count

**Actions:**
- View profile
- Edit user details
- Reset password
- Change role
- Block/Unblock user
- Delete user (with confirmation)

---

### Page 7: Borrow Requests Management (`/dashboard/admin/borrowings.php`)

**Purpose:** Manage book borrowing requests and returns

**Layout:**

```
1. PAGE HEADER
   Title: "Borrow Requests"
   Subtitle: "Manage book borrowing and returns"

2. TOOLBAR
   🔍 Search requests...  | Status: All ▼
   | Sort By: [Latest ▼]

3. STATISTICS BAR
   Total Requests: 125  |  Pending: 35  |  Approved: 75  |  Returned: 15

4. REQUESTS TABLE
   ┌──────────────────────────────────────────────────────────┐
   │ ☐ | User | Book | Date | Due | Status | Actions        │
   ├──────────────────────────────────────────────────────────┤
   │ ☐ | John | Book | 1/15 | 1/29|⏳ Wait │ [✓][✗][📄]     │
   │ ☐ | Jane | Book | 2/01 | 2/15|📘 Bor │ [🔄][✗][📄]    │
   │ ☐ | Bob  | Book | 1/20 | 2/3 |⚠ Due │ [🔄][✗][📄]    │
   └──────────────────────────────────────────────────────────┘

5. REQUEST DETAIL MODAL
   User: [Name] (Profile link)
   Email: user@example.com
   Book: [Title] (Book link)
   Requested Date: Jan 15, 2026
   Approval Date: Jan 15, 2026
   Due Date: Jan 29, 2026
   Actual Return: -
   Status: [Pending ▼]
   
   Notes: [Text area for internal notes]
   
   [Cancel Request] [Approve] [Mark as Returned]
```

**Statuses:**
- Pending (⏳)
- Approved (📘)
- Returned (✓)
- Overdue (⚠)
- Rejected (✗)

**Actions:**
- View request details
- Approve request
- Reject request
- Mark as returned
- Send reminder (email icon)
- View user profile
- View book details

**Filters:**
- Search by user name, book title, request ID
- Status (Pending, Approved, Returned, Overdue, Rejected)
- Date range
- Sort by: Latest, Oldest, Due Soon, Overdue

**Quick Actions:**
- Bulk approve pending requests
- Send overdue reminders
- Generate borrow report

---

### Page 8: Reviews Management (`/dashboard/admin/reviews.php`)

**Purpose:** Moderate and manage book reviews

**Layout:**

```
1. PAGE HEADER
   Title: "Reviews"
   Subtitle: "Moderate user reviews and ratings"

2. TOOLBAR
   🔍 Search reviews...   | Status: All ▼
   | Rating: All ▼

3. STATISTICS BAR
   Total Reviews: 324  |  Pending: 12  |  Approved: 300  |  Rejected: 12

4. REVIEWS TABLE
   ┌────────────────────────────────────────────────────┐
   │ Book | User | Rating | Date | Status | Actions    │
   ├────────────────────────────────────────────────────┤
   │ Book | John | ⭐⭐⭐⭐  | 2/15 |⏳ Wait│ [✓][✗][👁]│
   │ Book | Jane | ⭐⭐⭐   | 2/10 |✓ App │ [!][✗][👁]│
   │ Book | Bob  | ⭐     | 2/01 |✗ Rej │ [✓][?][👁]│
   └────────────────────────────────────────────────────┘

5. REVIEW DETAIL MODAL
   ┌──────────────────────────────────┐
   │ Review Details                   │
   ├──────────────────────────────────┤
   │ Book: [Title]                    │
   │ User: [Name]                     │
   │ Rating: ⭐⭐⭐⭐ (4.0)             │
   │ Date: February 15, 2026          │
   │                                  │
   │ "Great book! Highly recommend..."│
   │                                  │
   │ Status: [Pending ▼]              │
   │ [Flag as Spam] [Delete]          │
   │                                  │
   │ [Reject] [Approve]               │
   └──────────────────────────────────┘
```

**Statuses:**
- Pending (⏳)
- Approved (✓)
- Rejected (✗)
- Flagged (!)

**Actions:**
- Approve review
- Reject review
- Flag as spam
- Delete review
- View book
- View user profile
- Edit review (remove inappropriate content)

**Filters:**
- Search by book title, user name, review text
- Status (Pending, Approved, Rejected, Flagged)
- Rating (1-5 stars)
- Date range

---

### Page 9: Reports & Analytics (`/dashboard/admin/reports.php`)

**Purpose:** Generate and view library statistics

**Layout:**

```
1. PAGE HEADER
   Title: "Reports"
   Subtitle: "Library statistics and analytics"

2. DATE RANGE SELECTOR
   From: [Date ▼] To: [Date ▼] [Apply]

3. SECTION - LIBRARY OVERVIEW
   - Total Books Added (Line Chart - This Year)
   - Books by Category (Pie Chart)
   - Borrowing Trends (Bar Chart - Last 12 months)
   - Top Borrowed Books (Table - Top 10)

4. SECTION - USER ACTIVITY
   - New Users This Month (Card)
   - Active Users (Card)
   - Average Borrows per User (Card)
   - User Engagement Chart (Line)

5. SECTION - PERFORMANCE
   - Inventory Turnover (Card)
   - Average Borrowing Duration (Card)
   - Overdue Rate (Card)
   - Return on Time % (Card)

6. EXPORT SECTION
   [📥 Export as PDF] [📥 Export as CSV] [Print]
```

**Report Types:**
- Daily Summary
- Weekly Summary
- Monthly Summary
- Annual Summary
- Custom Date Range

---

### Page 10: Admin Settings (`/dashboard/admin/settings.php`)

**Purpose:** Configure admin preferences and system settings

**Layout:**

```
1. PAGE HEADER
   Title: "Settings"

2. SETTINGS TABS
   [Profile] [Preferences] [System] [Security]

TAB 1: PROFILE
   Avatar: [👤 Upload] [Preview]
   Full Name: [__________________]
   Email: [__________________]
   Phone: [__________________]
   Bio: [__________________]
   [Save Changes]

TAB 2: PREFERENCES
   Theme: [Light ◉] [Dark ○] [Auto ○]
   Language: [English ▼]
   Items per Page: [20 ▼]
   ☑ Email Notifications
   ☑ Desktop Notifications
   [Save Preferences]

TAB 3: SYSTEM
   Library Name: [__________________]
   Max Borrow Days: [______]
   Max Books per User: [______]
   Overdue Fine (per day): [______]
   [Save Settings]

TAB 4: SECURITY
   Change Password
   Current: [••••••••]
   New: [••••••••]
   Confirm: [••••••••]
   [Change Password]
   
   Two-Factor Authentication: [Enable]
   Last Login: Feb 20, 2026 at 10:30 AM
```

---

### Page 11: Admin Profile & Logout

**Purpose:** View admin profile and logout

**Layout:**

```
PROFILE DROPDOWN MENU (Top-right corner):
┌──────────────────────────────┐
│ 👤 John Doe (Admin)          │
│ john@library.com             │
├──────────────────────────────┤
│ 👤 My Profile                │
│ ⚙️ Settings                  │
│ 📧 Messages (5)              │
│ ❓ Help & Support            │
│ 🌙 Dark Mode                 │
├──────────────────────────────┤
│ ➡️  Logout                    │
└──────────────────────────────┘

PROFILE PAGE (/dashboard/admin/profile.php):
┌──────────────────────────────────────┐
│ 👤 Profile Picture (Large)           │
│ John Doe                             │
│ Administrator                        │
│                                      │
│ Email: john@library.com              │
│ Phone: +1 234 567 8900               │
│ Joined: January 2026                 │
│ Last Active: 2 hours ago             │
│                                      │
│ [Edit Profile] [Change Password]     │
│ [Two-Factor Auth]                    │
└──────────────────────────────────────┘
```

---

## Animation Strategy

### 1. Page Transitions
- Fade-in on page load (0.3s ease-out)
- Content slides up slightly from bottom (0.4s)
- Staggered card animations (80ms delay between cards)

```css
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
```

### 2. Sidebar Interactions
- Active state highlight with smooth border-left animation
- Icon rotation on hover (subtle 5-10 degree rotation)
- Smooth background color transition on hover (0.2s)
- Collapse animation (0.3s slide)

### 3. Card Hover Effects
- Lift effect: translateY(-4px)
- Shadow enhancement: small to medium shadow
- Background color brightening
- Duration: 0.2s ease-out

```css
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease-out;
}
```

### 4. Button Interactions
- Subtle scale effect on hover (1.02x)
- Color transition (0.15s)
- Active state: slight inset shadow
- Loading state: spinner animation

```css
.btn:hover {
    transform: scale(1.02);
    transition: all 0.15s ease-out;
}

.btn:active {
    transform: scale(0.98);
}
```

### 5. Table Row Hover
- Row background color change (subtle)
- Actions become more visible (opacity change)
- Duration: 0.15s

### 6. Modal Animations
- Backdrop fade-in: 0.2s
- Modal scale from 0.9 to 1.0 with fade: 0.3s
- Exit: reverse animation

```css
@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
```

### 7. Toast Notifications
- Slide-in from right: 0.3s
- Auto fade-out: 0.3s with slideOut
- Stack animations on multiple toasts

### 8. Stat Card Counters
- Number animation (1-2s) when page loads
- Smooth increase effect using CSS transitions or JS interval
- Accompany with subtle color pulse

```javascript
// Animate counter from 0 to target value
animateCounter(element, target, duration) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;
    const interval = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(interval);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}
```

### 9. Loading States
- Skeleton loaders for tables and cards
- Subtle pulse animation on skeleton
- Fade transition when content loads

### 10. Form Validation
- Input border color change (smooth transition 0.2s)
- Error message slide-down (0.15s)
- Success checkmark animation (scale and fade-in)

---

## Implementation Priority

### Phase 1: Foundation (Week 1)
**Critical for basic functionality**

1. ✅ Design System CSS (`admin-dashboard.css`)
   - Color variables
   - Typography
   - Spacing system
   - Border radius & shadows

2. ✅ Base Components CSS (`admin-components.css`)
   - Sidebar navigation
   - Top navbar
   - Stat cards
   - Basic buttons
   - Forms (inputs, labels, validation)

3. ✅ Layout Templates (`admin-header.php`, `admin-sidebar.php`)
   - Responsive master layout
   - Authentication checks
   - Mobile menu toggle

4. ✅ Admin Dashboard Home (`dashboard/admin/index.php`)
   - 4 stat cards
   - Placeholder charts
   - Recent activity table

### Phase 2: Core Features (Week 2)
**Essential admin functionality**

5. ✅ Books Management (`books.php`, `addBook.php`, `editBook.php`)
   - Table with search/filter
   - Add/Edit forms
   - CRUD operations

6. ✅ Data Table Component Enhancement
   - Pagination
   - Sorting
   - Inline actions
   - Bulk operations framework

7. ✅ API Endpoints (`api/admin/`)
   - Get statistics
   - Search/filter data
   - CRUD operations via AJAX

### Phase 3: Extended Modules (Week 3)
**Complete admin suite**

8. ✅ Categories Management (`categories.php`)
9. ✅ Users Management (`users.php`)
10. ✅ Borrowings Management (`borrowings.php`)
11. ✅ Reviews Management (`reviews.php`)

### Phase 4: Polish & Enhancement (Week 4)
**Refinement and optimization**

12. ✅ Animations & Interactions (`admin-animations.css`, `admin-interactions.js`)
13. ✅ Reports & Analytics (`reports.php`)
14. ✅ Settings & Profile Pages (`settings.php`, `profile.php`)
15. ✅ Dark Mode Support
16. ✅ Mobile Responsiveness Testing
17. ✅ Accessibility (ARIA labels, keyboard nav)

---

## File Structure Summary

```
library_project/
├── assets/
│   ├── css/
│   │   ├── admin-dashboard.css          (Design System)
│   │   ├── admin-components.css         (Component Styles)
│   │   ├── admin-animations.css         (Animations)
│   │   └── admin-responsive.css         (Media Queries)
│   └── js/
│       ├── admin-utils.js               (Utility Functions)
│       ├── admin-interactions.js        (Modal, Toast, etc.)
│       └── admin-charts.js              (Chart.js Integration)
│
├── includes/
│   ├── admin-header.php                 (Top navbar for admin)
│   ├── admin-sidebar.php                (Sidebar navigation)
│   └── admin-footer.php                 (Footer with scripts)
│
├── dashboard/admin/
│   ├── index.php                        (Dashboard Home)
│   ├── books.php                        (Manage Books)
│   ├── addBook.php                      (Add New Book)
│   ├── editBook.php                     (Edit Book)
│   ├── deleteBook.php                   (Delete Book - can be API)
│   ├── categories.php                   (Manage Categories)
│   ├── users.php                        (Manage Users)
│   ├── borrowings.php                   (Borrow Requests)
│   ├── reviews.php                      (Manage Reviews)
│   ├── reports.php                      (Analytics & Reports)
│   ├── settings.php                     (Admin Settings)
│   └── profile.php                      (Admin Profile)
│
└── api/admin/                           (API Endpoints)
    ├── get-stats.php
    ├── search-books.php
    ├── bulk-delete.php
    └── export-data.php
```

---

## Design System Quick Reference

| Element | Light Mode | Dark Mode | Hover | Notes |
|---------|-----------|-----------|-------|-------|
| Card | #F8FAFC | #1E293B | Lift+Shadow | 8px radius |
| Button Primary | #7C3AED | #7C3AED | #6D28D9 | 8px radius, 12px padding |
| Button Secondary | #E2E8F0 | #334155 | Color shift | Outline style |
| Badge Success | #DCFCE7 | #166534 | - | Solid bg, full radius |
| Input | #FFFFFF | #0F172A | Border shift | 8px radius, 1px border |
| Table Row | #FFFFFF | #0F172A | #F8FAFC/#1E293B | Subtle hover |
| Sidebar Active | Purple border + BG | Purple border + BG | - | Left 3px border |
| Stat Card | Gradient | Gradient | Lift | 12px radius |

---

## Component Library CSS Classes

### Quick Reference for Implementation

```css
/* Cards */
.card
.card-primary
.card-hover (lift effect)

/* Buttons */
.btn
.btn-primary
.btn-secondary
.btn-danger
.btn-outline
.btn-icon

/* Badges */
.badge
.badge-success
.badge-warning
.badge-danger
.badge-info

/* Tables */
.table
.table-hover
.table-striped
.table-responsive

/* Forms */
.form-group
.form-input
.form-select
.form-textarea
.form-checkbox
.form-error
.form-success

/* Utilities */
.text-primary
.text-secondary
.text-danger
.bg-soft-primary
.shadow-sm
.shadow-md
.shadow-lg
.rounded-sm
.rounded-md
.rounded-lg
```

---

## Recommended Next Steps

1. **Create Phase 1 CSS files** - Establish design system foundation
2. **Build admin layout templates** - Header, sidebar, footer includes
3. **Code Dashboard homepage** - First functional page
4. **Create Books management module** - Most complex, tests system
5. **Add animations** - Polish with micro-interactions
6. **Mobile optimization** - Responsive design refinement
7. **Performance optimization** - Lazy loading, caching
8. **Testing & refinement** - QA and user feedback

---

**Document Version:** 1.0  
**Last Updated:** March 11, 2026  
**Status:** Ready for Implementation

