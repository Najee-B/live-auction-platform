@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Auction Products</h1>
             

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('products.create') }}" class="btn btn-warning text-dark mb-3">Create Product</a>
            @endif
                <div class="row d-flex flex-wrap">
                 @foreach ($products as $product)
                 <div class="col-md-4">
                    <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                    <div class="card  m-3">
                    <span class="timer" data-end-time="{{ $product->end_time }}"></span>
                    <img src="{{asset('/storage/'.$product->image)}}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text">Initial Price : {{ $product->starting_price }}</p>
                        @if ($product->current_price > $product->starting_price)
                        <p class="card-text">Current Price : {{ $product->current_price }}</p>
                        @endif
                        <p class="card-text">Status : {{ $product->status }}</p>

                        <div class="d-flex mt-4">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info mx-2">View</a>
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning mx-2">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger mx-2">Delete</button>
                                    </form>
                                @endif
                        </div>
                        
                    </div>
                    <div class="card-footer text-center fw-bolder bg-dark text-light">
                        <small class="count-down "></small>
                    </div>
                </div>
                </a>
                </div>
                @endforeach
        </div>

        </div>
    </div>


   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const timers = document.querySelectorAll('.timer');

        timers.forEach(timer => {
            const endTime = new Date(timer.dataset.endTime);
            const countdownDisplay = timer.closest('.card').querySelector('.count-down');

            const interval = setInterval(() => {
                const now = new Date();
                const diff = endTime - now;

                if (diff <= 0) {
                    clearInterval(interval);
                    countdownDisplay.textContent = 'Auction Ended';
                    return;
                }

                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / (3600 * 24));
                const hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;

                let countdownText = '';
                if (days > 0) countdownText += `${days}d `;
                if (hours > 0 || days > 0) countdownText += `${hours}h `;
                countdownText += `${minutes}m ${seconds}s`;

                countdownDisplay.textContent = countdownText;
            }, 1000);
        });
    });

        // Real-time Updates
        // Wait for window.Echo to be defined
        function waitForEcho(callback) {
            if (window.Echo) {
                console.log("window.Echo is now defined:", window.Echo);
                callback();
            } else {
                console.log("Waiting for window.Echo...");
                setTimeout(() => waitForEcho(callback), 100);
            }
        }

        waitForEcho(() => {
        console.log("Setting up Echo listeners...");
        @foreach ($products as $product)
            const channel{{ $product->id }} = window.Echo.channel(`product.{{ $product->id }}`);
            channel{{ $product->id }}.subscribed(() => {
                console.log('Subscribed to product.{{ $product->id }}');
            }).error((error) => {
                console.error('Failed to subscribe to product.{{ $product->id }}:', error);
            });
        @endforeach

        @auth
            const privateChannel = window.Echo.private(`App.Models.User.{{ auth()->id() }}`);
            privateChannel.subscribed(() => {
                console.log('Subscribed to private-App.Models.User.{{ auth()->id() }}');
            }).error((error) => {
                console.error('Failed to subscribe to private-App.Models.User.{{ auth()->id() }}:', error);
            });

            privateChannel.notification((notification) => {
                const container = document.getElementById('notification-container');
                if (!container) return;

                const alert = document.createElement('div');
                alert.className = 'alert alert-warning alert-dismissible fade show';
                alert.innerHTML = `
                    ${notification.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                container.prepend(alert);
            });
        @endauth
    });
</script>
@endsection