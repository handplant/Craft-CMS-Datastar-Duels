import { defineConfig } from 'vite'
import ViteRestart from 'vite-plugin-restart'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({ command }) => ({
  base: command === 'serve' ? '' : '/dist/',

  build: {
    emptyOutDir: true,
    manifest: true,
    outDir: 'web/dist/',
    sourcemap: true,

    rollupOptions: {
      input: {
        app: 'src/js/app.js',
      },
    },
  },

  plugins: [
    tailwindcss(),
    ViteRestart({
      restart: ['templates/**/*.twig'],
      delay: 100,
    }),
  ],

  publicDir: './src/public',

  server: {
    allowedHosts: true,
    cors: {
      origin:
        /https?:\/\/([A-Za-z0-9\-\.]+)?(localhost|\.local|\.test|\.site)(?::\d+)?$/,
    },
    fs: {
      strict: false,
    },
    headers: {
      'Access-Control-Allow-Private-Network': 'true',
    },
    host: '0.0.0.0',
    origin: 'http://localhost:3000',
    port: 3000,
    strictPort: true,
  },
}))
