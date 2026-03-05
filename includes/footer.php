<footer>
    <div class="footer-grid">

        <div class="footer-brand">
            <a href="#" class="logo">
                <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
                <span class="logo-text">E-Library</span>
            </a>
            <p>
                Your gateway to thousands of books, journals, and digital resources.
                Explore, learn, and grow anytime, anywhere.
            </p>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Books</a></li>
                <li><a href="#">Browse</a></li>
                <li><a href="#">Categories</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contact Us</h4>
            <div class="contact-email">
                <i class="fa-regular fa-envelope"></i>
                info@elibrary.com
            </div>
            <div class="social-icons">
                <button class="social-btn"><i class="fa-brands fa-facebook-f"></i></button>
                <button class="social-btn"><i class="fa-brands fa-twitter"></i></button>
                <button class="social-btn"><i class="fa-brands fa-instagram"></i></button>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        © <?php echo date("Y"); ?> E-Library.
        Made with <span class="heart">❤</span> All rights reserved.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const toggle = document.getElementById('themeToggle');
    if(toggle){
        toggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            const icon = toggle.querySelector('i');
            icon.classList.toggle('fa-moon');
            icon.classList.toggle('fa-sun');
        });
    }
</script>

</body>
</html>