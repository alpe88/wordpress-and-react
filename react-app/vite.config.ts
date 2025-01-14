import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import path from "path";

export default defineConfig({
  plugins: [react()],

  // Where the build files are generated
  build: {
    outDir: path.resolve(__dirname, "../wordpress-theme/assets/js"),
    emptyOutDir: true, // Clears the output dir before each build

    rollupOptions: {
      input: {
        // Each entry corresponds to a React mount (header, footer, front-page, etc.)
        header: path.resolve(__dirname, "src/entries/header.tsx"),
        footer: path.resolve(__dirname, "src/entries/footer.tsx"),
        front_page: path.resolve(__dirname, "src/entries/front-page.tsx"),
        archive: path.resolve(__dirname, "src/entries/archive.tsx"),
        not_found: path.resolve(__dirname, "src/entries/not-found.tsx"),
        page: path.resolve(__dirname, "src/entries/page.tsx"),
        index: path.resolve(__dirname, "src/entries/blog.tsx"),
        // Add more entries as needed...
      },
      output: {
        // Filenames for compiled JS/CSS
        entryFileNames: "[name].[hash].js",
        chunkFileNames: "chunks/[name].[hash].js",
        assetFileNames: "assets/[name].[hash].[ext]",
      },
    },

    // Tells Vite to produce a manifest.json for script enqueuing in WP
    manifest: true,
  },
});
