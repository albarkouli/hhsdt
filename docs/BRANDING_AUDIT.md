# BRANDING AUDIT - Libya Market to WESALKOM | ظˆطµط§ظ„ظƒظ…

**Generated:** 2026-11-22 19:06

---

## 1. Old-brand occurrences

Total hits: **43**

### .env

- Line 1: APP_NAME=LibyaMarketplace
- Line 62: DB_DATABASE=libya_marketplace
- Line 72: MAIL_FROM_ADDRESS="noreply@libyamarketplace.test"

### .env.example

- Line 1: APP_NAME=LibyaMarketplace
- Line 5: APP_URL=http://libya-marketplace.test

### app\Notifications\OrderPlaced.php

- Line 29: ->line("You have a new order on Libya Marketplace.")

### app\Notifications\OrderStatusChanged.php

- Line 70: ->line('Thank you for shopping on Libya Marketplace.');

### app\Notifications\VendorApproved.php

- Line 27: ->line("Your store **{$this->vendor->name}** has been approved and is now live on Libya Marketplace.")
- Line 30: ->line('Welcome to Libya Marketplace!');

### app\Notifications\VendorSuspended.php

- Line 31: ->line('If you believe this is a mistake, please contact us at hello@libyamarketplace.ly')

### lang\en\shop.php

- Line 3: 'brand' => 'Libya Marketplace',
- Line 14: 'hero_btn_sell' => 'Sell on Libya Marketplace',
- Line 21: 'section_vendors_sub' => 'Trusted vendors on Libya Marketplace',
- Line 26: 'cta_desc' => 'Join Libya Marketplace and sell to customers across the country. No setup fees, cash on delivery, and full control over your products and orders.',

### resources\views\components\layouts\dashboard.blade.php

- Line 9: <title>{{ $title ?? 'Dashboard' }} - Libya Marketplace</title>

### resources\views\components\layouts\shop.blade.php

- Line 11: <meta property="og:title" content="Libya Market">
- Line 13: <meta property="og:site_name" content="Libya Market">
- Line 15: <title>{{ $title ?? 'Libya Marketplace' }}</title>
- Line 105: <a href="{{ route('vendor.register') }}" class="block px-3 py-2.5 mt-2 text-xs font-black text-lime-400 hover:bg-gray-900 rounded-md">Sell on Libya Market &rarr;</a>
- Line 118: <span class="text-xs text-gray-500">Sell on Libya Market?</span>
- Line 155: <a href="https://facebook.com/libyamarketplace" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-lime-400 hover:border-lime-400/30 transition" aria-label="Facebook"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
- Line 156: <a href="https://instagram.com/libyamarketplace" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-lime-400 hover:border-lime-400/30 transition" aria-label="Instagram"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
- Line 157: <a href="https://twitter.com/libyamarket" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-lime-400 hover:border-lime-400/30 transition" aria-label="Twitter"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
- Line 181: <p>&copy; {{ date('Y') }} Libya Marketplace. All rights reserved.</p>

### resources\views\customer\dashboard.blade.php

- Line 85: <p class="text-sm text-black/70 max-w-md">Open your own store on Libya Market and reach customers across Libya. Zero setup fees.</p>

### resources\views\emails\order-placed-customer.blade.php

- Line 13: Libya Marketplace
- Line 90: <p style="margin:0;">© {{ date('Y') }} Libya Marketplace. All rights reserved.</p>

### resources\views\emails\order-placed-vendor.blade.php

- Line 13: Libya Marketplace
- Line 79: <p style="margin:0;">© {{ date('Y') }} Libya Marketplace. All rights reserved.</p>

### resources\views\errors\404.blade.php

- Line 7: <title>404 - Page not found | Libya Market</title>

### resources\views\errors\500.blade.php

- Line 7: <title>500 - Server error | Libya Market</title>

### resources\views\errors\503.blade.php

- Line 7: <title>Maintenance آ· Libya Market</title>

### resources\views\invoices\pdf.blade.php

- Line 129: <p>Libya Marketplace — {{ config('app.url') }}</p>

### resources\views\invoices\show.blade.php

- Line 134: <p class="mt-2">Libya Marketplace - {{ config('app.url') }}</p>

### resources\views\layouts\auth\simple.blade.php

- Line 33: &copy; {{ date('Y') }} Libya Marketplace

### resources\views\shop\about.blade.php

- Line 5: <h1 class="text-4xl sm:text-5xl font-black text-white mb-4">About Libya Market</h1>
- Line 11: Libya Market is a homegrown online marketplace built to serve Libyan vendors and shoppers. Our mission is simple: give local businesses a modern, trusted platform to sell online — and give customers a single destination to discover products from across the country.

### resources\views\shop\contact.blade.php

- Line 19: <a href="mailto:hello@libyamarketplace.ly" class="text-sm text-lime-400 hover:text-lime-300">hello@libyamarketplace.ly</a>

### resources\views\shop\home.blade.php

- Line 1: <x-layouts.shop :title="'Libya Marketplace'">
- Line 32: Sell on Libya Market

### resources\views\shop\products\show.blade.php

- Line 6: $waMessage = urlencode("Hello {$product->vendor->name}, I'm interested in \"{$product->name}\" (from Libya Marketplace).");

### resources\views\welcome.blade.php

- Line 1: <x-layouts.shop :title="'Libya Marketplace'">
- Line 3: <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Libya Marketplace</h1>

---

## 2. Current theme tokens (resources/css/app.css)

~~~css
--font-sans: 'Inter', 'Cairo', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji';
    --font-arabic: 'Cairo', 'Inter', sans-serif;
    --font-display: 'Inter', sans-serif;

    --color-accent: #a3e635;
    --color-accent-hover: #84cc16;
    --color-accent-soft: rgba(163, 230, 53, 0.1);

    --color-ink: #0a0a0a;
    --color-surface: #171717;
    --color-surface-2: #262626;
    --color-border: #262626;
    --color-muted: #737373;
~~~

---

## 3. Layouts inventory

- resources\views\components\layouts\dashboard.blade.php
- resources\views\components\layouts\shop.blade.php

---

## 4. Blade components inventory

- resources\views\components\app-logo.blade.php
- resources\views\components\app-logo-icon.blade.php
- resources\views\components\auth-header.blade.php
- resources\views\components\auth-session-status.blade.php
- resources\views\components\desktop-user-menu.blade.php
- resources\views\components\order-timeline.blade.php
- resources\views\components\passkey-registration.blade.php
- resources\views\components\passkey-verify.blade.php
- resources\views\components\placeholder-pattern.blade.php
- resources\views\components\settings\layout.blade.php

---

## 5. Package versions (composer.json / package.json)

### composer.json (require)
~~~json
  php : ^8.4
  dompdf/dompdf : ^3.1
  laravel/chisel : ^0.1.0
  laravel/fortify : ^1.37.2
  laravel/framework : ^13.17
  laravel/tinker : ^3.0
  livewire/blaze : ^1.0
  livewire/flux : ^2.13.1
  livewire/livewire : ^4.1
  mpdf/mpdf : ^8.3
  spatie/laravel-permission : ^8.0
~~~

### package.json
~~~json
  @laravel/passkeys : ^0.2.0
  @tailwindcss/vite : ^4.1.11
  concurrently : ^10.0.3
  laravel-vite-plugin : ^3.1
  tailwindcss : ^4.0.7
  vite : npm:@voidzero-dev/vite-plus-core@1.4.0
  vite-plus : 1.4.0
~~~
