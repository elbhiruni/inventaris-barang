import tailwindcss from "@tailwindcss/vite";
import laravel from "laravel-vite-plugin";
import { bunny } from "laravel-vite-plugin/fonts";
import { defineConfig, lazyPlugins } from "vite-plus";

export default defineConfig({
    fmt: {
        ignorePatterns: [],
        printWidth: 80,
    },
    lint: {
        jsPlugins: [
            { name: "vite-plus", specifier: "vite-plus/oxlint-plugin" },
        ],
        rules: { "vite-plus/prefer-vite-plus-imports": "error" },
        options: { typeAware: true, typeCheck: true },
    },
    plugins: lazyPlugins(() => [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/passkeys.js",
            ],
            refresh: true,
            fonts: [
                bunny("Instrument Sans", {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ]),
    server: {
        cors: true,
        watch: {
            ignored: [
                "**/.agents/**",
                "**/.claude/**",
                "**/.cursor/**",
                "**/.junie/**",
                "**/storage/framework/views/**",
                "**/vendor/**",
            ],
        },
    },
});
