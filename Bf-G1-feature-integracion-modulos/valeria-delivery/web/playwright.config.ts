import { defineConfig } from '@playwright/test';
export default defineConfig({
  testDir: './tests', timeout: 90000, expect: { timeout: 15000 }, workers: 1, use: { baseURL: 'http://127.0.0.1:5174', channel: 'msedge', screenshot: 'only-on-failure' },
  projects: [{ name: 'desktop', use: { viewport: { width: 1280, height: 800 } } }, { name: 'mobile', use: { viewport: { width: 390, height: 844 } } }],
});
