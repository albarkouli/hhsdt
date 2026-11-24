# BRAND REPLACEMENT MAP - Tier 3 Execution Report

**Date:** 2026-11-22 19:14:02
**Backup:** .backup/brand-20261122-191402/

## Protected (DO NOT TOUCH)
- .env L62: DB_DATABASE=libya_marketplace

## Tier 2 (pending owner)
- .env L72: noreply@libyamarketplace.test
- app/Notifications/VendorSuspended.php L31: hello@libyamarketplace.ly
- resources/views/shop/contact.blade.php L19: hello@libyamarketplace.ly
- resources/views/components/layouts/shop.blade.php L155-157: facebook/instagram/twitter libyamarketplace URLs

## Geographic (kept)
- resources/views/customer/dashboard.blade.php L85: "across Libya"
- resources/views/shop/about.blade.php L11: "Libyan vendors"

## Per-file results

- OK .env : 1 replacement(s)
- OK .env.example : 2 replacement(s)
- OK app/Notifications/OrderPlaced.php : 1 replacement(s)
- OK app/Notifications/OrderStatusChanged.php : 1 replacement(s)
- OK app/Notifications/VendorApproved.php : 2 replacement(s)
- OK lang/en/shop.php : 4 replacement(s)
- OK resources/views/components/layouts/dashboard.blade.php : 1 replacement(s)
- OK resources/views/components/layouts/shop.blade.php : 6 replacement(s)
- OK resources/views/customer/dashboard.blade.php : 1 replacement(s)
- OK resources/views/emails/order-placed-customer.blade.php : 2 replacement(s)
- OK resources/views/emails/order-placed-vendor.blade.php : 2 replacement(s)
- OK resources/views/errors/404.blade.php : 1 replacement(s)
- OK resources/views/errors/500.blade.php : 1 replacement(s)
- OK resources/views/errors/503.blade.php : 1 replacement(s)
- OK resources/views/invoices/pdf.blade.php : 1 replacement(s)
- OK resources/views/invoices/show.blade.php : 1 replacement(s)
- OK resources/views/layouts/auth/simple.blade.php : 1 replacement(s)
- OK resources/views/shop/about.blade.php : 2 replacement(s)
- OK resources/views/shop/home.blade.php : 2 replacement(s)
- OK resources/views/shop/products/show.blade.php : 1 replacement(s)
- OK resources/views/welcome.blade.php : 2 replacement(s)

**Total replacements:** 36

## WARN: lang/ar/shop.php - manual inspection
- Line 3: 'brand' => 'سوق ليبيا',
- Line 8: 'hero_tagline' => 'بيتك للتسوق عبر الإنترنت في ليبيا',
- Line 9: 'hero_title' => 'اكتشف أفضل المتاجر المحلية في ليبيا',
- Line 10: 'hero_desc' => 'تسوق من تجار موثوقين في جميع أنحاء ليبيا. منتجات عالية الجودة، الدفع عند الاستلام، وسوق مصمم لليبيين.',
- Line 14: 'hero_btn_sell' => 'بع على سوق ليبيا',
- Line 21: 'section_vendors_sub' => 'تجار موثوقون على سوق ليبيا',
- Line 26: 'cta_desc' => 'انضم إلى سوق ليبيا وبع للعملاء في جميع أنحاء البلاد. بدون رسوم إعداد، الدفع عند الاستلام، وتحكم كامل في منتجاتك وطلباتك.',