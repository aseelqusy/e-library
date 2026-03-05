

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library — Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">

    <script src="../assets/js/auth.js" defer></script>
</head>
<body>

<!-- ─── LEFT PANEL ─── -->
<div class="left-panel">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>

    <div class="left-content">

        <!-- Logo -->
        <div class="panel-logo">
            <div class="panel-logo-icon">📚</div>
            <span class="panel-logo-text">E-Library</span>
        </div>

        <!-- Animated Book Shelf -->
        <div class="book-stack">
            <div class="book-shelf">
                <div class="book b1">📗</div>
                <div class="book b2">📘</div>
                <div class="book b3">📕</div>
                <div class="book b4">📙</div>
                <div class="book b5">📓</div>
                <div class="book b6">📒</div>
                <div class="book b7">📔</div>
            </div>
            <div class="shelf-plank"></div>
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
            <h2>Your next great read<br>is waiting for you</h2>
            <p>Join thousands of readers exploring our vast collection of digital books, journals, and resources — completely free.</p>
        </div>

        <!-- Testimonial -->
        <div class="testimonial">
            <div class="stars">★★★★★</div>
            <p>"E-Library has completely changed how I learn. I've read over 80 books this year — all free and at my fingertips."</p>
            <div class="t-author">
                <div class="t-avatar">AM</div>
                <div>
                    <div class="t-name">Alice Morgan</div>
                    <div class="t-role">Avid Reader · Joined 2023</div>
                </div>
            </div>
        </div>

        <!-- Pagination dots -->
        <div class="panel-dots">
            <div class="panel-dot active"></div>
            <div class="panel-dot"></div>
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
            <h1>Create your <span>free account</span></h1>
            <p>Start reading in less than 2 minutes.</p>
        </div>

        <!-- Step progress -->
        <div class="steps">
            <div class="step">
                <div class="step-circle active" id="step-c1">1</div>
                <div class="step-line" id="step-l1"></div>
            </div>
            <div class="step">
                <div class="step-circle" id="step-c2">2</div>
                <div class="step-line" id="step-l2"></div>
            </div>
            <div class="step">
                <div class="step-circle" id="step-c3">3</div>
            </div>
        </div>

        <!-- Social sign up (step 1 only) -->
        <div id="social-section">
            <div class="social-auth">
                <button class="social-btn"><span class="s-icon">🌐</span> Google</button>
                <button class="social-btn"><span class="s-icon">🍎</span> Apple</button>
            </div>
            <div class="or-divider"><span>or register with email</span></div>
        </div>

        <!-- STEP 1: Personal Info -->
        <div class="step-fields active" id="fields-1">
            <div class="form-row">
                <div class="field">
                    <label>First Name</label>
                    <div class="input-wrap">
                        <span class="i-icon">👤</span>
                        <input type="text" id="fname" name="first_name" placeholder="Jane" oninput="validateField(this)">
                    </div>
                </div>
                <div class="field">
                    <label>Last Name</label>
                    <div class="input-wrap">
                        <span class="i-icon">👤</span>
                        <input type="text" id="lname" name="last_name" placeholder="Doe" oninput="validateField(this)">
                    </div>
                </div>
            </div>
            <div class="field">
                <label>Email Address</label>
                <div class="input-wrap">
                    <span class="i-icon">✉️</span>
                    <input type="email" id="email" name="email" placeholder="jane@example.com" oninput="validateEmail(this)">
                </div>
            </div>
            <div class="field">
                <label>Username</label>
                <div class="input-wrap">
                    <span class="i-icon">🔖</span>
                    <input type="text" id="username" name="username" placeholder="jane_reads" oninput="validateField(this)">
                </div>
            </div>
            <div class="nav-btns">
                <button class="btn-next" onclick="goStep(2)">Continue →</button>
            </div>
        </div>

        <!-- STEP 2: Password & Security -->
        <div class="step-fields" id="fields-2">
            <div class="field">
                <label>Password</label>
                <div class="input-wrap">
                    <span class="i-icon">🔒</span>
                    <input type="password" id="password" name="password" placeholder="Create a strong password" oninput="checkStrength(this.value)">
                    <button class="toggle-pass" onclick="togglePw('password', this)" type="button">👁️</button>
                </div>
                <div class="strength-bar">
                    <div class="strength-seg" id="s1"></div>
                    <div class="strength-seg" id="s2"></div>
                    <div class="strength-seg" id="s3"></div>
                    <div class="strength-seg" id="s4"></div>
                </div>
                <div class="strength-label" id="strength-label">Password strength</div>
            </div>
            <div class="field">
                <label>Confirm Password</label>
                <div class="input-wrap">
                    <span class="i-icon">🔒</span>
                    <input type="password" id="confirm-pw" placeholder="Repeat your password" oninput="validateConfirm(this)">
                    <button class="toggle-pass" onclick="togglePw('confirm-pw', this)" type="button">👁️</button>
                </div>
            </div>
            <div class="field">
                <label>Date of Birth</label>
                <div class="input-wrap">
                    <span class="i-icon">🎂</span>
                    <input type="date" id="dob" name="dob"  style="padding-left:42px;">
                </div>
            </div>
            <div class="nav-btns">
                <button class="btn-back" onclick="goStep(1)">← Back</button>
                <button class="btn-next" onclick="goStep(3)">Continue →</button>
            </div>
        </div>

        <!-- STEP 3: Preferences -->
        <div class="step-fields" id="fields-3">
            <div class="field">
                <label>Favorite Genre</label>
                <div class="input-wrap">
                    <span class="i-icon">🏷️</span>
                    <input type="text" name="favorite_genre" placeholder="Fiction, Science, Technology..." oninput="validateField(this)" style="padding-left:42px;">
                </div>
            </div>
            <div class="field">
                <label>How did you hear about us?</label>
                <div class="input-wrap" style="position:relative;">
                    <span class="i-icon" style="z-index:2;">📣</span>
                    <select name="heard_about" style="width:100%;padding:11px 14px 11px 42px;border:1.5px solid var(--border);border-radius:12px;background:var(--input-bg);font-family:inherit;font-size:14px;color:var(--text);outline:none;appearance:none;cursor:pointer;transition:border .2s;" onfocus="this.style.borderColor='var(--purple)';this.style.boxShadow='0 0 0 3px #ede9fe'" onblur="this.style.borderColor='var(--border)';this.style.boxShadow=''">
                        <option value="">Select an option</option>
                        <option>Search engine</option>
                        <option>Social media</option>
                        <option>Friend or colleague</option>
                        <option>Advertisement</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>

            <div class="terms-row">
                <div class="custom-check" id="terms-check" onclick="this.classList.toggle('checked')"></div>
                <div class="terms-text">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a> of E-Library.</div>
            </div>

            <div class="nav-btns" id="submit-btns">
                <button class="btn-back" onclick="goStep(2)">← Back</button>
                <button class="btn-next" onclick="submitForm()" style="display:flex;align-items:center;justify-content:center;gap:8px;">
                    <span>🚀</span> Create Account
                </button>
            </div>
        </div>



        <!-- SUCCESS -->
        <div class="success-screen" id="success-screen">
            <div class="success-icon">🎉</div>
            <h2>Welcome to <span>E-Library!</span></h2>
            <p>Your account has been created successfully. Start exploring thousands of books, journals, and resources — all free.</p>
            <button class="submit-btn" onclick="window.location.href='#'">
                <span>📚 Start Reading Now</span>
            </button>
            <div class="login-link" style="margin-top:16px;">
                <a href="#">Go to your Dashboard →</a>
            </div>
        </div>

        <!-- Already have account -->
        <div class="login-link" id="login-redirect">
            Already have an account? <a href="#">Log In</a>
        </div>

    </div>

</div>

</body>

</html>

