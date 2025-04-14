import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/client.css',
                'resources/css/admin.css',
                'resources/js/client.js',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
});
