<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    // Validate inputs
    if (empty($new_username) || empty($new_email)) {
        $error_message = 'Username and email are required';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format';
    } else {
        // Check if email is already taken by another user
        $check_query = "SELECT id FROM users WHERE email = ? AND id != ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "si", $new_email, $user_id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $error_message = 'Email is already in use by another account';
        } else {
            // Update profile
            $update_query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "ssi", $new_username, $new_email, $user_id);

            if (mysqli_stmt_execute($update_stmt)) {
                $_SESSION['username'] = $new_username;
                $_SESSION['email'] = $new_email;
                $success_message = 'Profile updated successfully!';
            } else {
                $error_message = 'Failed to update profile';
            }
        }
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    

        $error_message = 'All password fields are required';
    } elseif ($new_password !== $confirm_password) {
        $error_message = 'New passwords do not match';
    } elseif (strlen($new_password) < 6) {
        $error_message = 'Password must be at least 6 characters';
    } else {
        // Verify current password
        $verify_query = "SELECT password FROM users WHERE id = ?";
        $verify_stmt = mysqli_prepare($conn, $verify_query);
        mysqli_stmt_bind_param($verify_stmt, "i", $user_id);
        mysqli_stmt_execute($verify_stmt);
        $verify_result = mysqli_stmt_get_result($verify_stmt);
        $user_data = mysqli_fetch_assoc($verify_result);
        
        // Check if password is hashed or plain text
        $password_verified = false;

            $password_verified = true;
        } elseif ($current_password === $user_data['password']) {
            $password_verified = true;
        }
        
        if (!$password_verified) {
            $error_message = 'Current password is incorrect';
        } else {
            // Update password

            $update_query = "UPDATE users SET password = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "si", $hashed_password, $user_id);
            
            if (mysqli_stmt_execute($update_stmt)) {
                $success_message = 'Password changed successfully!';
            } else {
                $error_message = 'Failed to change password';
            }
        }
    }
}


$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

$username = $user['username'];
$email = $user['email'];
$role = $user['role'];
$created_at = $user['created_at'];
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">
<link rel="stylesheet" href="/library_project/assets/css/dashboard.css">

<?php include('../includes/navbar.php'); ?>

<div class="dashboard-wrapper">
    
    <!-- SIDEBAR -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo strtoupper(substr($username, 0, 2)); ?>
            </div>

            <div class="sidebar-email"><?php echo htmlspecialchars($email); ?></div>
        </div>
        
        <ul class="sidebar-nav">
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/dashboard.php" class="sidebar-nav-link">
                    <i class="fas fa-home sidebar-nav-icon"></i>
                    Dashboard
                </a>
            </li>
            <li class="sidebar-nav-item">

                    <i class="fas fa-book sidebar-nav-icon"></i>
                    My Borrowed Books
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/favorits.php" class="sidebar-nav-link">
                    <i class="fas fa-heart sidebar-nav-icon"></i>
                    Favorites
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/dashboard/profile.php" class="sidebar-nav-link active">
                    <i class="fas fa-user sidebar-nav-icon"></i>
                    Profile
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/books/brows.php" class="sidebar-nav-link">
                    <i class="fas fa-search sidebar-nav-icon"></i>
                    Browse Books
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="/library_project/auth/logout.php" class="sidebar-nav-link" style="color: #ef4444;">
                    <i class="fas fa-sign-out-alt sidebar-nav-icon"></i>
                    Logout
                </a>
            </li>
        </ul>
    </aside>
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-main">
        
        <div class="profile-container">
            
            <!-- PAGE HEADER -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user"></i> My Profile
                </h1>
                <p class="page-subtitle">
                    Manage your account information and settings
                </p>


            <!-- SUCCESS/ERROR MESSAGES -->

                <div class="alert alert-success">

                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <?php echo htmlspecialchars($error_message); ?>

            <?php endif; ?>
            
            <!-- PROFILE CARD -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-section">
                        <div class="profile-avatar">

                        </div>
                        <button class="edit-avatar-btn" title="Change Avatar">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($username); ?></h1>

                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?>
                        </div>
                        <div class="profile-member-since">
                            <i class="fas fa-calendar"></i> Member since <?php echo date('M Y', strtotime($created_at)); ?>
                        </div>
                        <div class="profile-member-since" style="margin-top: 4px;">
                            <i class="fas fa-shield-alt"></i> Role: <strong><?php echo ucfirst($role); ?></strong>
                        </div>
                    </div>
                </div>
                
                <!-- EDIT PROFILE FORM -->
                <form method="POST" class="profile-form">
                    <h3 style="font-family: 'Sora', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 20px;">
                        <i class="fas fa-edit"></i> Edit Profile Information
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               class="form-input" 

                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 

                               value="<?php echo htmlspecialchars($email); ?>"
                               required>
                    </div>
                    
                    <div class="form-actions">
                        <input type="text"
                               id="username"
                               name="username"
                               class="form-input"
                               value="<?php echo htmlspecialchars($username); ?>"

                        </button>
                    </div>
                </form>
            </div>
            
            <!-- CHANGE PASSWORD CARD -->
            <div class="profile-card">
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-input"
                               value="<?php echo htmlspecialchars($email); ?>"
                <form method="POST" class="profile-form">

                        <label class="form-label" for="current_password">Current Password</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="form-input" 
                               placeholder="Enter your current password"
                               required>
                    </div>
                    

                        <label class="form-label" for="new_password">New Password</label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               class="form-input" 

                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm New Password</label>
                        <input type="password" 
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               class="form-input"
                               required>
                    </div>
                    

                        <button type="submit" name="change_password" class="btn-save">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                        <input type="password"
                               id="new_password"
                               name="new_password"
                               class="form-input"
                </form>
            </div>
            
            <!-- READING ACTIVITY -->
            <div class="activity-card">


                <div class="activity-timeline">
                    <div class="activity-item">
                        <div class="activity-icon">
                        <input type="password"
                               id="confirm_password"
                               name="confirm_password"
                               class="form-input"
                            <div class="activity-description">You've borrowed a total of <?php

                                $stmt = mysqli_prepare($conn, $total_borrowed_query);
                                mysqli_stmt_bind_param($stmt, "i", $user_id);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                echo mysqli_fetch_assoc($result)['count'];
                            ?> books</div>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i> All time

                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">

                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Favorite Books</div>
                            <div class="activity-description">You have <?php 
                                $favorites_query = "SELECT COUNT(*) as count FROM favorites WHERE user_id = ?";
                            <div class="activity-description">You've borrowed a total of <?php
                                mysqli_stmt_bind_param($stmt, "i", $user_id);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                echo mysqli_fetch_assoc($result)['count'];
                            ?> favorite books</div>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i> Updated regularly
                            </div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-star"></i>
                        </div>

                            <div class="activity-title">Reviews Written</div>
                            <div class="activity-description">You've written <?php 
                                $reviews_query = "SELECT COUNT(*) as count FROM reviews WHERE user_id = ?";
                                $stmt = mysqli_prepare($conn, $reviews_query);
                            <div class="activity-description">You have <?php
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                echo mysqli_fetch_assoc($result)['count'];
                            ?> reviews</div>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i> Helping others discover books
                            </div>
                        </div>
                    </div>


                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="activity-content">

                            <div class="activity-description">You joined E-Library on <?php echo date('M d, Y', strtotime($created_at)); ?></div>
                            <div class="activity-time">
                                <i class="fas fa-clock"></i> <?php
                                <i class="fas fa-clock"></i> <?php
                            <div class="activity-description">You've written <?php
                                    echo "$days days ago";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

        
    </main>

    
</div>

<?php include('../includes/footer.php'); ?>

<script src="/library_project/assets/js/books.js"></script>

