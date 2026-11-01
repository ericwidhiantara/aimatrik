import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    testMatch: ['**/public.spec.js', '**/auth.spec.js'],
    workers: 1,
    retries: 0,
    reporter: 'list',
    outputDir: './node_modules/.cache/playwright-results',
    use: {
        baseURL: process.env.PW_BASE_URL || 'http://127.0.0.1:8000',
        browserName: 'chromium',
        headless: true,
        screenshot: 'on',
        trace: 'retain-on-failure',
        video: 'off',
    },
});
