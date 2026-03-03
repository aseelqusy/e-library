<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">

        <a class="navbar-brand gradient-text" href="/library_project/index.php">
            📚 E‑Library
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Books</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Browse</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            </ul>

            <ul class="navbar-nav">

                <?php if(isset($_SESSION['user_id'])): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/library_project/dashboard/dashboard.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/library_project/auth/logout.php">
                            Logout
                        </a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/library_project/auth/login.php">
                            Log In
                        </a>
                    </li>

                    <li class="nav-item ms-2">
                        <a class="btn btn-gradient" href="/library_project/auth/register.php">
                            Sign Up
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>
    </div>
</nav>