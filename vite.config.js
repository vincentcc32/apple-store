import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    // server: {
    //     host: '0.0.0.0', // hoặc IP máy tính như '192.168.1.5'
    //     port: 5173,
    //     strictPort: true,
    //     hmr: {
    //         host: 'http://172.16.132.238/', // Địa chỉ IP LAN của máy tính bạn
    //     },
    // },
    server: {       // The port you're running Vite on
        allowedHosts: [
            '28d7539a16e6.ngrok-free.app'  // Add the ngrok URL here
        ],
    }

});
