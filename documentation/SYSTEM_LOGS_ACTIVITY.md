# System Logs & Activity Monitoring

## Overview
The System Logs & Activity feature provides comprehensive monitoring and logging capabilities for master administrators. This feature enables tracking of system performance, user activities, and system health in real-time.

## Access
- **Route:** `/admin/system-logs`
- **Permission:** Master Admin only
- **Navigation:** Admin Dashboard → Reports → System Logs & Activity

## Features

### 1. System Metrics Dashboard
- **Total Log Entries:** Real-time count of system log entries
- **Active Users (24h):** Number of users active in the last 24 hours
- **Database Queries (24h):** Count of database queries executed
- **System Uptime:** Current system uptime information

### 2. Log Management
- **View Logs:** Real-time display of system logs with filtering
- **Download Logs:** Export log files for external analysis
- **Clear Logs:** Safely clear old log entries with confirmation
- **Log Filtering:** Filter by log levels (All, Error, Warning, Info, Debug)

### 3. Advanced Filtering & Search
- **Date Range Filtering:** Last 24 hours, 7 days, 30 days, 90 days
- **Search Functionality:** Search across log messages and context
- **Log Level Filtering:** Focus on specific types of system events

### 4. System Activity Monitoring
- **Recent Activity:** Real-time display of system events
- **User Activity Tracking:** Monitor user actions and system interactions
- **Database Activity:** Track database operations and performance
- **Context Information:** Detailed context for each logged event

### 5. Log File Management
- **Available Log Files:** List of all system log files
- **File Information:** Size, modification date, and download options
- **Individual Downloads:** Download specific log files
- **File Status:** Visual indicators for log file health

## Technical Implementation

### Controller
- **Location:** `app/Http/Controllers/Admin/SystemLogsController.php`
- **Methods:**
  - `index()` - Main dashboard with metrics and logs
  - `downloadLog()` - Download specific log files
  - `clearLog()` - Clear log files with safety checks

### Middleware
- **User Activity Logging:** `app/Http/Middleware/LogUserActivity.php`
- **Automatic Tracking:** Logs user actions for audit trails
- **Registration:** Registered in `bootstrap/app.php`

### Views
- **Main Dashboard:** `resources/views/admin/system-logs.blade.php`
- **Reports Navigation:** `resources/views/admin/reports.blade.php`
- **Styling:** Custom CSS for improved readability and contrast

### Routes
```php
Route::middleware(['auth', 'verified', 'role:master_admin'])->group(function () {
    Route::get('/admin/reports', [ReportsController::class, 'index'])->name('admin.reports');
    Route::get('/admin/system-logs', [SystemLogsController::class, 'index'])->name('admin.system-logs');
    Route::get('/admin/system-logs/download', [SystemLogsController::class, 'downloadLog'])->name('admin.system-logs.download');
    Route::post('/admin/system-logs/clear', [SystemLogsController::class, 'clearLog'])->name('admin.system-logs.clear');
});
```

## Security Features
- **Master Admin Only:** Restricted to master administrator role
- **Audit Logging:** All log management actions are logged
- **Safe Downloads:** Validation of log file requests
- **CSRF Protection:** All forms protected against CSRF attacks

## Usage Instructions

### Accessing System Logs
1. Login as Master Admin
2. Navigate to Admin Dashboard
3. Click "Reports" card
4. Select "System Logs & Activity"

### Viewing Logs
1. Use the filter controls to narrow down results
2. Select log type (All, Error, Warning, Info, Debug)
3. Choose date range for analysis
4. Use search box for specific terms

### Managing Log Files
1. View available log files in the bottom section
2. Check file sizes and modification dates
3. Download individual files using the download button
4. Clear logs when needed (with confirmation)

### Monitoring System Health
1. Review the metrics cards at the top
2. Monitor active user counts
3. Track database query volume
4. Check system uptime status

## Troubleshooting

### Empty Logs
- Check if the application is generating logs
- Verify log storage permissions
- Review Laravel logging configuration

### Permission Issues
- Ensure user has master_admin role
- Check middleware configuration
- Verify route permissions

### Performance Issues
- Consider log rotation policies
- Monitor log file sizes
- Use date range filtering for large datasets

## Related Features
- **User Management:** Role-based access control
- **Reports Dashboard:** Central reporting hub
- **Activity Monitoring:** User action tracking

## Files Modified/Created
- `app/Http/Controllers/Admin/SystemLogsController.php` (created)
- `app/Http/Controllers/Admin/ReportsController.php` (created)
- `app/Http/Middleware/LogUserActivity.php` (created)
- `resources/views/admin/system-logs.blade.php` (created)
- `resources/views/admin/reports.blade.php` (created)
- `resources/views/admin/dashboard.blade.php` (modified)
- `resources/views/components/navigation.blade.php` (modified)
- `routes/web.php` (modified)
- `bootstrap/app.php` (modified)

## Activity Types Logged

#### User Management Activities
- **User Creation:** Individual and bulk user creation events
- **User Deletion:** User removal with admin attribution
- **Role Changes:** Role assignments and modifications
- **Ban/Unban Actions:** Club manager status changes
- **Bulk Import Operations:** CSV import activities with statistics
  - Import attempts and results
  - Validation errors and skip reasons
  - Success/failure metrics per import session

#### Authentication & Authorization
- **Login Attempts:** Successful and failed login events
- **Permission Changes:** Role and permission modifications
- **Email Verification:** Account verification activities

#### System Operations
- **Database Changes:** Critical data modifications
- **File Operations:** Upload, download, and deletion activities
- **Configuration Changes:** System setting modifications

### Activity Context Information

This feature provides essential monitoring capabilities for maintaining system health and security in the Student Skill Tracker application.