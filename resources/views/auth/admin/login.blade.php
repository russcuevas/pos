<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        @media (min-width: 576px) {
            .login-card {
                padding: 2.5rem;
            }
        }

        .brand-red {
            color: #d93025;
        }

        .btn-brand {
            background-color: #d93025;
            color: white;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-brand:hover {
            background-color: #b71c1c;
            color: white;
            transform: translateY(-1px);
        }

        .form-control {
            border-radius: 0.5rem;
        }

        .form-control:focus {
            border-color: #d93025;
            box-shadow: 0 0 0 0.25rem rgba(217, 48, 37, 0.1);
        }

        .forgot-link {
            text-decoration: none;
            color: #d93025;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background: transparent;
            border-left: none;
            cursor: pointer;
            color: #6c757d;
        }

        .password-field {
            border-right: none;
        }

        .logo-wrapper img {
            max-height: 80px;
            width: auto;
        }

        @media (min-width: 576px) {
            .logo-wrapper img {
                max-height: 100px;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <div class="logo-wrapper d-flex align-items-center justify-content-center mb-3">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
            </div>
            <h2 class="fw-bold h4 mb-1">Admin Login</h2>
            <p class="text-muted small">Enter your credentials to access the POS</p>
        </div>

        <form action="#" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label fw-medium small">
                    Email address<span class="brand-red"> *</span>
                </label>
                <input type="email" class="form-control form-control-lg" id="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-medium small">
                    Password<span class="brand-red"> *</span>
                </label>
                <div class="input-group">
                    <input type="password" class="form-control form-control-lg password-field" id="password" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
                <div class="text-end mt-2">
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>
            </div>

            <button type="submit" class="btn btn-brand w-100 mt-2">Sign in</button>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>
