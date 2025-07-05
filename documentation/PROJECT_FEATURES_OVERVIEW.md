# Student Skill Tracker - Features Overview

## User Roles
- **Admin (Master Admin):**
  - Full access to all features, user management, club management, and approvals.
- **Club Manager:**
  - Manages assigned clubs, updates club info, manages club students, and manages club events.
- **Student:**
  - Participates in clubs, views club info, tracks personal progress, and views club events.

## Authentication & Authorization
- Email verification required for all users.
- Role-based access control (RBAC) for all routes and features.

## Club Management
- **Create/Edit/Delete Clubs:** (Admin only)
- **Assign Club Managers:** (Admin only)
- **Update Club Info:** (Club Manager)
  - Edit club name, description, and logo (PNG/JPG/JPEG).
  - Logo displayed for managers and students.
- **View Club Info:** (All roles)
  - Club logo and description visible to members.
- **Manage Club Events:** (Club Manager)
  - Create, edit, and delete events for assigned clubs.
  - Events include name, description, logo (JPEG), start/end date, event type, event type description (if "other"), and venue/location (Google Maps link).
  - Event type options: workshops, seminars, contests, field events, or other (with custom description).
  - Venue/location is set using a Google Maps link.
  - Club managers can upload a new logo when editing an event.
  - Event logos are validated and stored securely.

## Student Management in Clubs
- **Add Students to Club:** (Club Manager)
  - Search and select one or multiple registered students by name/email.
  - Only students not already in the club are shown.
  - Added students are set as "pending" for admin approval.
- **Remove Students from Club:** (Club Manager)
  - Remove any student (pending or approved) from their club.
- **Approve/Reject Students:** (Admin)
  - View all pending student approvals for clubs on the user management page.
  - Approve or reject each student for club membership.

## User Management
- **Create Club Manager/Student:** (Admin)
- **Bulk Import Users:** (Admin)
  - Import multiple users from CSV files
  - Support for students, club managers, and master admins
  - Template download with proper format examples
  - Validation and error handling for duplicate emails and invalid data
  - Import summary with success/failure statistics
  - Activity logging for audit purposes
  - **📖 Detailed Documentation:** See [BULK_IMPORT_USERS.md](BULK_IMPORT_USERS.md)
- **Enhanced Role Management:** (Master Admin only)
  - Dedicated role management interface accessible via "Manage Roles" from Users page
  - Comprehensive user filtering by role (Students, Club Managers, All)
  - User status tracking with visual indicators (Active, Banned, No Club Assignment)
  - Delete user functionality with confirmation and audit logging
  - Statistics dashboard showing user counts and banned manager metrics
  - Real-time status updates and responsive design
- **Club Manager Ban/Unban System:** (Master Admin only)
  - Ban and unban club managers with single-click actions
  - Automatic status tracking and visual indicators
  - Handles club managers with and without club assignments
  - Confirmation dialogs for all destructive actions

## Emergency Announcement Broadcasting System ✅ **FULLY IMPLEMENTED**
- **Master Admin Control:** (Master Admin only)
  - Create, edit, delete, and manage emergency announcements
  - Priority-based system (Emergency, High, Medium, Low) with color-coded styling
  - Toggle announcements active/inactive status
  - Set optional expiration dates for time-sensitive messages
- **Advanced Targeting Options:**
  - **All Users:** Broadcast to entire system
  - **Role-Based:** Target specific roles (Students, Club Managers, Master Admins)
  - **Club-Specific:** Target announcements to specific clubs ✅ **VERIFIED WORKING**
    - Students enrolled in target clubs can view announcements
    - Club managers managing target clubs can view announcements
    - Perfect for emergency club meetings and club-specific updates
  - **Individual Users:** Send personalized announcements to specific users
- **User Experience:**
  - Announcement banners on all dashboards (Admin, Student, Club Manager, Profile)
  - Interactive mark-as-read functionality with smooth animations
  - Priority-based visual styling with contextual icons
  - Responsive design with dark mode support
  - Creamish text color (#8B7355) for improved readability
- **Technical Features:**
  - AJAX-powered interactions with CSRF protection
  - Read tracking system to monitor announcement visibility
  - Efficient database queries with optimized targeting logic
  - Activity logging integration for audit purposes
- **📖 Detailed Documentation:** See [EMERGENCY_ANNOUNCEMENT_SYSTEM.md](EMERGENCY_ANNOUNCEMENT_SYSTEM.md)

## System Monitoring & Analytics
- **System Logs & Activity Monitoring:** (Master Admin only)
  - Comprehensive system monitoring dashboard with real-time metrics
  - Log file management (view, download, clear) with security controls
  - System activity filtering by log levels (Error, Warning, Info, Debug)
  - Database activity statistics and user analytics
  - User activity logging middleware for audit trails
  - System health monitoring (disk usage, memory usage, performance metrics)
  - Time-based filtering and search functionality across logs
  - Access via Reports → System Monitoring section

## Student Dashboard
- **My Clubs:**
  - See all clubs the student is participating in, with logo and approval status.
  - Click a club to view its logo and description.
- **My Skills:**
  - Track and view skill development (static or dynamic, as implemented).
- **Recent Activities:**
  - View recent event enrollments and completions with real-time updates.
  - Automatic activity tracking when events are completed.
- **Statistics:**
  - Total event enrollments and completed events count.
  - Real-time statistics that update as events are completed.
- **Event Enrollment:**
  - Quick access to enrollment history and status.
  - Dashboard reflects completed events automatically when deadlines pass.

## Notifications
- **Admin Notification:**
  - Admins are notified when a student is added to a club and pending approval.
- **Custom Email Verification:**
  - Users receive a custom email verification notification.

## Club Event Management
- **Create/Edit/Delete Events:** (Club Manager only)
  - Club managers can create, update, and delete events for their assigned clubs.
  - Event details include name, description, logo (JPEG), start/end date.
  - Event logos are validated and stored securely.
  - Modern, colorful, and interactive UI for event management (see `ClubManagerEvents.vue`).
- **View Events:** (Approved Students only)
  - Approved students can view all events for their clubs in real time.
  - Events are displayed with logos, descriptions, and dates in a beautiful, readable UI (see `ClubEvents.vue`).
  - Only students approved for a club can view its events.
- **Event Enrollment System:**
  - Students can enroll in seminars, workshops, and contests.
  - Automatic completion when event deadlines pass.
  - Real-time enrollment status tracking and dashboard integration.
  - Club managers can view and manage event enrollments.
- **Participant Slot Management:**
  - Club managers can set maximum participant limits for enrollable events.
  - Visual progress bars show enrollment capacity and remaining slots.
  - Automatic prevention of over-enrollment when events are full.
  - Flexible limits - optional setting with unlimited participants by default.
- **UI Improvements:**
  - All event management and viewing pages feature white backgrounds, colorful buttons, improved spacing, and prominent event sections for easy access.
  - Enrollment buttons and status indicators for enhanced user experience.
  - Capacity management with visual progress tracking and slot availability.

## Technical & Security Notes
- **Logo Storage:**
  - Logos stored in `storage/app/public/club_logos`, served via `public/storage` symlink.
- **Event Logo Storage:**
  - Event logos stored in `storage/app/public/event_logos`, served via `public/storage` symlink.
- **Validation:**
  - Logo uploads validated for type/size. Only registered students can be added to clubs.
  - Event logo uploads validated for JPEG type/size. Only club managers can manage events for their assigned clubs. Only approved students can view events.
- **Security:**
  - Club managers can only manage their assigned clubs. Students can only view their own clubs.

---
For more details, see the documentation folder and codebase (controllers, Blade views, routes).
