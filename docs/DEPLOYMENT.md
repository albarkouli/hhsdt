# Libya Marketplace - Deployment Guide

Production deployment on Ubuntu 24.04.

## Server
- Hetzner CX22 (2 vCPU, 4GB) ~4.5 EUR/mo (recommended)
- DigitalOcean Basic 2GB $12/mo
- Ubuntu 24.04 LTS

## 1. Initial (as root)
apt update && apt upgrade -y
adduser libya
usermod -aG sudo libya
rsync --archive --chown=libya:libya ~/.ssh /home/libya
# /etc/ssh/sshd_config: PermitRootLogin no, PasswordAuthentication no
systemctl restart ssh
ufw allow OpenSSH && ufw allow 80/tcp && ufw allow 443/tcp && ufw enable

## 2. Packages
add-apt-repository ppa:ondrej/php -y
apt install -y php8.3-fpm php8.3-cli php8.3-pgsql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl
apt install -y postgresql postgresql-contrib nginx composer
curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt install -y nodejs

## 3. Database
sudo -u postgres psql
CREATE DATABASE libya_marketplace;
CREATE USER libya_app WITH ENCRYPTED PASSWORD 'STRONG-PASSWORD-HERE';
GRANT ALL PRIVILEGES ON DATABASE libya_marketplace TO libya_app;
\c libya_marketplace
GRANT ALL ON SCHEMA public TO libya_app;
\q

## 4. Deploy code
mkdir -p /var/www/libya-marketplace
chown libya:libya /var/www/libya-marketplace
cd /var/www/libya-marketplace
git clone YOUR_REPO_URL .
cp .env.production.example .env
nano .env
# Fill: DB_PASSWORD, MAIL_*, APP_URL (leave APP_KEY blank)
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm ci && npm run build
chown -R libya:www-data /var/www/libya-marketplace
chmod -R 775 storage bootstrap/cache
## 5. Nginx
# File: /etc/nginx/sites-available/libya-marketplace
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/libya-marketplace/public;
    index index.php;
    client_max_body_size 20M;
    add_header X-Frame-Options DENY always;
    add_header X-Content-Type-Options nosniff always;
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\.(?!well-known).* { deny all; }
}
# ln -s /etc/nginx/sites-available/libya-marketplace /etc/nginx/sites-enabled/
# nginx -t && systemctl reload nginx

## 6. SSL
apt install -y certbot python3-certbot-nginx
certbot --nginx -d yourdomain.com -d www.yourdomain.com
certbot renew --dry-run

## 7. Queue worker (Supervisor)
# File: /etc/supervisor/conf.d/libya-worker.conf
[program:libya-worker]
command=php /var/www/libya-marketplace/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=libya
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/libya-marketplace/storage/logs/worker.log
# supervisorctl reread && supervisorctl update && supervisorctl start libya-worker:*

## 8. Backup cron
chmod +x /var/www/libya-marketplace/scripts/*.sh
crontab -u libya -e
# Add: 0 3 * * * /var/www/libya-marketplace/scripts/backup.sh >> /var/log/libya-backup.log 2>&1

## 9. Deploy updates
cd /var/www/libya-marketplace
git pull origin main
./scripts/deploy.sh
./scripts/health-check.sh

## 10. DNS
- A record @ -> server IP
- A record www -> server IP
- Optional: Cloudflare proxy (orange cloud)

## 11. Final checks
- APP_DEBUG=false
- APP_KEY set
- Strong DB password
- migrate --force OK
- storage:link OK
- HTTPS works
- /up returns 200
- Product image upload works
- Email sending works
- Backup cron works
- supervisorctl status = RUNNING