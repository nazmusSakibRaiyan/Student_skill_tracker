# Club Management Features Documentation

## 1. Club Manager Features
- **Update Club Info:**
  - Club managers can update their assigned club's name, description, and logo (PNG/JPG/JPEG only).
  - Logos are displayed in the dashboard and for students.
- **Add Students to Club:**
  - Club managers can search and select one or multiple registered students (by name/email) to add to their club.
  - Added students are set as "pending" and require admin approval.
- **Remove Students:**
  - Club managers can remove any student (pending or approved) from their club.
- **Manage Club Events:**
  - Club managers can create, edit, and delete events for their assigned clubs.
  - Events include name, description, logo (JPEG), start/end date, event type, event type description (if "other"), and venue/location (Google Maps link).
  - Event type options: workshops, seminars, contests, field events, or other (with custom description).
  - Venue/location is set using a Google Maps link.
  - Event logos are validated and stored securely.
  - Club managers can upload a new logo when editing an event.
  - Event management UI is interactive, colorful, and user-friendly (see `ClubManagerEvents.vue`).

## 2. Admin Features
- **Approve/Reject Students:**
  - Admins can view all pending student approvals for clubs directly on the user management page.
  - Admins can approve or reject each student for club membership.
- **Assign Club Managers:**
  - Admins can assign club managers to clubs (existing feature).

## 3. Student Features
- **View Assigned Clubs:**
  - Students see a "My Clubs" section listing all clubs they are participating in, with logo and approval status.
  - Students can click a club to view its logo and description.
- **View Club Events:**
  - Approved students can view all events for their clubs in real time.
  - Events are displayed with logos, descriptions, and dates in a beautiful, readable UI (see `ClubEvents.vue`).

## 4. Technical Notes
- **Logo Storage:**
  - Club and event logos are stored in `storage/app/public/club_logos` and `storage/app/public/event_logos` and served via the `public/storage` symlink.
- **Validation:**
  - Logo uploads are validated for file type and size.
  - Only registered students not already in the club can be added.
  - Only JPEG files are accepted for event logos.
- **Security:**
  - Club managers can only manage their assigned clubs and events.
  - Students can only view clubs and events they are members of.

---
For further customization or troubleshooting, see the relevant Blade views, Vue components, controllers, and routes.
