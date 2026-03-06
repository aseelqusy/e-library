<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library — Log In</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/login-animations.css">
    <link rel="stylesheet" href="../assets/css/dark-mode-auth.css">

    <!-- Initialize dark mode before page renders to prevent flash -->
    <script>
        (function() {
            const DARK_MODE_KEY = 'elibrary-dark-mode';
            const isDarkMode = localStorage.getItem(DARK_MODE_KEY) === 'true';
            if (isDarkMode) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>
<body>


<!-- ─── LEFT PANEL ─── -->
<div class="left-panel">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>

    <!-- Flying books background -->
    <div class="flying-books">
        <div class="flying-book">📕</div>
        <div class="flying-book">📗</div>
        <div class="flying-book">📘</div>
        <div class="flying-book">📙</div>
        <div class="flying-book">📔</div>
    </div>

    <!-- Sparkles -->
    <div class="sparkles">
        <div class="sparkle">✨</div>
        <div class="sparkle">✨</div>
        <div class="sparkle">✨</div>
        <div class="sparkle">✨</div>
        <div class="sparkle">✨</div>
    </div>

    <div class="left-content">

        <!-- Logo -->
        <div class="panel-logo">
            <div class="panel-logo-icon">📚</div>
            <span class="panel-logo-text">E-Library</span>
        </div>

        <!-- Reading Character Animation -->
        <div class="reading-character">
            <div class="character-body">
                <div class="character-head"></div>
                <div class="character-book"></div>
            </div>
        </div>

        <!-- Floating stats cards -->
        <div class="float-cards">
            <div class="float-card">
                <div class="fc-icon">📚</div>
                <div class="fc-text">
                    <div class="fc-num">10K+</div>
                    <div class="fc-label">Books</div>
                </div>
            </div>
            <div class="float-card">
                <div class="fc-icon">👥</div>
                <div class="fc-text">
                    <div class="fc-num">4.2K</div>
                    <div class="fc-label">Readers</div>
                </div>
            </div>
            <div class="float-card">
                <div class="fc-icon">⭐</div>
                <div class="fc-text">
                    <div class="fc-num">4.8</div>
                    <div class="fc-label">Rating</div>
                </div>
            </div>
        </div>

        <!-- Headline -->
        <div class="panel-headline">
            <h2>Welcome back to<br>your reading journey</h2>
            <p>Continue exploring our vast collection of digital books, journals, and resources. Your next adventure awaits.</p>
        </div>

        <!-- Testimonial -->
        <div class="testimonial">
            <div class="stars">★★★★★</div>
            <p>"Logging in to E-Library is like opening a door to endless possibilities. Every visit brings new discoveries and knowledge."</p>
            <div class="t-author">
                <div class="t-avatar">JD</div>
                <div>
                    <div class="t-name">John Davis</div>
                    <div class="t-role">Book Enthusiast · Member since 2024</div>
                </div>
            </div>
        </div>

        <!-- Pagination dots -->
        <div class="panel-dots">
            <div class="panel-dot"></div>
            <div class="panel-dot active"></div>
            <div class="panel-dot"></div>
        </div>

    </div>
</div>

<!-- ─── RIGHT PANEL ─── -->
<div class="right-panel">
    <div class="form-wrapper">

        <!-- Back link -->
        <div class="form-top">
            <a href="../index.php" class="back-link">← Back to home</a>
            <h1>Welcome <span>back</span></h1>
            <p>Log in to access your library and continue reading.</p>
        </div>

        <!-- Social login -->
        <div class="social-auth">
            <button class="social-btn"><span class="s-icon">🌐</span> Google</button>
            <button class="social-btn"><span class="s-icon">🍎</span> Apple</button>
        </div>

        <div class="or-divider"><span>or log in with email</span></div>

        <!-- Login Form -->
        <form id="loginForm" method="POST" action="">

            <div class="field">
                <label>Email or Username</label>
                <div class="input-wrap">
                    <span class="i-icon">👤</span>
                    <input type="text" id="login-email" name="email" placeholder="your@email.com or username" required>
                </div>
            </div>

            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <span class="i-icon">🔒</span>
                    <input type="password" id="login-password" name="password" placeholder="Enter your password" required>
                    <button class="toggle-pass" onclick="toggleLoginPassword(event)" type="button">👁️</button>
                </div>
            </div>

            <div class="terms-row" style="justify-content: space-between; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="custom-check" id="remember-check" onclick="this.classList.toggle('checked')"></div>
                    <div class="terms-text" style="margin: 0;">Remember me</div>
                </div>
                <a href="#" class="terms-text" style="color: var(--purple); font-weight: 600; text-decoration: none; margin: 0;">Forgot password?</a>
            </div>

            <button type="submit" class="submit-btn">
                <span>📚 Log In to Library</span>
            </button>

        </form>

        <!-- Sign up redirect -->
        <div class="login-link">
            Don't have an account? <a href="register.php">Sign Up</a>
        </div>

        <!-- Quick login info -->
        <div style="margin-top: 32px; padding: 16px; background: rgba(124, 58, 237, 0.05); border-radius: 12px; border: 1px solid rgba(124, 58, 237, 0.1);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <span style="font-size: 20px;">💡</span>
                <div style="font-size: 13px; font-weight: 600; color: var(--purple);">Quick Tip</div>
            </div>
            <p style="font-size: 13px; color: var(--muted); line-height: 1.6; margin: 0;">
                Use your email or username to log in. Enable "Remember me" to stay logged in on this device.
            </p>
        </div>

    </div>
</div>

<script src="../assets/js/login.js"></script>
<script src="../assets/js/dark-mode.js"></script>

</body>

</html>

