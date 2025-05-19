import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        minify: "terser",
        cssMinify: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    alpine: ["alpinejs"],
                    vendor: ["perfect-scrollbar"],
                },
            },
        },
    },
    server: {
        hmr: {
            overlay: false,
        },
    },
});
