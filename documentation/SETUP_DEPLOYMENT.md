# Setup and Deployment Guide

## 🚀 Quick Setup Guide

### Prerequisites
- **PHP 8.2+** with required extensions (mbstring, openssl, PDO, tokenizer, XML)
- **Composer** for dependency management
- **Node.js 18+** and NPM for asset compilation
- **Database** (SQLite for development, MySQL/PostgreSQL for production)
- **Web Server** (Apache/Nginx with URL rewriting)

### 🔧 Development Setup

#### 1. Project Installation
```bash
# Clone or download the project
git clone <repository-url>
cd student_skill_tracker

# Install PHP dependencies
composer install

# Install Node.js dependencies and build assets
npm install
npm run build
```

#### 2. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database settings in .env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

#### 3. Database Setup
```bash
# Create SQLite database file (if using SQLite)
touch database/database.sqlite

# Run migrations and seed with sample data
php artisan migrate --seed

# Create storage symlink for file uploads
php artisan storage:link
```

#### 4. Start Development Server
```bash
php artisan serve
# Application available at http://127.0.0.1:8000
```

### 🔑 Default User Accounts
After seeding, these accounts are available:

- **Master Admin:** `nazmus.sakib.raiyan@g.bracu.ac.bd` / `admin123`
- **Club Manager:** `manager@example.com` / `password` 
- **Student:** `student@example.com` / `password`

*Note: Club Manager and Student accounts require email verification*

## 🏭 Production Deployment

### 1. Server Requirements
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **PHP:** 8.2+ with extensions
- **Database:** MySQL 8.0+ or PostgreSQL 13+
- **SSL Certificate:** Required for production security

### 2. Production Installation
```bash
# Install dependencies (production mode)
composer install --optimize-autoloader --no-dev

# Build production assets
npm ci --production
npm run production

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux
```

### 3. Environment Configuration
```bash
# Copy and configure production environment
cp .env.example .env.production

# Configure for production in .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail configuration (required for email verification)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### 4. Database Migration
```bash
# Run migrations in production
php artisan migrate --force

# Seed with sample data (optional)
php artisan db:seed --class=DatabaseSeeder

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```
Clear cache after deployment:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Task Scheduling Setup
The application uses Laravel's task scheduler for automated processes:

#### Linux/macOS (Cron Job)
```bash
# Add to crontab (crontab -e)
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

#### Windows (Task Scheduler)
1. Open Task Scheduler
2. Create Basic Task → "Laravel Scheduler"
3. Trigger: Daily at startup
4. Action: Start a program
5. Program: `php`
6. Arguments: `artisan schedule:run`
7. Start in: `C:\path\to\your\project`
8. Set to run every minute in advanced settings

### 6. Web Server Configuration

#### Apache Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/student_skill_tracker/public
    
    <Directory /path/to/student_skill_tracker/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /path/to/student_skill_tracker/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /path/to/student_skill_tracker/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl;
    server_name yourdomain.com;
    root /path/to/student_skill_tracker/public;
    index index.php;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## 🔧 Maintenance & Troubleshooting

### Common Commands
```bash
# Clear all caches
php artisan optimize:clear

# View application logs
tail -f storage/logs/laravel.log

# Check scheduled tasks
php artisan schedule:list

# Run specific task manually
php artisan events:auto-complete

# Check queue status
php artisan queue:work

# Restart queue workers
php artisan queue:restart
```

### Performance Optimization
```bash
# Enable OPcache (add to php.ini)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60

# Configure session storage (Redis recommended)
SESSION_DRIVER=redis
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

# Database optimization
# Add indexes for frequently queried columns
# Use database connection pooling
# Configure query caching
```

### Backup Strategy
```bash
# Database backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# File storage backup
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/app/public

# Complete application backup
tar -czf app_backup_$(date +%Y%m%d).tar.gz . --exclude=node_modules --exclude=vendor
```

## 🚨 Security Considerations

### Application Security
- **HTTPS Only:** Force HTTPS in production
- **Environment Variables:** Never commit .env to version control
- **File Permissions:** Restrict write access to storage and cache directories
- **Database Security:** Use dedicated database users with minimal privileges
- **Regular Updates:** Keep Laravel and dependencies updated

### Server Security
- **Firewall:** Configure firewall to allow only necessary ports
- **SSH Keys:** Use SSH key authentication instead of passwords
- **Regular Updates:** Keep server OS and software updated
- **Monitoring:** Implement log monitoring and intrusion detection

## 📊 Monitoring & Analytics

### Application Monitoring
```bash
# Install monitoring tools
composer require --dev barryvdh/laravel-debugbar
composer require spatie/laravel-activitylog

# Configure logging in .env
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Performance Monitoring
- **Database Query Monitoring:** Use Laravel Telescope for development
- **Application Performance:** Monitor response times and memory usage
- **Error Tracking:** Implement error tracking (Sentry, Bugsnag)
- **Uptime Monitoring:** Set up external uptime monitoring

## ✅ Post-Deployment Checklist

### Functionality Testing
- [ ] User registration and email verification
- [ ] All user roles can access their respective dashboards
- [ ] Club management features work correctly
- [ ] Event creation and enrollment system functional
- [ ] Skill assignment and tracking operational
- [ ] File uploads (logos, profiles) working
- [ ] Email notifications sending properly
- [ ] Scheduled tasks running automatically

### Performance Testing
- [ ] Page load times under 2 seconds
- [ ] Database queries optimized
- [ ] File uploads within size limits
- [ ] Concurrent user capacity tested
- [ ] Mobile responsiveness verified

### Security Testing
- [ ] HTTPS properly configured
- [ ] User authentication secure
- [ ] File upload validation working
- [ ] CSRF protection active
- [ ] SQL injection prevention verified
- [ ] Access control permissions correct

---

For technical support or deployment assistance, refer to the comprehensive documentation in the `documentation/` folder or contact the development team.
