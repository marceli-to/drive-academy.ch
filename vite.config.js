// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
const path = require('path');

export default defineConfig({
  resolve:{
    alias:{
      '@' : path.resolve(__dirname, './resources/js/')
    },
  },
  server: {
    cors: {
      origin: ['https://drive-academy.ch.test'],
      credentials: true,
    },
    hmr: {
      host: 'localhost',
    },
  },
  plugins: [
    vue(),
    laravel([
      'resources/js/app.js',
      'resources/js/form/register.js',
    ]),
  ],
});