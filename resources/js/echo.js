// resources/js/echo.js
console.log('Echo.js is loaded!');

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// console.log('Pusher:', typeof Pusher);
// console.log('Echo:', typeof Echo);
// console.log('VITE_PUSHER_APP_KEY:', import.meta.env.VITE_PUSHER_APP_KEY);
// console.log('VITE_PUSHER_APP_CLUSTER:', import.meta.env.VITE_PUSHER_APP_CLUSTER);

if (!import.meta.env.VITE_PUSHER_APP_KEY || !import.meta.env.VITE_PUSHER_APP_CLUSTER) {
    console.error('Pusher credentials missing in Vite environment! Echo not initialized.');
} else {
    window.Pusher = Pusher;

    try {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
        });
        console.log('Echo initialized successfully:', window.Echo);
    } catch (error) {
        console.error('Failed to initialize Echo:', error);
    }
}