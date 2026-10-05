import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [react(), tailwindcss()],
  server: { port: 5173 },
  // Existing PHP pages and APIs remain served by the backend during development.
  // Set VITE_API_BASE_URL when the frontend and API use different origins.
})
