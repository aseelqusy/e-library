# 🚀 E-Library Setup Guide

Complete step-by-step guide to get your E-Library system up and running.

---

## 📋 Prerequisites

Before you begin, make sure you have:

- ✅ **XAMPP** installed (version 7.4 or higher)
- ✅ **Modern web browser** (Chrome, Firefox, Safari, Edge)
- ✅ **Text editor** (VS Code, Sublime, PhpStorm - optional)

---

## 🔧 Step 1: Install XAMPP

If you haven't installed XAMPP:

1. Download from: https://www.apachefriends.org/
2. Run the installer
3. Install to default location: `C:\xampp`
4. Select components: **Apache** and **MySQL** (required)

---

## 📁 Step 2: Setup Project Files

1. **Copy project folder:**
   ```
   Copy the entire library_project folder to:
   C:\xampp\htdocs\library_project
   ```

2. **Verify folder structure:**
   ```
   C:\xampp\htdocs\library_project\
   ├── assets/
   ├── auth/
   ├── books/
   ├── dashboard/
   ├── includes/
   ├── api/
   ├── database/
   └── index.php
   ```

---

## 🗄️ Step 3: Setup Database

### Option A: Using phpMyAdmin (Recommended)

1. **Start XAMPP:**
   - Open XAMPP Control Panel
   - Click **Start** on Apache
   - Click **Start** on MySQL

2. **Open phpMyAdmin:**
   - Go to: http://localhost/phpmyadmin
   - Click "New" in the left sidebar

3. **Create Database:**
   - Database name: `library-db`
   - Collation: `utf8mb4_general_ci`
   - Click **Create**

4. **Import Schema:**
   - Select `library-db` from left sidebar
   - Click **Import** tab
   - Click **Choose File**
   - Select: `C:\xampp\htdocs\library_project\database\library-db.sql`
   - Click **Go** at bottom
   - Wait for "Import has been successfully finished"

5. **Import Sample Data (Optional but Recommended):**
   - With `library-db` still selected
   - Click **Import** tab again
   - Choose: `C:\xampp\htdocs\library_project\database\sample-data.sql`
   - Click **Go**

### Option B: Using MySQL Command Line

1. Open Command Prompt
2. Navigate to MySQL bin folder:
   ```cmd
   cd C:\xampp\mysql\bin
   ```

3. Login to MySQL:
   ```cmd
   mysql -u root -p
   ```
   (Press Enter when asked for password if you haven't set one)

4. Create and import:
   ```sql
   CREATE DATABASE `library-db`;
   USE `library-db`;
   SOURCE C:/xampp/htdocs/library_project/database/library-db.sql;
   SOURCE C:/xampp/htdocs/library_project/database/sample-data.sql;
   exit;
   ```

---

## ⚙️ Step 4: Configure Database Connection

1. **Open:** `C:\xampp\htdocs\library_project\includes\db.php`

2. **Verify settings:**
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";          // Leave empty for default XAMPP
   $db   = "library-db"; // Must match database name
   ```

3. **Save the file**

---

## 🎯 Step 5: Access the Application

1. **Make sure XAMPP services are running:**
   - Apache: ✅ Running
   - MySQL: ✅ Running

2. **Open your browser and go to:**
   ```
   http://localhost/library_project/
   ```

3. **You should see the E-Library homepage!** 🎉

---

## 👤 Step 6: Login to Test

### Test with existing accounts:

**Admin Account 1:**
- Email: `admin@library.com`
- Password: `aseel97.4`

**Admin Account 2:**
- Email: `admin2@library.com`
- Password: `rawan93.00`

**Regular User:**
- Email: `test@example.com`
- Password: `test123`

OR

**Create a new account:**
- Click "Sign Up" in the top right
- Fill in the registration form
- Start exploring!

---

## 🧪 Step 7: Test Features

### Test the following features:

1. **Homepage:**
   - ✅ Navigate to http://localhost/library_project/
   - ✅ Click theme toggle (moon/sun icon)
   - ✅ Verify dark/light mode works

2. **Browse Books:**
   - ✅ Click "Books" in navigation
   - ✅ Try category filters
   - ✅ Try availability filters
   - ✅ Test search functionality

3. **Book Details:**
   - ✅ Click on any book card
   - ✅ View full details
   - ✅ Add to favorites (heart icon)
   - ✅ Try borrowing a book

4. **User Dashboard:**
   - ✅ Login first
   - ✅ Click "Dashboard" in top right
   - ✅ Check statistics
   - ✅ View borrowed books
   - ✅ Check favorites
   - ✅ Edit profile

5. **Search:**
   - ✅ Use search bar in browse page
   - ✅ Try different keywords
   - ✅ Verify results display correctly

---

## 🐛 Troubleshooting

### Problem: "Connection failed" error

**Solution:**
- Make sure MySQL is running in XAMPP
- Check database name is `library-db` (with hyphen)
- Verify credentials in `includes/db.php`
- Try restarting MySQL in XAMPP

### Problem: "404 Not Found" errors

**Solution:**
- Verify project is in: `C:\xampp\htdocs\library_project`
- Check Apache is running
- Make sure URL is: `http://localhost/library_project/` (not `library-project`)

### Problem: CSS not loading / looks broken

**Solution:**
- Hard refresh browser: `Ctrl + F5`
- Check browser console for errors (F12)
- Verify all CSS files exist in `assets/css/`
- Clear browser cache

### Problem: Dark mode not working

**Solution:**
- Check browser console for JavaScript errors
- Verify `theme-init.js` is loading first
- Try clearing localStorage:
  ```javascript
  // In browser console (F12):
  localStorage.clear();
  location.reload();
  ```

### Problem: Books not showing

**Solution:**
- Import sample data: `database/sample-data.sql`
- Check if database has books:
  - Go to phpMyAdmin
  - Select `library-db`
  - Click on `books` table
  - Should see book records

### Problem: Can't borrow books

**Solution:**
- Make sure you're logged in
- Check book type is 'borrow' or 'both'
- Verify you haven't already borrowed that book
- Check browser console for API errors

### Problem: Favorites not working

**Solution:**
- Must be logged in
- Check `favorites` table exists in database
- Verify API files exist in `api/` folder
- Check browser console for errors

---

## 🔒 Security Notes

### For Production Use:

1. **Change default admin passwords** in database
2. **Set a MySQL root password** in XAMPP
3. **Update `includes/db.php`** with new password
4. **Enable SSL/HTTPS**
5. **Add CSRF tokens** to forms
6. **Set proper file permissions**
7. **Regular backups** of database

### Development Only:

The current setup is for **development/testing** only.
DO NOT use in production without proper security measures!

---

## 📊 Database Management

### Backup Database

**Using phpMyAdmin:**
1. Go to http://localhost/phpmyadmin
2. Select `library-db`
3. Click **Export** tab
4. Click **Go** (exports as SQL file)
5. Save the file

**Using Command Line:**
```cmd
cd C:\xampp\mysql\bin
mysqldump -u root library-db > backup.sql
```

### Reset Database

If you need to start fresh:

1. Go to phpMyAdmin
2. Select `library-db`
3. Click **Drop** (deletes database)
4. Follow Step 3 again to recreate

---

## 🎨 Customization

### Change Color Scheme

Edit `assets/css/style.css` or `index-style.css`:

```css
:root {
    --purple: #7c3aed;  /* Change primary color */
    --pink: #ec4899;    /* Change accent color */
    --bg: #f5f3f0;      /* Change background */
}
```

### Change Fonts

Edit `includes/header.php`:

```html
<link href="https://fonts.googleapis.com/css2?family=YOUR_FONT" rel="stylesheet">
```

Then update CSS:
```css
body {
    font-family: 'YOUR_FONT', sans-serif;
}
```

### Add More Categories

Go to phpMyAdmin → `library-db` → `categories` → Insert:
```sql
INSERT INTO categories (name) VALUES ('Your Category Name');
```

---

## 📱 Mobile Testing

Test on different screen sizes:

1. Open browser DevTools (F12)
2. Click device toolbar icon (Ctrl+Shift+M)
3. Test these sizes:
   - 📱 Mobile: 375px (iPhone)
   - 📱 Mobile Large: 425px
   - 📲 Tablet: 768px
   - 💻 Desktop: 1440px

---

## 📈 Performance Tips

1. **Optimize Images:**
   - Use WebP format
   - Compress images before upload
   - Recommended size: 300x400px for book covers

2. **Enable Caching:**
   - Add `.htaccess` file with cache headers
   - Enable browser caching

3. **Minify CSS/JS** for production:
   - Use online tools to minify
   - Combine files to reduce requests

---

## 🎓 Learning Resources

**PHP:**
- https://www.php.net/manual/en/
- https://www.w3schools.com/php/

**MySQL:**
- https://dev.mysql.com/doc/
- https://www.mysqltutorial.org/

**CSS:**
- https://developer.mozilla.org/en-US/docs/Web/CSS
- https://css-tricks.com/

**JavaScript:**
- https://developer.mozilla.org/en-US/docs/Web/JavaScript
- https://javascript.info/

---

## ✅ Final Checklist

Before presenting your project:

- [ ] Database imported successfully
- [ ] Sample data loaded
- [ ] Can access homepage
- [ ] Can register new account
- [ ] Can login
- [ ] Can browse books
- [ ] Can view book details
- [ ] Can add to favorites
- [ ] Can borrow books
- [ ] Dashboard loads correctly
- [ ] Profile page works
- [ ] Dark mode toggles properly
- [ ] Responsive on mobile
- [ ] No console errors

---

## 🎉 You're All Set!

Your E-Library System is now ready to use!

### Quick Links:
- **Homepage:** http://localhost/library_project/
- **Login:** http://localhost/library_project/auth/login.php
- **Register:** http://localhost/library_project/auth/register.php
- **Browse Books:** http://localhost/library_project/books/brows.php
- **phpMyAdmin:** http://localhost/phpmyadmin

---

## 💡 Tips for Your Presentation

1. **Demo Flow:**
   - Start at homepage
   - Show register/login
   - Browse books with filters
   - Borrow a book
   - Show dashboard
   - Add to favorites
   - Toggle dark mode

2. **Highlight Features:**
   - Responsive design (resize browser)
   - Dark mode support
   - Smooth animations
   - User-friendly interface
   - Modern design

3. **Technical Points:**
   - Clean code structure
   - Reusable components
   - Secure database queries (prepared statements)
   - Session management
   - RESTful API endpoints

---

**Need Help?** Check the README.md file or review the code comments.

**Happy Coding! 📚✨**

