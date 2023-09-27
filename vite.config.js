import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament.css",
                "resources/css/web/app.css",
            ],
            refresh: true,
        }),
    ],
});
