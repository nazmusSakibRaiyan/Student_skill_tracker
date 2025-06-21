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
- **Assign/Manage Roles:** (Admin)
- **View User Reports & Statistics:** (Admin)

## Student Dashboard
- **My Clubs:**
  - See all clubs the student is participating in, with logo and approval status.
  - Click a club to view its logo and description.
- **My Skills:**
  - Track and view skill development (static or dynamic, as implemented).
- **Recent Activities, Achievements, Goals:**
  - View recent learning activities, earned badges, and progress toward goals.

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
- **UI Improvements:**
  - All event management and viewing pages feature white backgrounds, colorful buttons, improved spacing, and prominent event sections for easy access.

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
