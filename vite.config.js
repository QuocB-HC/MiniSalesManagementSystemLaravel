import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                'resources/css/app.css',

                // JS
                'resources/js/app.js',
                'resources/js/notifications/toast-handler.js',
                'resources/js/notifications/modal-handler.js',

            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
