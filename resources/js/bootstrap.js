/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' || window.location.hostname.endsWith('.test');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || 'capavenir_key',
    wsHost: import.meta.env.VITE_PUSHER_HOST || window.location.hostname || '127.0.0.1',
    wsPort: isLocal ? (import.meta.env.VITE_PUSHER_PORT || 3000) : (window.location.protocol === 'https:' ? 443 : 80),
    wssPort: isLocal ? (import.meta.env.VITE_PUSHER_PORT || 3000) : (window.location.protocol === 'https:' ? 443 : 80),
    forceTLS: isLocal ? false : (window.location.protocol === 'https:'),
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
