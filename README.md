# E-Library System - University Project

A modern, responsive E-Library management system built with PHP, MySQL, HTML, CSS, JavaScript, Bootstrap, and TailwindCSS.

## 🎯 Features

### User Features
- **Browse Books**: Explore collection with filters by category and availability
- **Search**: Find books by title, author, or keywords
- **Book Details**: View detailed information, ratings, and reviews
- **Borrow Books**: Borrow books with automatic due date tracking
- **Favorites**: Save favorite books for quick access
- **User Dashboard**: Personal dashboard with statistics and activity
- **Profile Management**: Update profile information and change password
- **Dark/Light Mode**: Toggle between themes with persistent preference

### Design Features
- ✨ Modern, clean UI with purple/pink gradient theme
- 🎨 Smooth animations and transitions
- 📱 Fully responsive (mobile, tablet, desktop)
- 🌙 Dark mode support throughout
- 🎭 Book card hover effects and animations
- ⚡ Fast page load with optimized CSS
- 🔄 Smooth page transitions

## 🛠️ Tech Stack

### Frontend
- HTML5
- CSS3 (Custom + Bootstrap 5 + TailwindCSS)
- JavaScript (Vanilla JS)
- Font Awesome Icons
- Google Fonts (Sora, DM Sans)

### Backend
- PHP 7.4+
- MySQL/MariaDB
- XAMPP Environment

## 📁 Project Structure

```
library_project/
├── assets/
│   ├── css/
│   │   ├── auth.css
│   │   ├── animations.css
│   │   ├── books.css
│   │   ├── dashboard.css
│   │   ├── dark-mode.css
│   │   ├── dark-mode-auth.css
│   │   ├── index-style.css
│   │   ├── login-animations.css
│   │   ├── register-animations.css
│   │   └── style.css
│   ├── js/
│   │   ├── auth.js
│   │   ├── books.js
│   │   ├── dark-mode.js
│   │   ├── login.js
│   │   ├── nav-menu.js
│   │   └── theme-init.js
│   └── images/
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── books/
│   ├── brows.php (Browse all books)
│   ├── detaills.php (Book details)
│   └── search.php (Search books)
├── dashboard/
│   ├── dashboard.php (User dashboard)
│   ├── borrow.php (Borrowed books)
│   ├── favorits.php (Favorite books)
│   └── profile.php (User profile)
├── includes/
│   ├── header.php
│   ├── navbar.php
│   ├── footer.php
│   ├── config.php
│   └── db.php
├── api/
│   ├── toggle-favorite.php
│   ├── borrow-book.php
│   └── return-book.php
├── database/
│   └── library-db.sql
└── index.php
```

## 🚀 Installation

### Prerequisites
- XAMPP (or any PHP + MySQL environment)
- Web browser (Chrome, Firefox, Safari, Edge)

### Steps

1. **Clone/Download the project**
   ```
   Copy the library_project folder to C:\xampp\htdocs\
   ```

2. **Import Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `library-db`
   - Import `database/library-db.sql`

3. **Configure Database Connection**
   - Open `includes/db.php`
   - Update database credentials if needed:
     ```php
     $host = "localhost";
     $user = "root";
     $pass = "";
     $db   = "library-db";
     ```

4. **Start XAMPP**
   - Start Apache
   - Start MySQL

5. **Access the Application**
   ```
   http://localhost/library_project/
   ```

## 👤 Default Users

After importing the database, you can use these accounts:

**Admin:**
- Email: admin@library.com
- Password: aseel97.4

**Admin 2:**
- Email: admin2@library.com
- Password: rawan93.00

**Test User:**
- Email: test@example.com
- Password: test123 (hashed in database)

## 📊 Database Schema

### Main Tables
- **users**: User accounts (id, username, email, password, role, created_at)
- **books**: Book catalog (id, title, author, description, price, image, category_id, type, created_at)
- **categories**: Book categories
- **authors**: Book authors
- **borrowings**: Borrow records (user_id, book_id, borrow_date, return_date, status)
- **favorites**: User favorites (user_id, book_id, created_at)
- **reviews**: Book reviews (user_id, book_id, rating, comment, created_at)

## 🎨 Design System

### Color Palette
- Primary Purple: `#7c3aed`
- Primary Pink: `#ec4899`
- Background Light: `#f5f3f0`
- Text: `#1a1a2e`
- Muted: `#6b7280`

### Dark Mode Colors
- Background: `#0f0f0f`
- Cards: `#1a1a1a`
- Border: `#2a2a2a`
- Text: `#f5f5f5`

### Typography
- Headings: Sora (300-800)
- Body: DM Sans (300-600)

## 🔧 Key Features Implementation

### Dark Mode
- Persistent theme preference using localStorage
- Smooth toggle animation
- Comprehensive dark styles for all components

### Book Borrowing
- 14-day automatic borrow period
- Overdue detection with visual indicators
- Return functionality with status tracking

### Favorites
- Real-time toggle via AJAX
- Heart animation on favorite/unfavorite
- Persistent favorites list

### Search & Filters
- Live search by title, author, description
- Category filters
- Availability filters (borrow/sale/both)
- URL-based filter persistence

### Responsive Design
- Mobile-first approach
- Breakpoints: 576px, 768px, 992px, 1200px
- Collapsible sidebar on mobile
- Optimized grid layouts

## 🎭 Animations

- **Book Cards**: Fade-in, hover lift, image zoom
- **Favorite Button**: Heart beat animation
- **Empty States**: Bounce animation
- **Stats Cards**: Glow effect on hover
- **Borrowed Items**: Slide-in animation
- **Search Bar**: Focus sparkle effect
- **Buttons**: Ripple effect on click

## 📱 Browser Support

- Chrome (recommended)
- Firefox
- Safari
- Edge
- Opera

## 🔒 Security Features

- Password hashing (PHP password_hash)
- SQL injection prevention (prepared statements)
- Session management
- CSRF protection ready
- XSS prevention (htmlspecialchars)

## 📝 Future Enhancements

- [ ] Email notifications for due dates
- [ ] Advanced search with multiple filters
- [ ] Book recommendations based on history
- [ ] Reading progress tracking
- [ ] Social features (book clubs, discussions)
- [ ] PDF/EPUB reader integration
- [ ] Admin panel for book management
- [ ] Multi-language support
- [ ] Payment integration for sales

## 🐛 Known Issues

- None currently reported

## 📄 License

This is a university project for educational purposes.

## 👥 Credits

**Project Type**: University E-Library System
**Technologies**: PHP, MySQL, HTML, CSS, JavaScript, Bootstrap, TailwindCSS
**Design**: Modern library-themed UI with purple/pink gradient accent
**Icons**: Font Awesome 6.5.0
**Fonts**: Google Fonts (Sora, DM Sans)

## 📞 Support

For issues or questions:
1. Check the database connection in `includes/db.php`
2. Verify XAMPP services are running
3. Clear browser cache for style updates
4. Check browser console for JavaScript errors

---

**Happy Reading! 📚✨**

