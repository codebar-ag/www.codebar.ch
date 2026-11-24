import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

const alpineGlobalsSkip = 'key === "styleMedia" || key === "sharedStorage"';

const skipDeprecatedGlobalsInAlpine = () => ({
    name: 'skip-deprecated-globals-in-alpine',
    enforce: 'pre',
    transform(code, id) {
        if (!id.includes('@alpinejs/csp') || !code.includes(alpineGlobalsSkip)) {
            return null;
        }

        return {
            code: code.replace(alpineGlobalsSkip, `${alpineGlobalsSkip} || key === "fence"`),
            map: null,
        };
    },
});

export default defineConfig({
    plugins: [
        skipDeprecatedGlobalsInAlpine(),
        laravel({
            input: ['resources/js/app.js', 'resources/js/zunscan.js', 'resources/js/kiosk.js', 'resources/js/infrastructure.js'],
            refresh: [`resources/views/**/*`],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },

});
