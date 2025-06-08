<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Live Auction Platform') }}</title>

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
                        <a class="nav-link text-dark" href="#features">Features</a>
                    </li>
                    
                    @auth 
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('dashboard') }}">Auction</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning text-dark fw-bold px-3 py-2 ms-2" href="{{ route('register') }}">Join Now</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-dark text-light py-5">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold mb-4">Discover the Art of Bidding</h1>
            <p class="lead text-light mb-4 mx-auto" style="max-width: 600px;">
                Join our exclusive live auction platform to bid on unique treasures, engage in real-time, and experience the thrill of winning.
            </p>
            <div class="mt-4">
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg text-dark fw-bold mx-2 mb-2">Get Started</a>
                <a href="#features" class="btn btn-outline-light btn-lg mx-2 mb-2">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center display-6 fw-bold mb-5 text-dark">Why Choose Our Auction Platform?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-hammer text-warning display-4 mb-3"></i>
                            <h4 class="card-title fw-bold text-dark">Real-Time Bidding</h4>
                            <p class="card-text text-muted">
                                Place bids instantly and compete live with bidders worldwide, with seamless updates.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-chat-dots text-warning display-4 mb-3"></i>
                            <h4 class="card-title fw-bold text-dark">Live Chat</h4>
                            <p class="card-text text-muted">
                                Engage with other bidders and sellers through our integrated chat system.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="bi bi-bell text-warning display-4 mb-3"></i>
                            <h4 class="card-title fw-bold text-dark">Instant Notifications</h4>
                            <p class="card-text text-muted">
                                Stay informed with real-time alerts for outbids, auction updates, and more.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-light text-dark py-5">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-4">Ready to Start Bidding?</h2>
            <p class="lead text-muted mb-4">Join our platform today and explore a world of exclusive auctions.</p>
            <a href="{{ route('register') }}" class="btn btn-warning btn-lg text-dark fw-bold">Join Now</a>
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