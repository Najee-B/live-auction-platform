# Live Auction Platform

A real-time auction platform built with **Laravel 12.17.0**, **Pusher Channels**, **Bootstrap 5**, and **Laravel Breeze**. Features include live chat, outbid notifications, and real-time countdowns, delivering an engaging bidding experience. The UI is designed with a classic and elegant aesthetic, inspired by luxury auction houses.

> **Note**: Live streaming was planned but not implemented due to time constraints. Future enhancements could integrate services like Vimeo or WebRTC for streaming.

---

## Features

* **Real-Time Bidding**: Place bids instantly with seamless updates via Pusher.
* **Live Chat**: Engage with bidders and sellers through integrated chat.
* **Outbid Notifications**: Receive instant alerts when outbid.
* **Real-Time Countdowns**: Dynamic timers for auction deadlines, with automatic extensions for late bids (< 2 minutes).
* **Elegant UI**: Bootstrap 5-based design with a navy/gold palette.
* **Authentication**: Secure login/register with Laravel Breeze.
* **Responsive Design**: Optimized for mobile, tablet, and desktop.

---

## Tech Stack

* **Backend**: Laravel 12.17.0, PHP 8.2
* **Frontend**: Bootstrap 5, Laravel Blade, JavaScript (Laravel Echo)
* **Real-Time**: Pusher Channels for broadcasting events
* **Database**: MySQL (configurable in `.env`)
* **Authentication**: Laravel Breeze
* **Queue**: Laravel Queue for asynchronous event handling

---

## Prerequisites

* PHP >= 8.2
* Composer
* Node.js >= 18
* MySQL
* Pusher account ([pusher.com](https://pusher.com))
* Git

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Najee-B/live-auction-platform.git
cd live-auction-platform
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 3. Configure Environment

#### Copy .env.example to .env

```bash
cp .env.example .env
```

#### Update `.env` with the following:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auction_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

QUEUE_CONNECTION=database

APP_TIMEZONE=Asia/Kolkata
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Link Storage

```bash
php artisan storage:link
```

### 7. Start Server

```bash
php artisan serve
```

### 8. Start Queue Worker

```bash
php artisan queue:work
```

### 9. Access the Application

Visit [http://localhost:8000](http://localhost:8000)

#### Role Management

By default, users are assigned the **bidder** role upon registration.

#### Create Admin User with Tinker

```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => Hash::make('password123'),
    'role' => 'admin',
    'email_verified_at' => now(),
]);

// Verify role:
User::where('email', 'admin@example.com')->first()->role;
// Output: "admin"
```

Exit Tinker:

```bash
exit
```

---


Register or login using sample credentials:

* **Bidder**: [bidder@example.com](mailto:bidder@example.com)


## Usage

### Admin

* Create auctions at: `/products/create`

### Bidders

* Browse auctions: `/products`
* Place bids
* Participate in live chat
* Receive real-time notifications

### Real-Time Capabilities

* Instant bid updates
* Real-time chat
* Automatic countdown extensions (if bid placed within last 2 minutes)

---

## Troubleshooting

### Real-Time Issues

* Verify your Pusher credentials
* Ensure `php artisan queue:work` is running
* Check the Pusher Debug Console for logs

### Database Errors

* Ensure MySQL is running and credentials in `.env` are correct
* Run migrations with `php artisan migrate`

---


