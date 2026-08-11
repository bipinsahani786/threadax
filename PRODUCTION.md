# ThreadAX — Production Deployment Guide
## Hostinger Shared Hosting (hPanel) + cPanel Compatible

---

## ⚙️ Server Requirements

| Requirement | Minimum | Recommended |
|------------|---------|-------------|
| PHP | 8.2 | 8.3+ |
| MySQL | 5.7 | 8.0+ |
| Disk Space | 500 MB | 1 GB+ |
| RAM | 512 MB | 1 GB+ |
| SSL | Required | Let's Encrypt (free) |

### Required PHP Extensions
Enable these in hPanel → Advanced → PHP Configuration:

```
bcmath, ctype, curl, dom, fileinfo, filter, hash, json,
mbstring, openssl, pcre, pdo, pdo_mysql, session, tokenizer, xml, zip
```

---

## 🔐 Required `.env` Variables

Copy `.env.example` to `.env` and fill in ALL these values:

### Core App
```env
APP_NAME="ThreadAX"
APP_ENV=production
APP_KEY=          # Run: php artisan key:generate
APP_DEBUG=false   # ⚠️ MUST be false in production
APP_URL=https://yourdomain.com
```

### Database (from hPanel → MySQL Databases)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1   # or your DB host from hPanel
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### Email (SMTP)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password   # Gmail App Password (not login password)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="ThreadAX"
```

### Razorpay (Get from https://dashboard.razorpay.com)
```env
RAZORPAY_KEY_ID=rzp_live_xxxxxxxxxxxx       # Your live key
RAZORPAY_KEY_SECRET=xxxxxxxxxxxxxxxxxxxxxxxx # Your live secret
RAZORPAY_WEBHOOK_SECRET=xxxxx               # Set in Razorpay Dashboard → Webhooks
```

### Session & Cache
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=file
QUEUE_CONNECTION=database
```

### Google OAuth (Optional)
```env
GOOGLE_CLIENT_ID=xxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxxxx
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

### Marketing (Optional)
```env
WHATSAPP_NUMBER=919876543210   # Country code + number, no +
GA_MEASUREMENT_ID=G-XXXXXXXX
META_PIXEL_ID=XXXXXXXXXX
```

---

## 🚀 Deployment Steps (Hostinger hPanel)

### Step 1: Upload Files
Upload the **entire project** (except `node_modules/` and `.git/`) to your hosting.

> ⚠️ **IMPORTANT for shared hosting**: Hostinger's webroot is `public_html/`. You have two options:

**Option A (Recommended): Point domain to `/public` folder**
1. hPanel → Domains → Manage → Change Document Root to `/public_html/threadax/public`

**Option B: Move files**
```bash
# Move public/ contents to public_html/ and adjust paths
# Then update index.php to point to correct paths
```

### Step 2: Set Up `.env`
1. Copy `.env.example` → `.env`
2. Fill in all values from the table above
3. **Never commit `.env` to Git!**

### Step 3: Run Commands via SSH
Hostinger provides SSH access. Open terminal:

```bash
# Go to project folder
cd ~/public_html/threadax

# Install PHP dependencies (no dev)
composer install --no-dev --optimize-autoloader

# Generate app key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Create storage symlink
php artisan storage:link

# Cache config and routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### Step 4: Build Frontend Assets (do this locally then upload)
```bash
# On your LOCAL machine:
npm install
npm run build

# Then upload the public/build/ folder to server
```

### Step 5: Set Up Cron Job (Queue Worker)
hPanel → Cron Jobs → Add:
```
* * * * * /usr/local/bin/php /home/username/public_html/threadax/artisan schedule:run >> /dev/null 2>&1
```

### Step 6: Configure Razorpay Webhook
1. Log in to [Razorpay Dashboard](https://dashboard.razorpay.com)
2. Settings → Webhooks → Add New Webhook
3. URL: `https://yourdomain.com/webhooks/razorpay`
4. Events: Check `payment.captured`, `payment.failed`, `refund.processed`
5. Copy the Webhook Secret → paste into `.env` as `RAZORPAY_WEBHOOK_SECRET`

---

## 🔒 Security Checklist Before Going Live

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] `.env` file is NOT accessible via browser (check with `yourdomain.com/.env`)
- [ ] SSL certificate installed (Let's Encrypt in hPanel)
- [ ] Razorpay live keys (not test keys) added to `.env`
- [ ] Webhook secret configured
- [ ] Storage directory has correct permissions (`755`)
- [ ] `php artisan config:cache` run after any `.env` changes
- [ ] Admin password is strong (change default)
- [ ] Google OAuth redirect URI updated to production URL

---

## 🗄️ .htaccess for public_html (if NOT using subdirectory)

If you uploaded Laravel to `public_html/` directly (not in a subfolder), use this `.htaccess` in `public_html/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

And in `public_html/public/.htaccess`, the default Laravel one is fine.

---

## 📁 Folder Structure on Hostinger

```
/home/username/
└── public_html/               ← Hostinger webroot
    └── threadax/              ← Your Laravel project
        ├── app/
        ├── bootstrap/
        ├── config/
        ├── database/
        ├── public/            ← Point domain document root HERE
        │   ├── build/         ← Compiled CSS/JS (upload after npm run build)
        │   ├── storage/       ← Symlink (created by php artisan storage:link)
        │   └── index.php
        ├── resources/
        ├── routes/
        ├── storage/           ← Must be writable (chmod 755)
        ├── vendor/            ← PHP packages (composer install)
        ├── .env               ← Environment config (NEVER commit to Git)
        └── artisan
```

---

## 🔄 Post-Deployment Checks

```bash
# Verify app is working
php artisan about

# Test database connection
php artisan migrate:status

# Check if storage is linked
ls -la public/storage
```

### Test These Flows:
1. ✅ Homepage loads without errors
2. ✅ Login with OTP works
3. ✅ Add to cart works
4. ✅ Checkout with COD works
5. ✅ Checkout with Razorpay works (use test mode first)
6. ✅ Admin panel accessible at `/admin/login`
7. ✅ Order status change triggers stock restore
8. ✅ Cancel Razorpay-paid order triggers refund

---

## 📝 Update / Redeploy Process

```bash
# 1. Pull latest code
git pull origin main

# 2. Install new dependencies (if any)
composer install --no-dev --optimize-autoloader

# 3. Run new migrations
php artisan migrate --force

# 4. Build new frontend assets (locally, then upload public/build/)
npm run build

# 5. Clear and re-cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🆘 Common Issues

| Issue | Fix |
|-------|-----|
| 500 Server Error | Check `storage/logs/laravel.log`, set `APP_DEBUG=true` temporarily |
| Images not showing | Run `php artisan storage:link` |
| Payment not working | Verify Razorpay keys in `.env`, run `php artisan config:cache` |
| Session expired quickly | Increase `SESSION_LIFETIME` in `.env` |
| Emails not sending | Check SMTP credentials, try Gmail App Password |
| Permission denied | `chmod -R 755 storage/ bootstrap/cache/` |

---

## 📞 Support

- Hostinger Support: https://support.hostinger.com
- Razorpay Docs: https://razorpay.com/docs/
- Laravel Docs: https://laravel.com/docs/
