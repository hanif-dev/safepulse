import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    // Must be 0.0.0.0 so Codespaces port forwarding can reach the server
    host: '0.0.0.0',
    port: 5173,
    // Proxy API calls to Laravel in dev — avoids CORS issues when both
    // run behind the same Codespaces forwarded host.
    // Remove or update this in production (Amplify → EC2).
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: false,
      },
    },
  },
  build: {
    outDir: 'dist',
    sourcemap: false,
  },
});
