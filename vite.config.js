import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    /**
     * List of plugins to use in Vite.
     * @property {Array} plugins - Array of Vite plugins.
     */
    plugins: [
        laravel({
            /**
             * List of input files for the Laravel plugin.
             * @property {Array} input - Array of input file paths.
             */
            input: [
                // Include all JS files in the `resources/css` directory.
                "resources/css/app.css",
                "resources/css/**/*.css",

                // Include all JS files in the `resources/js` directory.
                "resources/js/app.js",
                "resources/js/**/*.js",

                // Include all JS files in the `resources/modules` directory.
                "resources/modules/**/*.js",
                "resources/modules/**/*.{js,ts}",

                // Include all JS files in the `resources/scss` directory.
                "resources/scss/components/**/*.scss",
                "resources/scss/**/*.scss",

                // Include all JS files in the `resources/` directory.
                "resources/**/*.js",
            ],
            /**
             * Whether to refresh the browser on changes.
             * @property {boolean} refresh - Boolean value indicating whether to refresh the browser on changes.
             */
            refresh: true,
        }),
    ],
});
