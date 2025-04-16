import { defineConfig } from "vite";
import path from "path";

export default defineConfig({
    root: "assets/src",
    build: {
        outDir: "../dist",
        emptyOutDir: true,
        rollupOptions: {
            input: {
                "dashboard": path.resolve(__dirname, "/scss/dashboard.scss"),
				//"grid": path.resolve(__dirname, "/js/grid.js"),
            },
            output: {
				entryFileNames: "js/[name].js",
                assetFileNames: "css/[name].css",
            },
        },
    },
    css: {
        postcss: "./config/postcss.config.js"
    },
});
