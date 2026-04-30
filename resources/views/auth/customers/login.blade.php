<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="{{ asset('assets/login.css') }}">
</head>

<body>

    <div class="login-container">
        <!-- Left Side: Visual/Brand -->
        <div class="login-visual">
            <div class="visual-decor-1"></div>
            <div class="visual-decor-2"></div>
            <div class="visual-content">
                <img src="{{ asset('image/logo.png') }}" alt="POS Logo"
                    style="max-height: 80px; margin-bottom: 2rem; filter: brightness(0) invert(1);">
                <h1>Welcome to Our Store.</h1>
                <p>Access your personalized dashboard to track your orders, manage your account, and enjoy a seamless
                    shopping experience.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="login-form-wrapper">
            <div class="login-card">
                <img src="{{ asset('image/logo.png') }}" alt="POS Logo" class="logo-mobile">

                <div class="form-header">
                    <h2>Customer Login</h2>
                    <p>Welcome back! Please enter your details to access your account.</p>
                </div>

                <form action="{{ route('customers.login.request') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label mb-0">Password</label>
                            <a href="#" class="forgot-link">Forgot password?</a>
                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control password-field" id="password" name="password"
                                placeholder="••••••••" required>
                            <span class="input-group-text" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label small" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand">Sign in</button>

                    <div class="text-center mt-4">
                        <p class="small text-muted">Don't have an account? <a
                                href="{{ route('customers.register.page') }}" class="text-brand fw-600">Sign
                                up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        // Password Visibility Toggle
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        }

        // Notyf Initialization
        const notyf = new Notyf({
            position: {
                x: 'right',
                y: 'top'
            },
            duration: 4000,
            types: [{
                    type: 'error',
                    background: '#ef4444',
                    icon: {
                        className: 'bi bi-x-circle fs-5 text-white',
                        tagName: 'i'
                    }
                },
                {
                    type: 'success',
                    background: '#10b981',
                    icon: {
                        className: 'bi bi-check-circle fs-5 text-white',
                        tagName: 'i'
                    }
                }
            ]
        });

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                notyf.success("{!! addslashes(session('success')) !!}");
            @endif

            @if (session('error'))
                notyf.error("{!! addslashes(session('error')) !!}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    notyf.error("{!! addslashes($error) !!}");
                @endforeach
            @endif
        });
    </script>
</body>

</html>
