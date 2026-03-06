<?php
session_start();

$success_message = '';
$error_message = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email address';
    } else {
        // In a real application, you would send an email or save to database
        $success_message = 'Thank you for contacting us! We\'ll get back to you soon.';

        // Clear form
        $name = $email = $subject = $message = '';
    }
}
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">
<link rel="stylesheet" href="/library_project/assets/css/dashboard.css">

<?php include('../includes/navbar.php'); ?>

<!-- CONTACT PAGE -->
<div class="page-container">

    <!-- PAGE HEADER -->
    <div class="page-header" style="text-align: center; padding: 60px 0 40px;">
        <h1 class="page-title" style="font-size: 48px; margin-bottom: 16px;">
            Contact Us
        </h1>
        <p class="page-subtitle" style="font-size: 18px; max-width: 700px; margin: 0 auto;">
            Have a question or feedback? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
        </p>
    </div>

    <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        
        <div class="contact-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 60px;">

            <!-- CONTACT FORM -->
            <div>
                <div style="background: var(--white); border: 1px solid var(--border); border-radius: 20px; padding: 40px;">
                    <h2 style="font-family: 'Sora', sans-serif; font-size: 24px; font-weight: 700; margin-bottom: 24px;">
                        Send Us a Message
                    </h2>

                    <?php if ($success_message): ?>
                    <div class="alert alert-success" style="margin-bottom: 24px;">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                    <div class="alert alert-error" style="margin-bottom: 24px;">
                        <i class="fas fa-exclamation-circle alert-icon"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" class="profile-form">
                        <div class="form-group">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-input" placeholder="John Doe" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" placeholder="your@email.com" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-input" placeholder="How can we help?" value="<?php echo isset($subject) ? htmlspecialchars($subject) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-input form-textarea" rows="6" placeholder="Tell us more..." required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                        </div>

                        <button type="submit" class="btn-save" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- CONTACT INFO -->
            <div>
                <div style="background: var(--white); border: 1px solid var(--border); border-radius: 20px; padding: 40px; margin-bottom: 24px;">
                    <h2 style="font-family: 'Sora', sans-serif; font-size: 24px; font-weight: 700; margin-bottom: 24px;">
                        Get in Touch
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 24px;">

                        <div style="display: flex; align-items: start; gap: 16px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--purple), var(--pink)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; flex-shrink: 0;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Email</h3>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">info@elibrary.com</p>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">support@elibrary.com</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: start; gap: 16px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; flex-shrink: 0;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Phone</h3>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">+1 (555) 123-4567</p>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">Mon-Fri 9am-6pm</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: start; gap: 16px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; flex-shrink: 0;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Address</h3>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">123 Library Street</p>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">Booktown, BK 12345</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: start; gap: 16px;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; flex-shrink: 0;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Hours</h3>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">Monday - Friday: 9am - 6pm</p>
                                <p style="font-size: 14px; color: var(--muted); margin: 0;">Weekend: 10am - 4pm</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- SOCIAL MEDIA -->
                <div style="background: linear-gradient(145deg, #4c1d95 0%, #7c3aed 40%, #9333ea 65%, #ec4899 100%); border-radius: 20px; padding: 32px; text-align: center; color: white;">
                    <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">Follow Us</h3>
                    <p style="font-size: 14px; opacity: 0.9; margin-bottom: 24px;">Stay connected on social media</p>
                    <div style="display: flex; gap: 12px; justify-content: center;">
                        <button style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); border: none; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; cursor: pointer; transition: background 0.3s;">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); border: none; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; cursor: pointer;">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); border: none; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; cursor: pointer;">
                            <i class="fab fa-instagram"></i>
                        </button>
                        <button style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); border: none; display: flex; align-items: center; justify-content: center; font-size: 20px; color: white; cursor: pointer;">
                            <i class="fab fa-linkedin-in"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- FAQ SECTION -->
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: 20px; padding: 40px; margin-bottom: 60px;">
            <h2 style="font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 700; text-align: center; margin-bottom: 32px;">
                Frequently Asked Questions
            </h2>

            <div style="display: grid; gap: 20px;">
                <details style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 20px; cursor: pointer;">
                    <summary style="font-size: 16px; font-weight: 600; color: var(--text); list-style: none; display: flex; justify-content: space-between; align-items: center;">
                        How do I borrow a book?
                        <i class="fas fa-chevron-down" style="color: var(--purple);"></i>
                    </summary>
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin-top: 12px; margin-bottom: 0;">
                        Simply browse our collection, click on a book you're interested in, and click the "Borrow Book" button. The book will be added to your account for 14 days.
                    </p>
                </details>

                <details style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 20px; cursor: pointer;">
                    <summary style="font-size: 16px; font-weight: 600; color: var(--text); list-style: none; display: flex; justify-content: space-between; align-items: center;">
                        Is E-Library free to use?
                        <i class="fas fa-chevron-down" style="color: var(--purple);"></i>
                    </summary>
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin-top: 12px; margin-bottom: 0;">
                        Yes! E-Library is completely free to use. Simply create an account and start browsing our collection.
                    </p>
                </details>

                <details style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 20px; cursor: pointer;">
                    <summary style="font-size: 16px; font-weight: 600; color: var(--text); list-style: none; display: flex; justify-content: space-between; align-items: center;">
                        How long can I keep a borrowed book?
                        <i class="fas fa-chevron-down" style="color: var(--purple);"></i>
                    </summary>
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin-top: 12px; margin-bottom: 0;">
                        You can keep a borrowed book for 14 days. After that, it will be automatically returned and available for others to borrow.
                    </p>
                </details>

                <details style="background: var(--bg); border: 1px solid var(--border); border-radius: 12px; padding: 20px; cursor: pointer;">
                    <summary style="font-size: 16px; font-weight: 600; color: var(--text); list-style: none; display: flex; justify-content: space-between; align-items: center;">
                        Can I download books?
                        <i class="fas fa-chevron-down" style="color: var(--purple);"></i>
                    </summary>
                    <p style="font-size: 14px; color: var(--muted); line-height: 1.6; margin-top: 12px; margin-bottom: 0;">
                        Currently, books are available for online reading only. We're working on adding download functionality in the future.
                    </p>
                </details>
            </div>
        </div>

    </div>

</div>

<?php include('../includes/footer.php'); ?>

<style>
:root {
    --white: #ffffff;
    --border: #e5e7eb;
    --text: #1a1a2e;
    --muted: #6b7280;
    --purple: #7c3aed;
    --pink: #ec4899;
    --bg: #f9fafb;
}

[data-theme="dark"] {
    --white: #1a1a1a;
    --border: #2a2a2a;
    --text: #f5f5f5;
    --muted: #b0b0b0;
    --bg: #0f0f0f;
}

body {
    background: var(--bg);
}

details[open] summary i {
    transform: rotate(180deg);
}

details summary i {
    transition: transform 0.3s ease;
}

/* RESPONSIVE STYLES */
@media (max-width: 768px) {
    /* Page header */
    .page-header {
        padding: 40px 20px 30px !important;
    }
    
    .page-title {
        font-size: 32px !important;
    }
    
    .page-subtitle {
        font-size: 16px !important;
        padding: 0 20px;
    }
    
    /* Make contact grid single column on mobile */
    .contact-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
    }
    
    /* Reduce padding on cards */
    .contact-grid > div > div {
        padding: 24px !important;
        border-radius: 16px !important;
    }
    
    /* Social buttons container */
    .contact-grid div[style*="gap: 12px"][style*="justify-content: center"] button {
        width: 44px !important;
        height: 44px !important;
        font-size: 18px !important;
    }
    
    /* FAQ section */
    .page-container > div > div:last-of-type {
        padding: 24px !important;
        margin: 0 0 40px !important;
    }
    
    details {
        padding: 16px !important;
    }
    
    /* Form elements */
    .form-input,
    .form-textarea {
        font-size: 15px !important;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 30px 16px 24px !important;
    }
    
    .page-title {
        font-size: 26px !important;
    }
    
    .page-subtitle {
        font-size: 14px !important;
        padding: 0 16px;
    }
    
    /* Contact grid with more padding adjustment */
    .contact-grid {
        gap: 20px !important;
    }
    
    /* Contact cards smaller padding on very small screens */
    .contact-grid > div > div {
        padding: 20px !important;
    }
    
    /* Contact info items stack better */
    .contact-grid div[style*="display: flex"][style*="gap: 16px"] {
        flex-direction: row !important;
        align-items: flex-start !important;
        text-align: left !important;
    }
    
    /* Icon boxes smaller */
    .contact-grid div[style*="width: 48px"][style*="height: 48px"] {
        width: 40px !important;
        height: 40px !important;
        font-size: 18px !important;
    }
    
    /* Headings smaller */
    .contact-grid h2 {
        font-size: 20px !important;
    }
    
    .contact-grid h3 {
        font-size: 15px !important;
    }
    
    /* FAQ section */
    details summary {
        font-size: 15px !important;
    }
    
    details p {
        font-size: 13px !important;
    }
}
</style>

