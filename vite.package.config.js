import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

// Standalone build config for the Composer package.
// Outputs dist/dev-frame.css — no font files, fonts are loaded via CDN in views.
export default defineConfig({
    plugins: [tailwindcss()],
    publicDir: false,
    build: {
        outDir: 'dist',
        emptyOutDir: false,
        rollupOptions: {
            input: 'resources/js/package-entry.js',
            output: {
                assetFileNames: 'dev-frame[extname]',
                entryFileNames: '_entry.js',
            },
        },
        cssCodeSplit: false,
        cssMinify: true,
    },
});
