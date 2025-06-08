import Pusher from 'pusher-js';

console.log('Testing Pusher connection...');
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

pusher.connection.bind('connected', () => {
    console.log('Connected to Pusher!');
});

pusher.connection.bind('error', (err) => {
    console.error('Pusher connection error:', err);
});