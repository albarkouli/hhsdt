# PROJECT ARCHITECTURE — Libya Market

**Document Version:** 1.0  
**Date:** 2026-11-22  
**Status:** Phase 0 Baseline (as-is)  
**Audience:** Technical Team, Potential Acquirer, CTO

---

## 1. Overview

**Libya Market** is a single-instance, multi-vendor e-commerce marketplace built on Laravel 13. It is currently structured as a **traditional MVC monolith** with a partial Service layer. It supports three distinct user domains: Customers, Vendors, and Super Admins.

### Current Maturity Level

| Dimension | Status |
|---|---|
| Feature completeness (marketplace) | ✅ Functional |
| Code organization | 🟡 Partially service-oriented |
| Authorization | ⚠️ Middleware-based, no Policies |
| Multi-tenancy readiness | 🔴 Not started |
| Production readiness | 🟠 Needs config hardening |
| Test coverage | 🟡 76 tests, critical paths only |

---

## 2. Technology Stack (Verified)

| Component | Version | Source |
|---|---|---|
| PHP | 8.5.12 | `php -r` |
| Laravel | 13.42.0 | composer |
| Livewire | **4.4.8** | composer |
| Fortify | Installed | starter kit |
| Spatie Permission | 8.5.0 | composer |
| mPDF | 8.3.1 | composer |
| Dompdf | ❌ Not installed | — |
| Tailwind CSS | ^4.0.7 | package.json |
| Vite | laravel-vite-plugin ^3.1 | package.json |
| Flux UI | v2.x | starter kit |
| Pest | Installed | starter kit |
| Database | PostgreSQL 17 | `.env` |
| Queue | sync (dev) | `.env` |
| Cache | database (dev) | `.env` |

> **Critical:** Livewire actual version is **v4.4.8**, not v3 as assumed in the Master Prompt.

> **Critical:** `barryvdh/laravel-dompdf` is **NOT installed**. Only `mpdf/mpdf` is used for PDF generation.

---
## 3. Directory Structure

libya-marketplace/
├── app/
│ ├── Actions/Fortify/ # Registration, password reset actions
│ ├── Concerns/ # Shared traits
│ ├── Console/ # Artisan commands
│ ├── Enums/ # OrderStatus, VendorStatus
│ ├── Http/
│ │ ├── Controllers/ # 27 controllers
│ │ ├── Middleware/ # EnsureUserHasRole, SecurityHeaders, SetLocale
│ │ └── Requests/ # Form Requests
│ ├── Livewire/ # Livewire 4 components
│ ├── Models/ # 14 Eloquent models
│ ├── Notifications/ # 9 notification classes
│ ├── Providers/ # AppServiceProvider, FortifyServiceProvider
│ └── Services/ # 6 services
├── bootstrap/app.php # Middleware + routing config
├── config/ # Laravel config
├── database/
│ ├── factories/ # 6 factories
│ ├── migrations/ # 20 migrations
│ └── seeders/ # Role, Category, Demo
├── docs/ # This documentation
├── lang/ # ar/shop.php, en/shop.php
├── resources/
│ ├── css/app.css # Tailwind + dark theme tokens
│ ├── js/ # app.js, passkeys.js
│ └── views/
│ ├── components/ # Blade components (layouts, forms)
│ ├── livewire/ # Livewire views
│ ├── shop/ # Customer storefront
│ ├── vendor/ # Vendor dashboard
│ ├── admin/ # Admin dashboard
│ ├── customer/ # Customer dashboard
│ ├── errors/ # 404, 500, 503
│ ├── invoices/ # Invoice HTML + PDF
│ └── notifications/ # Notification center
├── routes/
│ ├── web.php # Main application routes
│ ├── console.php # Artisan commands
│ └── settings.php # Fortify settings routes
├── storage/
│ ├── app/public/ # Public uploads (products, vendors)
│ └── logs/
├── tests/
│ ├── Feature/ # 20 feature tests
│ ├── Unit/ # 1 unit test
│ └── Pest.php
└── vite.config.js


---
## 4. Controllers (27)

### Root (2)
- `Controller.php` — Base controller
- `NotificationsController.php` — Notification center

### Admin (9)
- `Admin\DashboardController` — Platform overview
- `Admin\VendorController` — Approve/reject vendors
- `Admin\CategoryController` — Category CRUD
- `Admin\OrderController` — All orders oversight
- `Admin\CouponController` — Discount codes
- `Admin\EarningsController` — Commission tracking
- `Admin\CommissionController` — Per-vendor rates
- `Admin\ReviewController` — Review moderation
- `Admin\ContactMessageController` — Contact form messages

### Shop (9)
- `Shop\HomeController` — Homepage (`__invoke`)
- `Shop\ProductController` — Browse/filter/show
- `Shop\StoreController` — Public vendor storefront
- `Shop\CartController` — Session cart + coupons
- `Shop\OrderController` — Checkout, orders, cancel
- `Shop\InvoiceController` — Invoice HTML + PDF
- `Shop\ReviewController` — Product reviews
- `Shop\WishlistController` — Favorites
- `Shop\ContactController` — Contact form

### Vendor (6)
- `Vendor\DashboardController` — Stats + charts
- `Vendor\ProductController` — Own products CRUD
- `Vendor\OrderController` — Own orders + transitions
- `Vendor\RegistrationController` — Store registration
- `Vendor\SettingsController` — Store settings
- `Vendor\ReviewController` — Reply to reviews

### Customer (1)
- `Customer\DashboardController` — Customer overview

---

## 5. Services (6)

| Service | Responsibility |
|---|---|
| `CartService` | Session cart, quantity, coupon application |
| `CommissionService` | Compute + record commission on delivery |
| `CouponService` | Validate discount codes |
| `InvoiceService` | Issue/cancel invoices with snapshot |
| `InvoicePdfService` | Generate Arabic PDF via mPDF |
| `ProductRatingService` | Recalculate aggregate product rating |

> **Gap:** Order state transitions (confirm/ship/deliver/cancel) still live in `Vendor\OrderController`. Should extract to a dedicated `OrderStateMachine` service.

---

## 6. Models (14)

| Model | Purpose | Key Relations |
|---|---|---|
| `User` | All authenticated users | `hasOne(Vendor)`, `hasMany(Order)`, `belongsToMany(Product)` |
| `Vendor` | Vendor store profile | `belongsTo(User)`, `hasMany(Product)`, `hasMany(Order)`, `hasMany(PlatformEarning)` |
| `Product` | Product catalog | `belongsTo(Vendor)`, `belongsTo(Category)`, `hasMany(Review)` |
| `Category` | Product taxonomy (flat) | `hasMany(Product)` |
| `Order` | Order header (one per vendor) | `belongsTo(User)`, `belongsTo(Vendor)`, `hasMany(OrderItem)`, `hasOne(Invoice)` |
| `OrderItem` | Frozen line items | `belongsTo(Order)` |
| `OrderModification` | Vendor-proposed changes | `belongsTo(Order)` |
| `Invoice` | Legal invoices | `belongsTo(Order)` |
| `PlatformEarning` | Commission log | `belongsTo(Order)`, `belongsTo(Vendor)` |
| `Review` | Product reviews | `belongsTo(Product)`, `belongsTo(User)` |
| `Wishlist` | Favorites pivot | `belongsTo(User)`, `belongsTo(Product)` |
| `Coupon` | Discount codes | `hasMany(CouponUsage)` |
| `CouponUsage` | Coupon usage log | `belongsTo(Coupon)` |
| `ContactMessage` | Contact form submissions | — |

---
## 7. Middleware (Verified)

Registered in `bootstrap/app.php`:

~~~php
$middleware->append(SecurityHeaders::class);
$middleware->web(append: [SetLocale::class]);
$middleware->alias(['role' => EnsureUserHasRole::class]);
~~~

| Middleware | Purpose |
|---|---|
| `SecurityHeaders` | X-Frame-Options, nosniff, Referrer-Policy |
| `SetLocale` | Session locale, default en |
| `EnsureUserHasRole` | Returns 403 if user lacks required role |

> **CRITICAL FINDING:** There is **NO** `app/Policies` directory. All authorization is done via:
> 1. Role middleware (`role:vendor`, `role:super_admin`)
> 2. Manual `abort_if` checks inside controllers
>
> This is a security and maintainability risk. See `SECURITY_AUDIT.md`.

---

## 8. Frontend Layer

### 8.1 Livewire Components

The project uses **Livewire v4.4.8** (not v3). Notable differences:
- Single-file components in `resources/views/livewire/`
- `Route::livewire()` syntax (used in `routes/settings.php`)
- Flux UI v2 integration

### 8.2 Layouts

| Layout | Used By |
|---|---|
| `layouts/shop.blade.php` | Storefront, wishlist, orders, about, contact |
| `layouts/dashboard.blade.php` | All dashboards (vendor/admin/customer) |
| `layouts/auth/simple.blade.php` | Login, register, password reset, 2FA |

### 8.3 Styling

- Tailwind CSS 4 with `@theme` tokens
- Dark theme: `--color-ink: #0a0a0a`, `--color-surface: #171717`, `--color-accent: #a3e635`
- Fonts: Inter (English) + Cairo (Arabic)

---

## 9. Authentication (Fortify)

- Fortify handles: registration, login, password reset, email verification, 2FA, passkeys
- Passkeys via `laravel/passkeys`
- `User::booted()` auto-assigns `customer` role on user creation

---

## 10. Known Architectural Issues

| # | Issue | Severity | Recommendation |
|---|---|---|---|
| 1 | No `app/Policies` directory | High | Migrate `abort_if` to Policies |
| 2 | Order state transitions in controller | Medium | Extract `OrderStateMachine` |
| 3 | No Audit Log | High | Phase 1 deliverable |
| 4 | No tenant concept | High | Phase 4 |
| 5 | Session-based cart | Low | Acceptable for MVP |
| 6 | `QUEUE_CONNECTION=sync` | Medium | Use database/redis in prod |
| 7 | `MAIL_MAILER=log` | Medium | Use SMTP/API in prod |
| 8 | `APP_DEBUG=true` | High | Set to `false` in prod |
| 9 | Possible duplicate notifications | Low | Verify `OrderPlaced*` classes |
| 10 | No API layer | High | Phase 3 |

---

## 11. Testing

- Framework: Pest
- Files: 20 test files
- Tests: 76 passing (184 assertions)
- Coverage: Feature-level only; no unit tests for Services
- Gap: No tests verify vendor-level isolation at Policy level

---

## 12. Routing Summary

Total: **149 routes**

| Prefix | Count |
|---|---|
| `admin/*` | 34 |
| `shop/*` | 28 |
| `vendor/*` | 22 |
| `user/*` | 13 |
| `flux/*` | 9 |
| `livewire-*` | 9 |
| `passkeys/*` | 4 |
| `notifications/*` | 4 |
| `settings/*` | 4 |
| others | ~22 |

---

## 13. Next Steps

Refer to `TECHNICAL_AUDIT.md` and `DEVELOPMENT_ROADMAP.md`.

Priority order:
1. **Phase 1:** Add Policies, Audit Log, extract `OrderStateMachine`.
2. **Phase 2:** Returns/Refunds, Payment Abstraction, Financial Ledger.
3. **Phase 4:** Multi-Tenancy (`tenant_id` columns via expand-and-contract).
4. **Phase 5:** White-label theming.
5. **Phase 6:** Subscriptions & Billing.

---

**End of Document**