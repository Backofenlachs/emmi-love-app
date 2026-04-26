import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import { VitePWA } from 'vite-plugin-pwa'

export default defineConfig({
  base: "/emmi-love-app/",   // WICHTIG für GitHub Pages

  plugins: [
    vue(),
    vueDevTools(),

    VitePWA({
      registerType: 'autoUpdate',
      manifest: {
        name: 'Für Emmi ❤️',
        short_name: 'Emmi ❤️',
        description: 'Eine kleine Liebeserklärung',
        theme_color: '#ff9a9e',
        background_color: '#ff9a9e',
        display: 'standalone',

        start_url: '/emmi-love-app/',
        scope: '/emmi-love-app/',

        icons: [
          {
            src: 'icon-192.png',
            sizes: '192x192',
            type: 'image/png'
          },
          {
            src: 'icon-512.png',
            sizes: '512x512',
            type: 'image/png'
          }
        ]
      },

      workbox: {
        globPatterns: ['**/*.{js,css,html,png,svg,ico}']
      }
    })
  ],

  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
})