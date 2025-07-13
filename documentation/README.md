# Student Skill Tracker — README

A comprehensive Laravel-based skill tracking system for educational institutions with multi-club support, role-based access control, and real-time progress monitoring.

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- XAMPP or similar local server environment

### Installation
1. **Clone/Download the project**
   ```bash
   git clone <repository-url>
   cd student_skill_tracker
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   touch database/database.sqlite  # Create SQLite database
   php artisan migrate --seed      # Migrate and seed with sample data
   ```

5. **Start Development Server**
   ```bash
   php artisan serve
   ```

6. **Access the Application**
   - URL: `http://127.0.0.1:8000`
   - Master Admin: `nazmus.sakib.raiyan@g.bracu.ac.bd` / `admin123`
   - Club Manager: `manager@example.com` / `password`
   - Student: `student@example.com` / `password`

## ✨ Key Features

### 🎯 **Skill Tracking System** ✅ **FULLY IMPLEMENTED**
- **Real-time Progress Monitoring:** Live skill point tracking across multiple categories
- **Club-based Skills:** Each club has its own skill categories and point systems
- **Level Progression:** Automatic level calculation based on earned points
- **Achievement System:** Milestone tracking and achievement badges
- **Skill History:** Complete audit trail of all skill point assignments
- **Leaderboards:** Club-specific rankings and progress comparisons
- **Manager Assignment:** Club managers can assign skill points to members

### 👥 **Role-Based Access Control** ✅ **COMPLETE**
- **Master Admin:** Full system control, user management, club oversight
- **Club Manager:** Manage assigned clubs, assign skills, create events
- **Student:** View progress, track achievements, participate in events
- **Permission System:** Granular permissions for specific actions
- **Email Verification:** Secure account activation process

### 🏛️ **Club Management** ✅ **OPERATIONAL**
- **Multi-club Support:** Students can belong to multiple clubs
- **Club-specific Categories:** Each club defines its own skill categories
- **Member Management:** Add/remove students, approve membership requests
- **Club Analytics:** Progress tracking and member statistics
- **Manager Assignment:** Assign multiple managers per club

### 🎪 **Event Management** ✅ **ENHANCED**
- **Event Creation:** Full CRUD operations for club managers
- **Event Types:** Workshops, seminars, contests, field events, custom types
- **Enrollment System:** Student registration with capacity limits
- **Auto-completion:** Automatic event completion tracking
- **File Uploads:** Event logos and supporting materials
- **Real-time UI:** Interactive Vue.js components for seamless experience

### 🚨 **Emergency Announcements** ✅ **VERIFIED**
- **Targeted Broadcasting:** All users, specific roles, clubs, or individuals
- **Priority System:** Emergency, High, Medium, Low with visual indicators
- **Interactive Interface:** Mark-as-read functionality and notifications
- **Dashboard Integration:** Seamless announcement display across all dashboards

### � **Analytics & Reporting** ✅ **INTEGRATED**
- **Real-time Dashboards:** Live data updates for all user roles
- **Progress Analytics:** Detailed skill progression charts and statistics
- **Club Performance:** Comparative analysis across different clubs
- **Activity Logs:** Comprehensive system activity tracking
- **Export Capabilities:** Data export for external analysis

### 🏆 Club & Event Management
- **Club Manager Assignment:** Admins can assign one or more club managers to any club
- **Club Event Creation:** Club managers can create, edit, and delete events for their assigned clubs
- **Event Details:** Name, description, logo (JPEG), start/end date, event type, and venue (Google Maps link)
- **Event Types:** Workshops, seminars, contests, field events, or custom with description
- **Student Access:** Approved students can view all club events in real time
- **Modern UI:** Colorful and interactive interface for both managers and students
- See [`documentation/CLUB_MANAGER_ASSIGNMENT.md`](CLUB_MANAGER_ASSIGNMENT.md) and [`documentation/CLUB_MANAGEMENT_FEATURES.md`](CLUB_MANAGEMENT_FEATURES.md) for details

### 📊 System Monitoring & Analytics
- **System Logs & Activity Monitoring:** Comprehensive dashboard for system health and activity
- **Real-time Metrics:** Database statistics, user analytics, and performance monitoring
- **Log Management:** View, download, and clear system logs with security controls
- **Activity Filtering:** Filter by log levels (Error, Warning, Info, Debug) with time-based search
- **Audit Trails:** User activity logging middleware for complete audit capabilities
- See [`documentation/SYSTEM_LOGS_ACTIVITY.md`](SYSTEM_LOGS_ACTIVITY.md) for complete details

### 💾 **User & Data Management** ✅ **COMPREHENSIVE**
- **Bulk User Import:** CSV import with validation and error handling
- **Enhanced Role Management:** Dedicated interface for role and permission management
- **Club Manager Control:** Ban/unban functionality with audit logging
- **Profile Management:** User profiles with picture upload capabilities
- **Email Verification:** Secure account activation with resend functionality

## 🏗️ Technical Architecture

### Backend Stack
- **Framework:** Laravel 12.x (PHP 8.2+)
- **Database:** SQLite (development), MySQL/PostgreSQL (production ready)
- **Authentication:** Laravel Sanctum with email verification
- **Authorization:** Role-based access control with permissions
- **File Storage:** Laravel filesystem with secure uploads
- **Task Scheduling:** Laravel scheduler for automated processes

### Frontend Technologies
- **Templating:** Blade templates with component system
- **Styling:** Tailwind CSS for responsive design
- **JavaScript:** Vue.js 3 for interactive components
- **Icons:** FontAwesome for consistent iconography
- **Charts:** Chart.js for data visualization

### Database Schema
```sql
-- Core tables
users (id, name, email, role_id, email_verified_at)
roles (id, name, permissions)
clubs (id, name, description, logo)
skill_categories (id, club_id, name, max_points, color)
student_skills (id, user_id, club_id, skill_category_id, total_points, level)
skill_point_history (id, student_skill_id, points, reason, assigned_by_id, awarded_at)

-- Relationships
club_managers (user_id, club_id, banned)
club_student (club_id, user_id, status, created_at)
events (id, club_id, name, description, start_date, end_date, max_participants)
event_enrollments (id, event_id, user_id, status, enrolled_at, completed_at)
```

## 🚀 Deployment & Production

### Server Requirements
- PHP 8.2 or higher with extensions: mbstring, openssl, PDO, tokenizer, XML
- Composer for dependency management
- Node.js 18+ and NPM for asset compilation
- Web server (Apache/Nginx) with URL rewriting
- Database server (MySQL 8.0+, PostgreSQL 13+, or SQLite 3)

### Production Setup
1. **Environment Configuration**
   ```bash
   cp .env.example .env.production
   # Configure database, mail, and other services
   ```

2. **Dependencies & Assets**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm ci && npm run production
   ```

3. **Database & Caching**
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=DatabaseSeeder
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Task Scheduling** (add to crontab)
   ```bash
   * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
   ```

## 🛠️ Development

### Running Tests
```bash
php artisan test                    # Run all tests
php artisan test --coverage       # Run with coverage report
```

### Code Quality
```bash
composer install --dev            # Install dev dependencies
./vendor/bin/phpstan analyse      # Static analysis
./vendor/bin/php-cs-fixer fix     # Code formatting
```

### Database Management
```bash
php artisan migrate:fresh --seed  # Reset database with fresh data
php artisan migrate:rollback      # Rollback last migration batch
php artisan make:migration name    # Create new migration
php artisan make:seeder name       # Create new seeder
```

## 🔧 Troubleshooting

### Common Issues

**"View [student.skills.club] not found"**
```bash
php artisan view:clear
php artisan cache:clear
# Ensure file exists at resources/views/student/skills/club.blade.php
```

**Permission Denied Errors**
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache  # Linux
```

**Migration Errors**
```bash
php artisan migrate:status        # Check migration status
php artisan migrate:fresh --seed  # Fresh install if needed
```

**Asset Compilation Issues**
```bash
npm install                       # Reinstall dependencies
npm run dev                      # Development build
npm run production              # Production build
```

### Performance Optimization
- Enable Laravel caching: `php artisan optimize`
- Use database indexing for large datasets
- Implement Redis for session and cache storage
- Configure CDN for static assets
- Enable gzip compression on web server

## 📚 Documentation Reference

### Core Features
- [`PROJECT_SUMMARY.md`](PROJECT_SUMMARY.md) - Complete project overview
- [`SETUP_DEPLOYMENT.md`](SETUP_DEPLOYMENT.md) - Installation and deployment guide

### User Management
- [`USER_CREATION.md`](USER_CREATION.md) - User creation and management
- [`ENHANCED_ROLE_MANAGEMENT.md`](ENHANCED_ROLE_MANAGEMENT.md) - Role and permission system
- [`BULK_IMPORT_USERS.md`](BULK_IMPORT_USERS.md) - Bulk user import functionality
- [`EMAIL_VERIFICATION.md`](EMAIL_VERIFICATION.md) - Email verification system

### Club & Event Features
- [`CLUB_MANAGEMENT.md`](CLUB_MANAGEMENT.md) - Club management system
- [`CLUB_MANAGER_ASSIGNMENT.md`](CLUB_MANAGER_ASSIGNMENT.md) - Manager assignment
- [`EVENT_ENROLLMENT_SYSTEM.md`](EVENT_ENROLLMENT_SYSTEM.md) - Event enrollment
- [`PARTICIPANT_SLOT_MANAGEMENT.md`](PARTICIPANT_SLOT_MANAGEMENT.md) - Capacity management

### System Features
- [`EMERGENCY_ANNOUNCEMENT_SYSTEM.md`](EMERGENCY_ANNOUNCEMENT_SYSTEM.md) - Announcement broadcasting
- [`SYSTEM_LOGS_ACTIVITY.md`](SYSTEM_LOGS_ACTIVITY.md) - System monitoring
- [`PROFILE_PICTURE.md`](PROFILE_PICTURE.md) - Profile picture management

---

## 📞 Support & Maintenance

For technical support or feature requests, refer to the documentation files or contact the development team. The system is designed for institutional use with educational organizations requiring comprehensive skill tracking and club management capabilities.

**Latest Update:** July 2025 - All core features implemented and fully functional
