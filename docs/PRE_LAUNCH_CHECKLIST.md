# Libya Marketplace - Pre-Launch Checklist

## Security
- [ ] APP_DEBUG=false
- [ ] APP_KEY generated
- [ ] SESSION_ENCRYPT=true, SESSION_SECURE_COOKIE=true
- [ ] Strong DB password (16+ chars)
- [ ] SSH: PasswordAuthentication no, PermitRootLogin no
- [ ] UFW enabled
- [ ] HTTPS enforced
- [ ] Rate limiting active (checkout, cart, contact)
- [ ] Security headers present
- [ ] .env in .gitignore, no secrets in Git

## Data
- [ ] Backup cron runs (test manually)
- [ ] Restore tested on staging
- [ ] Disk space > 20% free
- [ ] Indexes on orders, products, invoices

## Email
- [ ] Real SMTP (Mailgun/Postmark/SendGrid)
- [ ] SPF + DKIM configured
- [ ] Contact form sends
- [ ] Order confirm sends
- [ ] PDF attachment opens

## Performance
- [ ] config:cache, route:cache, view:cache
- [ ] OPcache enabled
- [ ] Nginx gzip + browser caching
- [ ] npm run build complete
- [ ] Queue worker running

## Functionality - Customer
- [ ] Register, login
- [ ] Browse, search, filter
- [ ] Cart, checkout
- [ ] Receive notifications
- [ ] Download PDF invoice

## Functionality - Vendor
- [ ] Open store, upload logo
- [ ] Add products with images
- [ ] Receive orders, confirm, modify, ship, deliver
- [ ] Dashboard charts work

## Functionality - Admin
- [ ] Approve/reject/suspend vendor
- [ ] Manage categories
- [ ] View all orders, cancel
- [ ] Contact messages

## Testing
- [ ] php artisan test all green
- [ ] Manual mobile test (Chrome/Safari)
- [ ] Full purchase flow from clean browser
- [ ] Slow network test

## Monitoring
- [ ] Uptime monitor (UptimeRobot)
- [ ] Error tracking (Sentry - optional)
- [ ] Backup failure alerts

## Legal
- [ ] Terms of Service
- [ ] Privacy Policy
- [ ] Commercial registration (per Libyan law)

---
Planned launch: _____
Responsible: _____