import './bootstrap';
import './admin-crud';
import $ from 'jquery';
window.$ = window.jQuery = $;

import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

import Swal from 'sweetalert2';
window.Swal = Swal;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

// window.Echo.channel('admin-channel')
// .listen('.payment.received', e => console.log('DAPET:', e))
// .listen('payment.received', e => console.log('NO DOT:', e))
// .listen('PaymentReceived', e => console.log('CLASS:', e))


// window.Echo.connector.pusher.connection.bind_global((eventName, data) => {
//     console.log('GLOBAL EVENT:', eventName, data);
// });
