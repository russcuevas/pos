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
                <h1>Join Our Community.</h1>
                <p>Create an account to track your orders, manage your preferences, and receive exclusive offers.</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="login-form-wrapper">
            <div class="login-card">
                <img src="{{ asset('image/logo.png') }}" alt="POS Logo" class="logo-mobile">

                <div class="form-header">
                    <h2>Create Account</h2>
                    <p>Get started by creating your customer account below.</p>
                </div>

                <form action="{{ route('customers.register.request') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 mb-2">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                value="{{ old('fullname') }}" placeholder="Full name" required autofocus>
                        </div>


                        <div class="col-md-6 mb-2">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                value="{{ old('phone_number') }}" placeholder="Phone">
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email') }}" placeholder="Email" required>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address') }}" placeholder="Address">
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control password-field" id="password"
                                    name="password" placeholder="••••••••" required>
                                <span class="input-group-text" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand w-100">Create Account</button>

                    <div class="text-center mt-4">
                        <p class="small text-muted">Already have an account? <a
                                href="{{ route('customers.login.page') }}" class="text-brand fw-600">Sign in</a></p>
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
