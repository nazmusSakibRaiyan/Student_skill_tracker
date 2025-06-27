# Setup and Deployment Guide

## Event Enrollment System Setup

### 1. Database Migration
Run the event enrollments migration:
```bash
php artisan migrate
```

### 2. Task Scheduling (Production)
For the auto-completion system to work in production, you need to set up the Laravel scheduler.

#### Linux/macOS (Cron Job)
Add this cron entry to run every minute:
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

#### Windows (Task Scheduler)
1. Open Task Scheduler
2. Create Basic Task
3. Set to run every minute
4. Action: Start a program
5. Program: `php`
6. Arguments: `artisan schedule:run`
7. Start in: `C:\path\to\your\project`

### 3. Manual Commands
You can also run the auto-completion manually:
```bash
# Auto-complete ended event enrollments
php artisan events:auto-complete

# Check scheduler status
php artisan schedule:list
```

### 4. Storage Setup
Ensure storage symlink is created for file uploads:
```bash
php artisan storage:link
```

### 5. Cache Configuration
Clear cache after deployment:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Environment Configuration

### Required Environment Variables
```env
# App
APP_NAME="Student Skill Tracker"
APP_ENV=production
APP_DEBUG=false
APP_TIMEZONE=Asia/Dhaka

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourapp.com
```

### File Permissions
Ensure proper permissions for:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod 664 database/database.sqlite
```

## Testing the System

### 1. Create Test Data
```bash
php artisan migrate:fresh --seed
```

### 2. Test Auto-Completion
```bash
# Create a test event that has ended
php artisan tinker
> $event = App\Models\Event::create([
    'club_id' => 1,
    'name' => 'Test Workshop',
    'description' => 'Test auto-completion',
    'start_date' => now()->subHours(2),
    'end_date' => now()->subMinutes(30),
    'event_type' => 'workshops'
]);

# Create enrollment
> App\Models\EventEnrollment::create([
    'event_id' => $event->id,
    'user_id' => 1, // Student user ID
    'status' => 'enrolled',
    'enrolled_at' => now()->subHours(1)
]);

# Test auto-completion
> exit
php artisan events:auto-complete
```

### 3. Test Frontend
1. Log in as a student
2. Navigate to a club
3. Try enrolling in an event
4. Check dashboard for activity updates

## Monitoring

### Log Files
Monitor these logs for issues:
- `storage/logs/laravel.log` - Application logs
- Web server error logs
- Cron job logs (for scheduling)

### Health Checks
- Verify scheduler is running: `php artisan schedule:list`
- Check enrollment counts: Visit student dashboard
- Test manual completion: Use club manager interface

## Troubleshooting

### Common Issues

#### Scheduler Not Running
- Verify cron job is set up correctly
- Check cron job logs for errors
- Ensure PHP path is correct in cron command

#### Enrollments Not Auto-Completing
- Check event end_date format and timezone
- Verify scheduler is running
- Run manual command: `php artisan events:auto-complete`

#### File Upload Issues
- Verify storage symlink exists
- Check file permissions
- Ensure storage directories are writable

#### Database Errors
- Check database file permissions
- Verify database path in .env
- Ensure SQLite is installed

### Debug Commands
```bash
# Check application status
php artisan about

# View scheduled tasks
php artisan schedule:list

# Test auto-completion
php artisan events:auto-complete --verbose

# Clear all caches
php artisan optimize:clear
```

## Performance Optimization

### Production Optimizations
```bash
# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize Composer autoloader
composer install --optimize-autoloader --no-dev

# Enable OPcache (in php.ini)
opcache.enable=1
opcache.memory_consumption=128
```

### Database Optimization
- Consider migrating to MySQL/PostgreSQL for large datasets
- Add database indexes for frequently queried fields
- Regular database maintenance and cleanup

---

This completes the setup and deployment guide for the Student Skill Tracker with Event Enrollment System.
