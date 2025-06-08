<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Live Auction Platform') }} - Login</title>

    <!-- Bootstrap CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="{{ url('/') }}">
                {{ config('app.name', 'Live Auction') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark active" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning text-dark fw-bold px-3 py-2 ms-2" href="{{ route('register') }}">Join Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="display-6 fw-bold text-dark text-center mb-4">Login</h2>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success mb-3">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <!-- Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label text-muted">Email Address</label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label text-muted">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                                </div>

                                <!-- Remember Me -->
                                <div class="mb-3 form-check">
                                    <input id="remember" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember" class="form-check-label text-muted">Remember Me</label>
                                </div>

                                <!-- Submit -->
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-warning text-dark fw-bold">Login</button>
                                </div>

                                <!-- Forgot Password -->
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="text-warning text-decoration-none" href="{{ route('password.request') }}">Forgot your password?</a>
                                    </div>
                                @endif
                            </form>

                            <!-- Register Link -->
                            <div class="text-center mt-3">
                                <p class="text-muted mb-0">Don’t have an account? <a href="{{ route('register') }}" class="text-warning text-decoration-none">Register</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p class="mb-2">© {{ date('Y') }} {{ config('app.name', 'Live Auction') }}. All rights reserved.</p>
            <p>
                <a href="#" class="text-warning mx-2 text-decoration-none">About</a> |
                <a href="#" class="text-warning mx-2 text-decoration-none">Contact</a> |
                <a href="#" class="text-warning mx-2 text-decoration-none">Privacy Policy</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS (via Vite) -->
    @vite('resources/js/app.js')
</body>
</html>