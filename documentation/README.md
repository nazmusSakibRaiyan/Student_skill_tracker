# Student Skill Tracker — README

This is the main README for the Student Skill Tracker project. For detailed documentation on specific features, see the `documentation/` folder.

## Quick Start
1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Copy `.env.example` to `.env` and configure your environment
4. Run migrations: `php artisan migrate --seed`
5. Start the server: `php artisan serve`

## Key Features

### 🚨 Emergency Announcement Broadcasting System ✅ **NEW & VERIFIED**
- Master admins can create and manage emergency announcements
- **Advanced Targeting:** All users, specific roles, clubs, or individual users
- **Club-Specific Announcements:** Students and club managers see announcements for their clubs
- **Priority System:** Emergency, High, Medium, Low with color-coded styling
- **Interactive UI:** Mark-as-read functionality, smooth animations, dark mode support
- **Dashboard Integration:** Announcement banners on all user dashboards
- See [`documentation/EMERGENCY_ANNOUNCEMENT_SYSTEM.md`](EMERGENCY_ANNOUNCEMENT_SYSTEM.md) for complete details

### 🎯 Event Enrollment System
- Students can enroll in seminars, workshops, and contests
- Automatic completion tracking when event deadlines pass
- **Participant slot management** with capacity limits and visual progress bars
- Real-time dashboard updates with enrollment statistics
- Progress monitoring and activity history
- Hourly auto-completion via scheduled tasks
- See [`documentation/EVENT_ENROLLMENT_SYSTEM.md`](EVENT_ENROLLMENT_SYSTEM.md) for complete details

### �🏆 Club Event Management
- Club managers can create, edit, and delete events for their assigned clubs.
- Events include name, description, logo (JPEG), start/end date, event type, event type description (if "other"), and venue/location (Google Maps link).
- Event type options: workshops, seminars, contests, field events, or other (with custom description).
- Venue/location is set using a Google Maps link.
- Club managers can upload a new logo when editing an event.
- Event logos are validated and stored securely.
- Students approved for a club can view all its events in real time.
- Modern, colorful, and interactive UI for both managers and students (see `ClubManagerEvents.vue` and `ClubEvents.vue`).
- Only JPEG files are accepted for event logos.

### 👤 User & Role Management
- **Enhanced Role Management:** Dedicated interface for managing user roles and permissions
- **Bulk User Import:** Import multiple users from CSV files with validation and error handling
- **Club Manager Ban/Unban:** Advanced club manager status control with audit logging
- **User Creation:** Admins can add new students and club managers via UI or API
- Students are assigned the correct role automatically
- See [`documentation/USER_CREATION.md`](USER_CREATION.md) and [`documentation/ENHANCED_ROLE_MANAGEMENT.md`](ENHANCED_ROLE_MANAGEMENT.md) for details

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

## Documentation
- See [`documentation/EMERGENCY_ANNOUNCEMENT_SYSTEM.md`](EMERGENCY_ANNOUNCEMENT_SYSTEM.md) for emergency announcement broadcasting
- See [`documentation/EVENT_ENROLLMENT_SYSTEM.md`](EVENT_ENROLLMENT_SYSTEM.md) for event enrollment and auto-completion
- See [`documentation/PARTICIPANT_SLOT_MANAGEMENT.md`](PARTICIPANT_SLOT_MANAGEMENT.md) for capacity management and slot limits
- See [`documentation/SYSTEM_LOGS_ACTIVITY.md`](SYSTEM_LOGS_ACTIVITY.md) for system monitoring and activity logging
- See [`documentation/ENHANCED_ROLE_MANAGEMENT.md`](ENHANCED_ROLE_MANAGEMENT.md) for advanced user role management
- See [`documentation/BULK_IMPORT_USERS.md`](BULK_IMPORT_USERS.md) for bulk user import from CSV files
- See [`documentation/EMAIL_VERIFICATION.md`](EMAIL_VERIFICATION.md) for email verification
- See [`documentation/PROJECT_SUMMARY.md`](PROJECT_SUMMARY.md) for project overview
- See [`documentation/CLUB_MANAGER_ASSIGNMENT.md`](CLUB_MANAGER_ASSIGNMENT.md) for club manager assignment
- See [`documentation/USER_CREATION.md`](USER_CREATION.md) for user creation
- See [`documentation/PROFILE_PICTURE.md`](PROFILE_PICTURE.md) for profile picture feature
- See [`documentation/CLUB_MANAGEMENT_FEATURES.md`](CLUB_MANAGEMENT_FEATURES.md) for club and event management
- See [`documentation/CLUB_MANAGER_BAN.md`](CLUB_MANAGER_BAN.md) for club manager ban/unban feature

---
For more, see the documentation folder.
