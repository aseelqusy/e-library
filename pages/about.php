<?php
session_start();
?>
<?php include('../includes/header.php'); ?>

<link rel="stylesheet" href="/library_project/assets/css/books.css">

<?php include('../includes/navbar.php'); ?>

<!-- ABOUT PAGE -->
<div class="page-container">

    <!-- PAGE HEADER -->
    <div class="page-header" style="text-align: center; padding: 60px 0 40px;">
        <h1 class="page-title" style="font-size: 48px; margin-bottom: 16px;">
            About E-Library
        </h1>
        <p class="page-subtitle" style="font-size: 18px; max-width: 700px; margin: 0 auto;">
            Your gateway to thousands of books, journals, and digital resources. Explore, learn, and grow anytime, anywhere.
        </p>
    </div>

    <!-- MISSION SECTION -->
    <div style="max-width: 900px; margin: 0 auto 60px;">
        <div style="background: var(--white); border: 1px solid var(--border); border-radius: 20px; padding: 40px; margin-bottom: 32px;">
            <h2 style="font-family: 'Sora', sans-serif; font-size: 32px; font-weight: 700; color: var(--text); margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 36px;">🎯</span>
                Our Mission
            </h2>
            <p style="font-size: 16px; color: var(--muted); line-height: 1.8; margin-bottom: 16px;">
                At E-Library, we believe that knowledge should be accessible to everyone, everywhere. Our mission is to provide a modern, user-friendly platform that connects readers with a vast collection of digital books and resources.
            </p>
            <p style="font-size: 16px; color: var(--muted); line-height: 1.8;">
                Whether you're a student, professional, or casual reader, E-Library offers an intuitive experience designed to help you discover, borrow, and enjoy books that inspire and educate.
            </p>
        </div>

        <!-- FEATURES GRID -->
        <h2 style="font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 700; text-align: center; margin-bottom: 32px;">
            Why Choose E-Library?
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 60px;">

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px; text-align: center; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <div style="width: 64px; height: 64px; margin: 0 auto 20px; background: linear-gradient(135deg, var(--purple), var(--pink)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                    📚
                </div>
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">Vast Collection</h3>
                <p style="font-size: 14px; color: var(--muted); line-height: 1.6;">
                    Access thousands of books across all genres and categories
                </p>
            </div>

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px; text-align: center;">
                <div style="width: 64px; height: 64px; margin: 0 auto 20px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                    🔍
                </div>
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">Easy Search</h3>
                <p style="font-size: 14px; color: var(--muted); line-height: 1.6;">
                    Find your next favorite book with our powerful search and filters
                </p>
            </div>

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px; text-align: center;">
                <div style="width: 64px; height: 64px; margin: 0 auto 20px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                    📱
                </div>
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">Mobile Friendly</h3>
                <p style="font-size: 14px; color: var(--muted); line-height: 1.6;">
                    Read on any device - desktop, tablet, or smartphone
                </p>
            </div>

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px; text-align: center;">
                <div style="width: 64px; height: 64px; margin: 0 auto 20px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                    ⚡
                </div>
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">Quick Borrowing</h3>
                <p style="font-size: 14px; color: var(--muted); line-height: 1.6;">
                    Borrow books instantly with our streamlined process
                </p>
            </div>

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px; text-align: center;">
                <div style="width: 64px; height: 64px; margin: 0 auto 20px; background: linear-gradient(135deg, #ec4899, #db2777); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                    ❤️
                </div>
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">Save Favorites</h3>
                <p style="font-size: 14px; color: var(--muted); line-height: 1.6;">
                    Create your personal collection of favorite books
                </p>
            </div>

            <div style="background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px; text-align: center;">
                <div style="width: 64px; height: 64px; margin: 0 auto 20px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 32px;">
                    🌙
                </div>
                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">Dark Mode</h3>
                <p style="font-size: 14px; color: var(--muted); line-height: 1.6;">
                    Easy on the eyes with our beautiful dark theme option
                </p>
            </div>

        </div>

        <!-- STATISTICS -->
        <div style="background: linear-gradient(145deg, #4c1d95 0%, #7c3aed 40%, #9333ea 65%, #ec4899 100%); border-radius: 20px; padding: 60px 40px; text-align: center; color: white; margin-bottom: 60px;">
            <h2 style="font-size: 32px; font-weight: 800; margin-bottom: 40px;">E-Library by Numbers</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px;">
                <div>
                    <div style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">10K+</div>
                    <div style="font-size: 16px; opacity: 0.9;">Books Available</div>
                </div>
                <div>
                    <div style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">4.2K</div>
                    <div style="font-size: 16px; opacity: 0.9;">Active Readers</div>
                </div>
                <div>
                    <div style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">4.8</div>
                    <div style="font-size: 16px; opacity: 0.9;">Average Rating</div>
                </div>
                <div>
                    <div style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">24/7</div>
                    <div style="font-size: 16px; opacity: 0.9;">Access Anytime</div>
                </div>
            </div>
        </div>

        <!-- CALL TO ACTION -->
        <div style="text-align: center; padding: 40px 0;">
            <h2 style="font-size: 32px; font-weight: 700; margin-bottom: 16px;">Ready to Start Reading?</h2>
            <p style="font-size: 16px; color: var(--muted); margin-bottom: 32px;">
                Join thousands of readers exploring our digital library today
            </p>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="/library_project/books/brows.php" class="btn-primary" style="text-decoration: none;">
                    <i class="fas fa-book"></i> Browse Books
                </a>
                <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="/library_project/auth/register.php" class="btn-secondary" style="text-decoration: none;">
                    <i class="fas fa-user-plus"></i> Sign Up Free
                </a>
                <?php endif; ?>
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

/* RESPONSIVE STYLES */
@media (max-width: 768px) {
    .page-header {
        padding: 40px 20px 30px !important;
    }
    
    .page-title {
        font-size: 32px !important;
    }
    
    .page-subtitle {
        font-size: 16px !important;
    }
    
    /* Features grid responsive */
    .page-container > div > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        padding: 0 20px;
    }
    
    /* Mission card */
    .page-container > div > div[style*="padding: 40px"] {
        padding: 24px !important;
        margin: 0 20px 24px !important;
    }
    
    /* Statistics gradient section */
    .page-container > div > div[style*="background: linear-gradient"] {
        padding: 40px 24px !important;
        margin: 0 20px 40px !important;
    }
    
    /* CTA section */
    .page-container > div > div[style*="text-align: center"] h2 {
        font-size: 26px !important;
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
        font-size: 15px !important;
    }
    
    .page-container > div > div[style*="grid-template-columns"] {
        padding: 0 16px;
    }
    
    .page-container > div > div[style*="padding: 40px"],
    .page-container > div > div[style*="padding: 24px"] {
        padding: 20px !important;
        margin: 0 16px 20px !important;
    }
    
    .page-container h2 {
        font-size: 22px !important;
    }
}
</style>

