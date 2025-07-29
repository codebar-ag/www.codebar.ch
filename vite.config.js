import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: [`resources/views/**/*`],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
    build: {
        // Optimize build performance
        target: 'esnext',
        // Use default minification instead of terser
        minify: 'esbuild',
        rollupOptions: {
            output: {
                manualChunks: {
                    alpine: ['@alpinejs/csp'],
                },
            },
        },
        // Enable source maps for debugging
        sourcemap: false,
        // Optimize chunk size
        chunkSizeWarningLimit: 1000,
    },
    // Optimize development server
    optimizeDeps: {
        include: ['@alpinejs/csp'],
    },
});
