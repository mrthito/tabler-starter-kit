import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/admin.scss',
                'resources/js/admin.js',
                'resources/js/app.js',
                'resources/js/admin-theme.js',
                'resources/scss/flags.scss',
                'resources/scss/icons.scss',
                'resources/scss/marketing.scss',
                'resources/scss/payments.scss',
                'resources/scss/admin-props.scss',
                'resources/scss/socials.scss',
                'resources/scss/themes.scss',
                'resources/scss/vendors.scss',
            ],
            refresh: true,
        }),
    ],
});
