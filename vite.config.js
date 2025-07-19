import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
  ],
  server: {
        https: true, // Buộc Vite sử dụng HTTPS
        host: '0.0.0.0', // Cho phép truy cập từ bên ngoài
        hmr: {
            host: '13fa9d9be0b9.ngrok-free.app', // Thay bằng URL ngrok của bạn
            protocol: 'https',
        },
    },
});

