<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Barlow+Condensed:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/default.css') }}">
</head>

<body class="antialiased">

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <a href="#" class="nav-brand">SAMMER'S STORE</a>
        <div class="nav-links" id="navLinks">
            <button class="mobile-toggle close-menu-btn" id="closeMenu">
                <i class="bi bi-x-lg"></i>
            </button>
            <a href="{{ route('customers.login.page') }}" class="btn-login btn-customer">
                <i class="bi bi-person-fill"></i> Customer Login
            </a>
            <a href="#" class="btn-login btn-cashier">
                <i class="bi bi-calculator-fill"></i> Cashier Login
            </a>
            <a href="{{ route('admin.login.page') }}" class="btn-login btn-admin">
                <i class="bi bi-shield-lock-fill"></i> Admin Login
            </a>
        </div>

        <div class="nav-actions">
            <button class="theme-toggle" id="themeToggle">
                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
            </button>
            <button class="mobile-toggle" id="mobileMenuBtn">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    <!-- 1. Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Quality Goods, <br>Right Next Door.</h1>
            <p class="hero-desc">Experience the convenience of shopping at Sammer's Store. From daily essentials to
                wholesale deals, we bring the best products to your neighborhood.</p>
            <div class="hero-btns">
                <a href="{{ route('customers.login.page') }}" class="btn-main btn-hero-primary">Shop Now</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-blob"></div>
            <div class="hero-img-card">
                <div
                    style="width: 100%; height: 350px; background: #eee; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 5rem;">
                    🛒</div>
                <div style="margin-top: 1.5rem;">
                    <div
                        style="height: 10px; width: 40%; background: var(--primary); border-radius: 5px; margin-bottom: 10px;">
                    </div>
                    <div style="height: 10px; width: 80%; background: var(--border-light); border-radius: 5px;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Features Section -->
    <section class="features">
        <div class="section-header">
            <span class="section-tag">Why Choose Us</span>
            <h2 class="section-title">The Best Shopping Experience</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-lightning-fill"></i></div>
                <h3 class="feature-name">Fast & Friendly</h3>
                <p class="feature-desc">Quick service with a smile. We value your time and ensure a hassle-free shopping
                    experience.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-tags-fill"></i></div>
                <h3 class="feature-name">Wholesale Prices</h3>
                <p class="feature-desc">Save more with our wholesale pricing on bulk purchases. The more you buy, the
                    less you pay.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <h3 class="feature-name">Conveniently Located</h3>
                <p class="feature-desc">Always around the corner. Your trusted neighborhood partner for all your daily
                    needs.</p>
            </div>
        </div>
    </section>

    <!-- 3. Category Showcase -->
    <section class="categories">
        <div class="section-header">
            <span class="section-tag">Our Products</span>
            <h2 class="section-title">Everything You Need</h2>
        </div>
        <div class="categories-grid">
            <div class="category-item">
                <div
                    style="width: 100%; height: 100%; background: #475569; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                    🥫</div>
                <div class="category-overlay">
                    <div class="category-name">Grocery Essentials</div>
                </div>
            </div>
            <div class="category-item">
                <div
                    style="width: 100%; height: 100%; background: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                    🥤</div>
                <div class="category-overlay">
                    <div class="category-name">Cold Drinks</div>
                </div>
            </div>
            <div class="category-item">
                <div
                    style="width: 100%; height: 100%; background: var(--secondary); display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                    🧼</div>
                <div class="category-overlay">
                    <div class="category-name">Household</div>
                </div>
            </div>
            <div class="category-item">
                <div
                    style="width: 100%; height: 100%; background: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                    🍪</div>
                <div class="category-overlay">
                    <div class="category-name">Snacks & More</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Promotions Section -->
    <section class="promos">
        <div style="width: 45%; height: 400px; background: #e2e8f0; border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 8rem;"
            class="promo-img">🎁</div>
        <div class="promo-content">
            <span class="promo-badge">Special Offer</span>
            <h2 class="promo-title">Wholesale Wednesday Deals!</h2>
            <p class="hero-desc">Get exclusive discounts every Wednesday when you buy in bulk. Join our loyalty program
                to unlock more rewards.</p>
            <ul class="promo-list">
                <li><i class="bi bi-check-circle-fill"></i> Up to 20% off on bulk items</li>
                <li><i class="bi bi-check-circle-fill"></i> Free delivery for orders above ₱500</li>
                <li><i class="bi bi-check-circle-fill"></i> Points system for every purchase</li>
            </ul>
            <a href="{{ route('customers.login.page') }}" class="btn-main btn-hero-primary">Claim Rewards</a>
        </div>
    </section>

    <!-- 5. Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="#" class="nav-brand">SAMMER'S STORE</a>
                <p class="footer-desc">Your neighborhood choice for fresh goods, daily essentials, and wholesale
                    savings. Serving you with excellence since 2026.</p>
            </div>
            <div>
                <h4 class="footer-links-title">Quick Links</h4>
                <ul class="footer-links-list">
                    <li><a href="{{ route('customers.login.page') }}">Customer Portal</a></li>
                    <li><a href="{{ route('admin.login.page') }}">Admin Dashboard</a></li>
                    <li><a href="#">Cashier POS</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-links-title">Contact Us</h4>
                <ul class="footer-links-list">
                    <li><i class="bi bi-geo-alt me-2"></i> 123 Street Name, Lipa City</li>
                    <li><i class="bi bi-telephone me-2"></i> +63 912 345 6789</li>
                    <li><i class="bi bi-envelope me-2"></i> support@sammersstore.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Sammer's Store. All rights reserved.</p>
            <div style="display: flex; gap: 1.5rem;">
                <a href="#" style="color: #64748b;"><i class="bi bi-facebook"></i></a>
                <a href="#" style="color: #64748b;"><i class="bi bi-instagram"></i></a>
                <a href="#" style="color: #64748b;"><i class="bi bi-twitter-x"></i></a>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const THEME_KEY = 'sammers-theme';

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                themeIcon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            } else {
                document.body.classList.remove('dark-mode');
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
            }
        }

        const storedTheme = localStorage.getItem(THEME_KEY) || 'light';
        applyTheme(storedTheme);

        themeToggle.addEventListener('click', () => {
            const isDark = document.body.classList.contains('dark-mode');
            const newTheme = isDark ? 'light' : 'dark';
            localStorage.setItem(THEME_KEY, newTheme);
            applyTheme(newTheme);
        });

        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMenu = document.getElementById('closeMenu');
        const navLinks = document.getElementById('navLinks');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                navLinks.classList.add('active');
            });
        }

        if (closeMenu) {
            closeMenu.addEventListener('click', () => {
                navLinks.classList.remove('active');
            });
        }

        // Close menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
            });
        });
    </script>
</body>

</html>
