# 🧪 Quick Test Script

## Test the E-Library System Step by Step

### ✅ Pre-Flight Checklist

Before testing, verify:
- [ ] XAMPP Apache is running (green in control panel)
- [ ] XAMPP MySQL is running (green in control panel)
- [ ] Database `library-db` exists
- [ ] Database tables imported successfully
- [ ] Sample data imported (optional but recommended)

---

## 🔍 Testing Sequence

### Test 1: Homepage ✨
**URL:** http://localhost/library_project/

**Expected:**
- ✅ Page loads without errors
- ✅ Purple/pink gradient design visible
- ✅ Top bar shows "Log In" and "Sign Up" buttons
- ✅ Navigation menu displays correctly
- ✅ Hero section with stats cards visible
- ✅ Footer displays

**Actions:**
1. Click theme toggle (moon/sun icon)
2. Verify dark mode activates
3. Refresh page - dark mode should persist
4. Toggle back to light mode

**Status:** [ ] PASS [ ] FAIL

---

### Test 2: Registration 📝
**URL:** http://localhost/library_project/auth/register.php

**Expected:**
- ✅ Beautiful split-panel design loads
- ✅ Left panel shows animations (books, stats)
- ✅ Registration form on right side
- ✅ Step indicators visible (1, 2, 3)

**Actions:**
1. Enter test data:
   - Username: `testuser2`
   - Email: `test2@example.com`
   - Password: `test123456`
   - Confirm Password: `test123456`
2. Check "I agree to terms"
3. Click "Create Account"
4. Should redirect to dashboard

**Status:** [ ] PASS [ ] FAIL

---

### Test 3: Login 🔐
**URL:** http://localhost/library_project/auth/login.php

**Test with existing user:**
- Email: `test@example.com`
- Password: `test123`

OR use admin:
- Email: `admin@library.com`
- Password: `aseel97.4`

**Expected:**
- ✅ Login form loads
- ✅ Can enter credentials
- ✅ Successful login redirects to dashboard
- ✅ Session persists (user stays logged in)

**Status:** [ ] PASS [ ] FAIL

---

### Test 4: Browse Books 📚
**URL:** http://localhost/library_project/books/brows.php

**Expected:**
- ✅ Hero search section displays
- ✅ Filter chips show categories and availability
- ✅ Books display in responsive grid
- ✅ Each card shows: image, title, author, description
- ✅ Hover effects work (card lifts, image zooms)
- ✅ Heart icon for favorites visible

**Actions:**
1. Click a category filter chip (e.g., "Fiction")
2. Verify URL updates with ?categories=Fiction
3. Verify books filter to show only that category
4. Click "Clear All" filters
5. Try "For Borrow" filter
6. Test on mobile size (resize browser to 400px)

**Status:** [ ] PASS [ ] FAIL

---

### Test 5: Search Books 🔍
**URL:** http://localhost/library_project/books/search.php

**Expected:**
- ✅ Large search hero section
- ✅ Search input focused and ready

**Actions:**
1. Enter search term: "Harry Potter"
2. Click Search
3. Verify results display
4. Try search: "Orwell"
5. Try search with no results: "zzzzz"
6. Verify empty state shows

**Status:** [ ] PASS [ ] FAIL

---

### Test 6: Book Details 📖
**URL:** Click any book from browse page

**Expected:**
- ✅ Large book image on left (desktop)
- ✅ Full details on right: title, author, description
- ✅ Book info card with type, price, date
- ✅ "Borrow This Book" button (if available)
- ✅ "Add to Favorites" button
- ✅ Reviews section below
- ✅ Responsive (image moves to top on mobile)

**Actions:**
1. Click "Add to Favorites" heart button
2. Verify heart turns red/filled
3. Click "Borrow This Book"
4. Verify success message
5. Check button changes to "Already Borrowed"

**Status:** [ ] PASS [ ] FAIL

---

### Test 7: User Dashboard 🎯
**URL:** http://localhost/library_project/dashboard/dashboard.php

**Must be logged in first!**

**Expected:**
- ✅ Sidebar on left with user avatar and navigation
- ✅ Welcome banner with user name
- ✅ 4 stat cards showing counts
- ✅ Quick actions grid
- ✅ Recently borrowed books section
- ✅ Recommended books grid

**Actions:**
1. Verify username displays correctly
2. Check stat numbers (should show actual counts)
3. Click "Browse Books" quick action
4. Navigate back to dashboard

**Status:** [ ] PASS [ ] FAIL

---

### Test 8: Borrowed Books 📋
**URL:** http://localhost/library_project/dashboard/borrow.php

**Expected:**
- ✅ List of borrowed books
- ✅ Each item shows: image, title, author
- ✅ Borrow date and due date displayed
- ✅ Status badge (Borrowed/Overdue/Returned)
- ✅ "Return Book" button for active borrows

**Actions:**
1. Verify borrowed books display
2. Check dates are formatted correctly
3. Click "Return Book"
4. Verify book disappears from list
5. Check empty state if no books borrowed

**Status:** [ ] PASS [ ] FAIL

---

### Test 9: Favorites ❤️
**URL:** http://localhost/library_project/dashboard/favorits.php

**Expected:**
- ✅ Grid of favorite books
- ✅ All favorited books display
- ✅ Heart icon is filled/red
- ✅ Can view details from here
- ✅ Can borrow from favorites

**Actions:**
1. Verify favorite books display
2. Click heart to remove from favorites
3. Verify book disappears
4. Add new favorite from browse page
5. Return to favorites - should see new book

**Status:** [ ] PASS [ ] FAIL

---

### Test 10: Profile Page 👤
**URL:** http://localhost/library_project/dashboard/profile.php

**Expected:**
- ✅ Large avatar with initials
- ✅ User info displayed
- ✅ Edit profile form with current values
- ✅ Change password form
- ✅ Reading activity timeline

**Actions:**
1. Change username to something else
2. Click "Save Changes"
3. Verify success message
4. Check username updates in sidebar
5. Try changing password
6. Verify old password validation works

**Status:** [ ] PASS [ ] FAIL

---

### Test 11: Dark Mode 🌙
**Test on all pages**

**Expected:**
- ✅ Dark mode toggle works on all pages
- ✅ Theme persists after refresh
- ✅ All text is readable
- ✅ Cards have proper contrast
- ✅ No white flashes on page load

**Actions:**
1. Toggle to dark mode on homepage
2. Navigate to browse books - should stay dark
3. Go to dashboard - should stay dark
4. Refresh page - should stay dark
5. Toggle back to light mode
6. Verify all pages return to light

**Status:** [ ] PASS [ ] FAIL

---

### Test 12: Responsive Design 📱

**Test these breakpoints:**

**Mobile (375px):**
- [ ] Homepage hero responsive
- [ ] Book cards stack to 1 column
- [ ] Dashboard sidebar becomes horizontal
- [ ] Forms are mobile-friendly
- [ ] Navigation is accessible

**Tablet (768px):**
- [ ] Book grid shows 2-3 columns
- [ ] Dashboard layout adjusts
- [ ] All content readable

**Desktop (1200px+):**
- [ ] Full grid layout (4 columns)
- [ ] Sidebar sticky
- [ ] Optimal spacing

**How to test:**
1. Open browser DevTools (F12)
2. Click device toolbar icon (Ctrl+Shift+M)
3. Select different devices
4. Or manually resize browser window

**Status:** [ ] PASS [ ] FAIL

---

### Test 13: API Endpoints 🔌

**Test via browser console (F12):**

**Test Favorite Toggle:**
```javascript
fetch('/library_project/api/toggle-favorite.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ book_id: 1 })
})
.then(r => r.json())
.then(d => console.log(d));
```

**Test Borrow Book:**
```javascript
fetch('/library_project/api/borrow-book.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ book_id: 2 })
})
.then(r => r.json())
.then(d => console.log(d));
```

**Expected:**
- ✅ Returns JSON response
- ✅ Success: true/false
- ✅ Message included
- ✅ Requires login (redirects if not logged in)

**Status:** [ ] PASS [ ] FAIL

---

### Test 14: Database Integrity 🗄️

**Check in phpMyAdmin:**

1. **Users Table:**
   - [ ] Has records
   - [ ] Passwords are hashed (except demo accounts)
   - [ ] Emails are unique

2. **Books Table:**
   - [ ] Has sample books
   - [ ] Images URLs are valid
   - [ ] Categories linked properly

3. **Borrowings Table:**
   - [ ] Borrows are created when button clicked
   - [ ] Return dates are 14 days ahead
   - [ ] Status updates to 'returned' when returned

4. **Favorites Table:**
   - [ ] Records created when heart clicked
   - [ ] Records deleted when unfavorited
   - [ ] User_id and book_id are valid

**Status:** [ ] PASS [ ] FAIL

---

### Test 15: Error Handling ⚠️

**Test error scenarios:**

1. **Login with wrong password:**
   - [ ] Shows error message
   - [ ] Doesn't crash
   - [ ] Form stays filled

2. **Access dashboard without login:**
   - [ ] Redirects to login page
   - [ ] Session check works

3. **Invalid book ID in URL:**
   - [ ] Redirects to browse page
   - [ ] Doesn't show error

4. **Database connection failure:**
   - [ ] Shows error message (test by changing db.php)

**Status:** [ ] PASS [ ] FAIL

---

## 📊 Test Results Summary

| Feature | Status | Notes |
|---------|--------|-------|
| Homepage | ⬜ | |
| Registration | ⬜ | |
| Login | ⬜ | |
| Browse Books | ⬜ | |
| Search | ⬜ | |
| Book Details | ⬜ | |
| Dashboard | ⬜ | |
| Borrowed Books | ⬜ | |
| Favorites | ⬜ | |
| Profile | ⬜ | |
| Dark Mode | ⬜ | |
| Responsive | ⬜ | |
| API Endpoints | ⬜ | |
| Database | ⬜ | |
| Error Handling | ⬜ | |

**Legend:** ⬜ Not Tested | ✅ Pass | ❌ Fail

---

## 🐛 Common Issues & Solutions

### Issue: Books not showing
**Solution:** Import sample-data.sql file

### Issue: Favorites not working
**Solution:** Check if logged in, verify favorites table exists

### Issue: Styles broken
**Solution:** Hard refresh (Ctrl + F5), check CSS files exist

### Issue: Can't login
**Solution:** Check database connection, verify user exists

### Issue: Session not persisting
**Solution:** Check session_start() is at top of files

---

## 🎉 Final Checklist

Before submitting/presenting:

- [ ] All pages load without errors
- [ ] Can register and login
- [ ] Can browse and search books
- [ ] Can borrow books
- [ ] Can add/remove favorites
- [ ] Dashboard shows correct stats
- [ ] Profile updates work
- [ ] Dark mode works everywhere
- [ ] Responsive on all devices
- [ ] No console errors (check F12)
- [ ] Database has sample data
- [ ] Documentation is complete

---

## 📝 Test Notes

Use this space to write any issues found:

```
Date: ___________
Tester: __________

Issues Found:
1. 
2. 
3. 

Fixed:
1. 
2. 
3. 

```

---

**Testing Complete!** ✅

Once all tests pass, your E-Library System is ready for presentation! 🎓📚

