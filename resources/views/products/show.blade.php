@extends('layouts.app')



@section('content')

    <div class="row">
        <div class="col-md-8 border-end border-secondary ">
            <div class="d-flex row">
                <div class="col-md-7">
                    <img src="{{asset('/storage/'.$product->image)}}" class="img-fluid" alt="...">
                </div>
                <div class="col-md-5">
                    <h3>{{ $product->name }}</h3>
            
                    <p>{{ $product->description }}</p>
                    <p><strong>Current Bid:</strong> <span id="current-bid">${{ number_format($product->current_price ?? $product->starting_price, 2) }}</span></p>
                    <p><strong>Time Remaining:</strong> <span id="timer" data-end-time="{{ $product->end_time }}"></span></p>
                    <form id="bid-form" action="{{ route('bids.store', $product) }}" method="POST">
                            @csrf
                    @if ($product->status === 'active' && !$product->end_time->isPast())
                        
                            <div class="mb-3">
                                <label for="amount" class="form-label">Your Bid</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="{{ ($product->current_price ?? $product->starting_price) + 0.01 }}" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Place Bid</button>
                    
                    @else
                        <div class="alert alert-warning">This auction is closed.</div>
                    @endif
                    </form>
                </div>
            </div>
            
            <div class="mt-3">
                <h3>Live Stream</h3>
                <div class="alert alert-info">Live stream will be available soon.</div>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Chat</h3>
            <div id="messages" class="border p-3 mb-3" style="height: 300px; overflow-y: scroll;">
               @foreach ($product->chats as $chat)
                    <div class="d-flex mb-2 {{ $chat->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="chat-message {{ $chat->user_id === auth()->id() ? 'chat-sender' : 'chat-receiver' }}">
                            <strong>{{ $chat->user->name }}:</strong> {{ $chat->message }}
                        </div>
                    </div>
                @endforeach
            </div>
            <form id="chat-form">
                <div class="input-group">
                    <input type="text" id="message-input" class="form-control" placeholder="Type a message...">
                    <button type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Countdown Timer
            let endTime = new Date(document.getElementById('timer').dataset.endTime);
        
            const countdownDisplay = document.getElementById('timer');

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

        // Bid Form Submission
        document.getElementById('bid-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Chat Form Submission
        document.getElementById('chat-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const message = document.getElementById('message-input').value;
            fetch(`/products/{{ $product->id }}/chat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('message-input').value = '';
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Debug Echo
        //console.log("window.Echo value:", window.Echo);

        // Real-time Updates
        // Wait for window.Echo to be defined
        function waitForEcho(callback) {
            if (window.Echo) {
                //console.log("window.Echo is now defined:", window.Echo);
                callback();
            } else {
                console.log("Waiting for window.Echo...");
                setTimeout(() => waitForEcho(callback), 100);
            }
        }

        waitForEcho(() => {
        //console.log("Setting up Echo listeners...");
        const channel = window.Echo.channel(`product.{{ $product->id }}`);
        channel.subscribed(() => {
            console.log(`Subscribed to product.{{ $product->id }}`);
        }).error((error) => {
            console.error(`Failed to subscribe to product.{{ $product->id }}:`, error);
        });

        channel.listen('.BidPlaced', (e) => {
            console.log('BidPlaced event received:', e);

            const currentBidElement = document.getElementById('current-bid');
            if (currentBidElement) {
                currentBidElement.textContent = `$${parseFloat(e.bid.amount).toFixed(2)}`;
            }

            const amountInput = document.getElementById('amount');
            if (amountInput) {
                amountInput.min = parseFloat(e.bid.amount) + 0.01;
            }

            if (e.product && e.product.end_time) {
                endTime = new Date(e.product.end_time);
            }
        }).listen('.ChatMessageSent', (e) => {
            const isSender = e.chat.user.name === @json(auth()->user()->name);
            
            const wrapper = document.createElement('div');
            wrapper.classList.add('d-flex', 'mb-2');
            wrapper.classList.add(isSender ? 'justify-content-end' : 'justify-content-start');

            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message');
            messageDiv.classList.add(isSender ? 'chat-sender' : 'chat-receiver');
            messageDiv.innerHTML = `<strong>${e.chat.user.name}:</strong> ${e.chat.message}`;

            wrapper.appendChild(messageDiv);

            const messagesContainer = document.getElementById('messages');
            messagesContainer.appendChild(wrapper);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

        const privateChannel = window.Echo.private(`App.Models.User.{{ auth()->id() }}`);
        privateChannel.subscribed(() => {
            console.log(`Subscribed to private-App.Models.User.{{ auth()->id() }}`);
        }).error((error) => {
            console.error(`Failed to subscribe to private-App.Models.User.{{ auth()->id() }}:`, error);
        });

        privateChannel.notification((notification) => {
            console.log('Notification received:', notification);
            privateChannel.notification((notification) => {
            console.log('Notification received:', notification);
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
        });
    });
    </script>
@endpush